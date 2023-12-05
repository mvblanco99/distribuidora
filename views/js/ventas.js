//total - pedido = disponible
//si disponible > solicitado = vender()
//si disponible < solicitado = no vender

/*Busqueda del producto a vender

    condiciones de busqueda
        - Se debe buscar solo los productos que tengan el estatus venta
        -En el caso de que se busque un producto que no tenga el estatus vender, se debe enviar un alerta.

    Flujo del programa

    - Al cargar la pagina se deben buscar los datos de todos los productos que tengan el estatus vender.
    - Cuando el usuario intente ingresar un producto a la lista, se debe comparar el codigo con los datos extraidos de la base de datos
    - En el caso de corresponder, se ingresa a la lista.
    - En caso contrario, se envia un alerta
    - Cuando el usuario presione el boton guardar venta, se debe hacer una nueva consulta a la base de datos y extraer la cantidad disponible actualizada de cada producto, para comparar con la cantidad solicitada
    - En caso de que la cantidad disponible de cada producto satisfaga la cantidad solicitada, guarda la venta
    - En caso contrario se debe enviar un alerta
    - Luego se debe actualizar la cantidad disponible de los productos afectados en la venta

*/

import {url} from "./urls.js";
import { alerta, parseToDoubleWithTwoDecimals, obtenerFechaActual_format_date_time, loadDatos_user } from "./utils.js";
import { validarCamposVacios, validarNumeros } from "./validaciones.js";

const { url_productos, url_ventas } = url;

const d = document,
$btn_add_list = d.querySelector('#btn-add-list'),
$lista_venta_productos = d.querySelector('#lista_venta_productos'),
$fragment_list = d.createDocumentFragment(),
$btn_registrar_venta = d.querySelector('#btn_concretar_venta');
let data_productos;
let monto_total_venta = 0;
const productos_seleccionados = [];

let producto_encontrado = false; //Variable usada para manejar el estado de la busqueda de los productos y establecer el funcionamiento de la letra 'Enter' a traves del evento keydown

//Extraemos la data de todos los productos registrados con estatus venta
const extraerDatosProductos = async (url) => {

    try {
        const res =  await fetch(`${url}?consultar_todos_productos=1`);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        //console.log(data)
        data_productos = data["success"]; //Guardamos los datos de los productos
    } catch (error) {
        console.log(error);
        let titulo = error.status || "Error";
        let mensaje = error.statusText || "Ocurrio un error, Contacte al Administrador";
        alerta({
            titulo,
            mensaje,
            tipo_mensaje:"error"
        });
    }

}

//Mostramos los productos seleccionados
const show_lista_productos = (lista_productos_seleccionados) => {

    //enlazamos el template creado en el HTML
    const $items_lista_productos = d.querySelector('#items_lista_productos').content;

    if(lista_productos_seleccionados.length > 0){
        
        monto_total_venta = 0;
        lista_productos_seleccionados.forEach(elemento => {
            //Insertamos los datos en el template
            $items_lista_productos.querySelector('.id').dataset.id = elemento['cod_producto'];
            
            $items_lista_productos.querySelector('.name_producto').textContent = `${elemento['nombre_producto']} ${ elemento['grabado_excento'] == 1 ? '(G)' : '(E)'}`;
            
            $items_lista_productos.querySelector('.stock').textContent = elemento['cantidad'];
            $items_lista_productos.querySelector('.price').textContent = `$${elemento['precio']}`;

            let total = parseToDoubleWithTwoDecimals(Number(elemento['precio'] * Number(elemento['cantidad'])));

            $items_lista_productos.querySelector('.total').textContent = `$${total}`;

            let iva = elemento['grabado_excento'] == 1 
                ? 
                total * 0.16 
                : 0;

            $items_lista_productos.querySelector('.iva').textContent = `$${iva}`;

            let subtotal = Number(total) - Number(iva);

            $items_lista_productos.querySelector('.subtotal').textContent = `$${subtotal}`;

            monto_total_venta = parseToDoubleWithTwoDecimals(Number(total) + Number(monto_total_venta));

            $items_lista_productos.querySelector('.btn-danger').dataset.id = elemento['cod_producto'];
            $items_lista_productos.querySelector('.borrar').dataset.id = elemento['cod_producto'];

            //guardamos una copia de la estrutura actual del template en la variable $node
            let $clone = $items_lista_productos.cloneNode(true);
            //Guardamos el nodo en el fragment
            $fragment_list.append($clone);
        });
        
        //Limpiamos la lista
        $lista_venta_productos.innerHTML = "";
        //Insertamos el fragment en la listas
        $lista_venta_productos.append($fragment_list);
    }else{
        monto_total_venta = 0;
        $lista_venta_productos.innerHTML = "";
    }
}

//Agregamos el producto al array de productos seleccionados
const addProduct_in_array_product = (id_producto, cantidad) => {
    
    //Obtenemos datos del producto solicitado
    let datos_producto = data_productos.filter( element => element['id_producto'] == id_producto);

    //extraemos el nombre y codigo del producto
    let nombre_producto = `${datos_producto[0]['nombre_producto']} ${data_productos[0]['contenido_neto']} ${data_productos[0]['nombre_presentacion']}`;
    let cod_producto = datos_producto[0]['codigo_producto'];
    let grabado_excento = datos_producto[0]['grabado_excento'];
    let precio =  datos_producto[0]['precio'];

    //Creamos un objeto con todos los datos ingresados
    let producto_seleccionado = {
        id_producto,
        cod_producto,
        nombre_producto,
        cantidad,
        grabado_excento,
        precio,
    }

    //Agregamos los datos al array de productos seleccionados
    productos_seleccionados.push(producto_seleccionado);
    producto_encontrado = false; //Reseteamos el valor de producto encontrado para poder llamar nuevamente a la funcion addlist desde el evento keydown
    d.querySelector('#codigo_producto').value = "";
    d.querySelector('#codigo_producto').parentElement.classList.remove("is-dirty");
    d.querySelector('#cantidad').value = "";
    d.querySelector('#cantidad').parentElement.classList.remove("is-dirty");
    d.querySelector('#codigo_producto').focus();

}

//Agregamos el producto a la lista de productos seleccionados
const addList = async() => {

    //Extraemos datos del formulario
    let codigo_producto =d.querySelector('#codigo_producto');
    let cantidad = d.querySelector('#cantidad');

    let arrayInputs = [codigo_producto,cantidad];

    //validamos datos del formulario
    if(!validarCamposVacios(arrayInputs) || !validarNumeros(codigo_producto) || !validarNumeros(cantidad)){
        return;
    }

    //Buscamos el producto solicitado por medio del codigo del producto
    let producto_solicitado = buscarProducto(codigo_producto.value);

    //Verificamos si el producto fue encontrado
    if(producto_solicitado === null){
        // errorBuscarProducto = true;
        alerta({
            titulo: "Producto no se encuentra disponible",
            tipo_mensaje: "error",
            bool: true,
            callback : () =>{
                setTimeout(() => {
                    d.querySelector('#codigo_producto').focus();
                }, 100);
            }
        });
        codigo_producto.parentElement.classList.add("is-invalid");
        producto_encontrado = false; //Reseteamos el valor de producto encontrado para poder llamar nuevamente a la funcion addlist desde el evento keydown
        return;
    }

    producto_encontrado = true;

    //Verificamos si el array de productos seleccionados esta vacio
    if(productos_seleccionados.length === 0){
        //Si esta vacio, entonces agregamos el producto
        addProduct_in_array_product(producto_solicitado.id_producto,cantidad.value);
    }else{

        let encontrado = false;

        //Si el array no esta Vacio, verificamos si el producto solicitado ya se encuentra dentro el array
        for (let i = 0; i < productos_seleccionados.length; i++) {
            if(productos_seleccionados[i]['id_producto'] == producto_solicitado.id_producto){
                encontrado = true;
                break;
            }
        }

        if(encontrado){

            //Borramos los valores del input para manejar el estado de la busqueda de los productos y establecer el funcionamiento de la letra 'Enter' a traves del evento keydown
            d.querySelector('#codigo_producto').value = "";
            d.querySelector('#cantidad').value = "";

            producto_encontrado = false; //Reseteamos el valor de producto encontrado para poder llamar nuevamente a la funcion addlist desde el evento keydown
            d.querySelector('#codigo_producto').focus();

            alerta({
                titulo: 'Producto ya se encuentra seleccionado',
                tipo_mensaje: "warning",
            });
            return;
        }else{
            addProduct_in_array_product(producto_solicitado.id_producto,cantidad.value);
        }
    }

    show_lista_productos(productos_seleccionados);
}

//Eliminamos el producto de la lista de productos seleccionados
const quitar_producto_lista = (id) => {
    
    let posicion_producto = null;

    //Recuperamos la posicion del producto a borrar
    for (let i = 0; i < productos_seleccionados.length; i++) {
        if(productos_seleccionados[i]['cod_producto'] == id){
            posicion_producto = i;
            break;
        }
    }

    //Eliminamos el producto de la lista
    productos_seleccionados.splice(posicion_producto,1);

    //Mostramos la lista actualizada
    show_lista_productos(productos_seleccionados);
}

//Guardamos datos de la compra (Tabla compra_productos)
const save_venta_productos = async(datos,url) => {

    console.log(datos);
    const body = {
        method : 'POST',
        body : JSON.stringify(datos) 
    }

    try {
        const res = await fetch(`${url}?recibir_datos_venta_local=1`,body);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        return data.success;
    } catch (error) {   
        console.log(error);
        let titulo = error.status || "Error";
        let mensaje = error.statusText || "Ocurrio un error, Contacte al Administrador";
        alerta({
            titulo,
            mensaje,
            tipo_mensaje:"error"
        });
    }
}

//Guardamos la nueva cantidad disponible de los productos afectados
const saveCantidadesDisponibles = async(datos_productos,url) =>{
    
    const body = {
        method : 'POST',
        body : JSON.stringify(datos_productos)
    };

    try {
        const res = await fetch(`${url}?modificar_cantidad_disponible_producto=1`,body);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        if(data['success'] == 1){
            alerta({
                titulo : "Venta ha sido registrada exitosamente",
                tipo_mensaje : "success",
                callback : ()=>{
                    window.location = "resumen_ventas";  
                  },
                  bool: true
            })
        }else if(data['success'] == 0){
            alerta({
                titulo : "Ha ocurrido un error durante el registro",
                tipo_mensaje : "error"
            })
        }
    } catch (error) {
        console.log(error);
        let titulo = error.status || "Error";
        let mensaje = error.statusText || "Ocurrio un error, Contacte al Administrador";
        alerta({
            titulo,
            mensaje,
            tipo_mensaje: "error"
        });
    }

}

//Buscamos producto solicitado
const buscarProducto = (codigo_producto) => {

    if(codigo_producto === ""){
        return;
    }

    let producto_solicitado = null;
    //Recorremos el array de productos y devolvemos del producto solicitado
    for (let i = 0; i < data_productos.length; i++) {
        if(codigo_producto === data_productos[i].codigo_producto){
            producto_solicitado = data_productos[i];
            break;
        }
    }

    return producto_solicitado;
}

//buscamos los datos de un conjunto de productos seleccionados
const buscar_productos_especificos = async (productos,url) => {

    let body = {
        method : 'POST',
        body : JSON.stringify(productos),
    }

    try {
        let res = await fetch(`${url}?consultar_datos_productos=1`,body);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        let data = await res.json();
        return data.success;
    } catch (error) {
        console.log(error);
        let titulo = error.status || "Error";
        let mensaje = error.statusText || "Ocurrio un error, Contacte al Administrador";
        alerta({
            titulo,
            mensaje,
            tipo_mensaje:"error"
        });
    }
}

//Comprobamos si la cantidad disponible de un producto es suficiente para satisfacer la cantidad solicitada
const comprobar_disponibilidad_producto = async (productos) => {

    let vender = true;
    let array_id_productos = productos.map(e=> e.id_producto);

    //Extraemos nuevamente los datos de los productos seleccionados, con el fin de tener lo mas actualizada posible la cantidad disponible de cada producto
    let data_productos = await buscar_productos_especificos({data : array_id_productos},url_productos);

    //Ordenar de menor a mayor los productos utilizando el id
    data_productos = data_productos.sort((a,b) => a.id_producto - b.id_producto);
    productos = productos.sort((a,b) => a.id_producto - b.id_producto);

    console.log(productos);
    
    //Comparamos cantidades
    for (let i = 0; i < productos.length; i++) {
        if(Number(productos[i].cantidad) > Number(data_productos[i].cantidad_disponible)){
            alert(`No hay ${productos[i].nombre_producto} suficientes`);
            vender = false;
            break;
        }
    }

    //En caso de que haya disponibilidad realizamos un calculo para obtener la nueva cantidad disponible
    let cantidad_actualizada;
    if(vender){
        cantidad_actualizada = modificar_cantidad_disponible(productos,data_productos);
    }

    return {vender, cantidad_actualizada};
}

//modificar la cantidad disponible de los productos 
const modificar_cantidad_disponible = (productos, data_productos) => {

    let cantidades_actualizadas = [];
    //Realizamos las operaciones para actualizar la cantidad disponible de los productos afectados en la venta

    productos.forEach((item,index) => {
       
        let producto = {
            id_producto : item.id_producto,
            //Nueva cantidad disponible
            cantidad_disponible : Number(data_productos[index].cantidad_disponible) - Number(item.cantidad),
        }
        cantidades_actualizadas.push(producto);
    });
    
    return cantidades_actualizadas;
}

d.addEventListener('keydown', e => {

    if(e.keyCode === 13 && d.querySelector('#codigo_producto').value != "" && d.querySelector('#cantidad').value != "" && !producto_encontrado && !d.querySelector('#codigo_producto').parentElement.classList.contains('is-invalid')){
        e.preventDefault();
        e.stopPropagation();
        addList();
        return;
    }

    if(e.keyCode === 13 && d.querySelector('#codigo_producto').value != "" && !d.querySelector('#codigo_producto').parentElement.classList.contains('is-invalid')){
        
        e.preventDefault();
        e.stopPropagation();
        
        let codigo_producto = data_productos.filter(producto => producto.codigo_producto == d.querySelector('#codigo_producto').value);

        if(codigo_producto.length === 0){
            alerta({
                titulo: "Producto no se encuentra disponible",
                tipo_mensaje: "error",
                bool:true,
                callback : () => {
                    d.querySelector('#codigo_producto').parentElement.classList.remove("is-invalid");
                }
            });
            producto_encontrado = false;//Reseteamos el valor de producto encontrado para poder llamar nuevamente a la funcion addlist desde el evento keydown
            d.querySelector('#codigo_producto').parentElement.classList.add("is-invalid");
            d.querySelector('#codigo_producto').focus();
            return;
        }else{
            d.querySelector('#cantidad').focus();
            return;
        }
    }
});

d.addEventListener('click', async ev => {

    //Agregamos productos a la lista
    if(ev.target === $btn_add_list){
        ev.preventDefault();
        addList();
    }

    //Eliminamos producos de la lista
    if(ev.target.classList.contains('btn-danger') || ev.target.classList.contains('borrar')){
        ev.preventDefault();
        quitar_producto_lista(ev.target.dataset.id);
        d.querySelector('#codigo_producto').focus();
    }

    //Guardamos datos de la venta
    if(ev.target === $btn_registrar_venta){
        
        ev.preventDefault();

        //Verficamos si han agregado productos a la lista
        if(productos_seleccionados.length === 0){
            alerta({
                titulo : "Debe ingresar productos a la lista",
                tipo_mensaje : "warning"
            });
            return;
        }

        let {vender, cantidad_actualizada} = await comprobar_disponibilidad_producto(productos_seleccionados);

        if(vender){

                //PREPARAMOS LOS DATOS DE LAS VENTAS

                //Extraemos los datos del usuario logeado para asi tener agregarlo al registro y tener control sobre que usuario hizo tal registro
                const datos = loadDatos_user();

                let arrayDatos;

                try {
                arrayDatos = JSON.parse(datos);  
                } catch (error) {
                    arrayDatos = [];
                }

                //Extraemos el id de administrador
                let admin = arrayDatos['id_usuario'];

                let fecha_venta = obtenerFechaActual_format_date_time();
                let venta_registrada = await save_venta_productos(
                    {
                        fecha_venta,
                        productos_seleccionados,
                        id_admin : admin,
                        monto_total_venta,
                    },url_ventas
                );
        
            if(venta_registrada == 1){
                saveCantidadesDisponibles({datos_productos : cantidad_actualizada},url_productos);
            }
        }
    }
});

d.addEventListener('DOMContentLoaded', async e => {
    await extraerDatosProductos(url_productos); 
});