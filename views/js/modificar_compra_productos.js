import { url } from "./urls.js";
import { alerta } from "./utils.js";
import { validarCamposVacios, validarNumeros } from "./validaciones.js";

const {get_sessiones,url_compra, url_productos} = url;

const d = document,
$btn_modificar_compra = d.querySelector('#btn_modificar_compra'),
$btn_add_list = d.querySelector('#btn-add-list'),
$btn_registrar_compra = d.querySelector('#btn-registrar-compra'),
$lista_compra_productos = d.querySelector('#lista_compra_productos'),
$fragment_list = d.createDocumentFragment();
const productos_seleccionados = [];
let  datos_compra;
let all_data_productos;

//Mostramos el numero de factura de la compra
const show_numero_factura = (data) => {
    d.querySelector('#numero_factura').value = data;
    d.querySelector('#label_numero_factura').classList.add('is-dirty');    
}

//Recuperamos los datos de la compra utilizando el id recuperado (Tabla Compra)
const extraer_datos_compra =  async(id_compra,url) => {
    
    try {
        const res = await fetch(`${url}?obtener_datos_compra_especifica=${id_compra}`);
        if(!res.ok) throw {status:res.status, statusText:res.statusText};
        const data = await res.json();
        if(typeof data['success'][0] === "string"){
            alerta({
                titulo: "Error",
                mensaje : data["success"][0],
                tipo_mensaje: "error",
            });
        }else{
            //datos_compra = data['success'];
            return data['success'];
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

//Recuperamos el id de la compra por medio de la variable de session creada en php
const extraer_id_compra= async(url) => {
    try {
        const res = await fetch(`${url}?extraer_id_modificar_compra=1`)
        if(!res.ok) throw {status: res.status, statusText: res.statusText};
        const data = await res.json();
        return data;
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

//Obtenemos los datos de la tabla compra_productos de una compra especifia
const extraer_datos_compra_productos = async(id_compra,url) => {

    try {
        const res = await fetch(`${url}?obtener_datos_compra_productos=${id_compra}`);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        return data["success"];
    } catch (error) {
        console.log(error)
        let titulo = error.status || "Error";
        let mensaje = error.statusText || "Ocurrio un error, Contacte al Administrador";
        alerta({
            titulo,
            mensaje,
            tipo_mensaje: "error"
        });
    }
}

//Extraemos la data de todos los productos registrados
const extraer_datos_productos = async (url) => {

    try {
        const res =  await fetch(`${url}?consultar_todos_productos=1`);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        all_data_productos = data["success"]; //Guardamos los datos de los productos
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

        lista_productos_seleccionados.forEach(elemento => {
            //Insertamos los datos en el template
            $items_lista_productos.querySelector('.name_producto').textContent = `${elemento['nombre_producto']}  ${elemento['contenido_neto']}${elemento.nombre_presentacion}  (${elemento['tipo']})`;
            $items_lista_productos.querySelector('.stock').textContent = elemento['cantidad'];
            $items_lista_productos.querySelector('.price').textContent = `$${elemento['precio']}`;
            $items_lista_productos.querySelector('.monto_producto').textContent = `$${Number(elemento['cantidad']) * Number(elemento['precio'])}`;
            
            $items_lista_productos.querySelector('.btn-danger').dataset.id = elemento['cod_producto'];
            //guardamos una copia de la estrutura actual del template en la variable $node
            let $clone = $items_lista_productos.cloneNode(true);
            //Guardamos el nodo en el fragment
            $fragment_list.append($clone);
        });
        
        //Limpiamos la lista
        $lista_compra_productos.innerHTML = "";
        //Insertamos el fragment en la listas
        $lista_compra_productos.append($fragment_list);
    }else{
        $lista_compra_productos.innerHTML = "";
    }
}

//Agregamos el producto al array de productos seleccionados
const addProduct_in_array_product = (id_producto, cantidad, precio) => {
    
    //Obtenemos datos del producto solicitado
    let datos_producto = all_data_productos.filter( element => element['id_producto'] == id_producto);

    //extraemos el nombre y codigo del producto
    let nombre_producto = datos_producto[0]['nombre_producto'];
    let cod_producto = datos_producto[0]['codigo_producto'];
    let contenido_neto = datos_producto[0]['contenido_neto'];
    let tipo = datos_producto[0]['grabado_excento'] == 1 ? 'G' : 'E';
    let nombre_presentacion = datos_producto[0]['nombre_presentacion'];

    //Creamos un objeto con todos los datos ingresados
    let producto_seleccionado = {
        id_producto,
        cod_producto,
        nombre_producto,
        cantidad,
        precio,
        contenido_neto,
        tipo,
        nombre_presentacion
    }

    //Agregamos los datos al array de productos seleccionados
    productos_seleccionados.push(producto_seleccionado);
    d.querySelector('#codigo_producto').value = "";
    d.querySelector('#codigo_producto').parentElement.classList.remove("is-dirty");
    d.querySelector('#cantidad').value = "";
    d.querySelector('#cantidad').parentElement.classList.remove("is-dirty");
    d.querySelector('#precio').value = "";
    d.querySelector('#precio').parentElement.classList.remove("is-dirty");
    d.querySelector('#codigo_producto').focus();

}

//Agregamos el producto a la lista de productos seleccionados
const addList = async() => {

    //Extraemos datos del formulario
    let codigo_producto =d.querySelector('#codigo_producto');
    let cantidad = d.querySelector('#cantidad');
    let precio =  d.querySelector('#precio');

    let arrayInputs = [codigo_producto,precio,cantidad];

    //validamos datos del formulario
    if(!validarCamposVacios(arrayInputs) || !validarNumeros(codigo_producto) || !validarNumeros(cantidad) || !validarNumeros(precio)){
        return;
    }

    //Buscamos el producto solicitado por medio del codigo del producto
    let producto_solicitado = buscarProducto(codigo_producto.value);

    //Verificamos si el producto fue encontrado
    if(producto_solicitado === null){
        // errorBuscarProducto = true;
        alerta({
            titulo: "Código de Producto No Se Encuentra Registrado",
            tipo_mensaje: "error",
        });
        codigo_producto.parentElement.classList.add("is-invalid");
        codigo_producto.focus();
        return;
    }

    //Verificamos si el array de productos seleccionados esta vacio
    if(productos_seleccionados.length === 0){
        //Si esta vacio, entonces agregamos el producto
        addProduct_in_array_product(producto_solicitado.id_producto,cantidad.value,precio.value);
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
            alerta({
                titulo: 'Producto ya se encuentra seleccionado',
                tipo_mensaje: "warning"
            });
            return;
        }else{
            addProduct_in_array_product(producto_solicitado.id_producto,cantidad.value,precio.value);
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

//Buscamos producto solicitado
const buscarProducto = (codigo_producto) => {

    if(codigo_producto === ""){
        return;
    }

    let producto_solicitado = null;
    //Recorremos el array de productos y devolvemos del producto solicitado
    for (let i = 0; i < all_data_productos.length; i++) {
        if(codigo_producto === all_data_productos[i].codigo_producto){
            producto_solicitado = all_data_productos[i];
            break;
        }
    }

    return producto_solicitado;
}

//Guardamos datos de la compra (Tabla compra_productos)
const save_compra_productos = async(datos,url) => {

    const body = {
        method : 'POST',
        body : JSON.stringify(datos) 
    }

    try {
        const res = await fetch(`${url}?recibir_datos_compra_producto_modificar=1`,body);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        console.log(data)
        if(data["success"] == 1){
            alerta({
                titulo: "Modificación de Compra Completado",
                tipo_mensaje: "success",
                callback : ()=>{
                  window.location = "lista_compra_disponible";  
                },
                bool: true
            });
        }else{
            //En caso de fallar la validacion en el backend se envia un alerta
            alerta({
                titulo: "Error",
                mensaje : data["success"][0],
                tipo_mensaje: "error",
                bool: true,
                callback: () => {
                    d.querySelector(`#${data["success"][3]}`) !== "" ?d.querySelector(`#${data["success"][3]}`).focus():"";
                }
            });
            if(data['success'][1] !== ""){
                let tr = d.querySelectorAll('.id');
                tr[data['success'][1]].focus();

                productos_seleccionados.splice(data['success'][1],1);
                show_lista_productos(productos_seleccionados);
                console.log(data['success'][2]);
                d.querySelector('#codigo_producto').value = data['success'][2].cod_producto;
                d.querySelector('#codigo_producto').parentElement.classList.add("is-dirty");

                d.querySelector('#cantidad').value = data['success'][2].cantidad;
                d.querySelector('#cantidad').parentElement.classList.add("is-dirty");

                d.querySelector('#precio').value = data['success'][2].precio;
                d.querySelector('#precio').parentElement.classList.add("is-dirty");
                d.querySelector(`#${data["success"][3]}`).focus();
            }
        }
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

//Borrado de datos de la Tabla Compra Productos
const borrar_datos_compra = async(id_compra,url) => {

    try {
        const res = await fetch(`${url}?borrar_datos_tabla_compra_producto=${id_compra}`);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        if(data["success"] == 1){
            return true;
        }
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

$btn_modificar_compra.addEventListener('click', async e => {

    //Verficamos si han agregado productos a la lista
    if(productos_seleccionados.length === 0){
        ev.preventDefault();
        ev.stopPropagation();
        alerta({
            titulo : "Debe ingresar productos a la lista",
            tipo_mensaje : "warning"
        });
        return;
    }

    //Borramos los productos relacionados de la tabla compra-productos
    let borrado = await  borrar_datos_compra(datos_compra[0]['id_compra'],url_compra);

    await save_compra_productos(
        {
            id_compra: datos_compra[0]['id_compra'],
            productos_seleccionados
        },url_compra
    );
});

d.addEventListener('click', async ev => {
    
    //Agregamos productos a la lista
    if(ev.target === $btn_add_list){
        addList();
    }

    //Eliminamos producos de la lista
    if(ev.target.classList.contains('zmdi-more')){
        quitar_producto_lista(ev.target.dataset.id);
    }

  
});

d.addEventListener('DOMContentLoaded', async e => {
    
    //Recuperamos todos los datos de la compra
    let id_compra = await extraer_id_compra(get_sessiones);
    datos_compra = await extraer_datos_compra(id_compra,url_compra);
    let data_compra_productos = await extraer_datos_compra_productos(id_compra,url_compra);
    //Recuperamos los datos de todos los productos
    await extraer_datos_productos(url_productos);

    //Mostramos el número de factura
    show_numero_factura(datos_compra[0]["numero_factura"]);
    
    //Verificamos si la compra tiene productos
    if(data_compra_productos !== 0){
        
        //Recuperamos los id de los productos relacionados a la compra
        let id_productos = data_compra_productos.map( e => e.productos);
        //console.log(id_productos)
        
        //Recuperamos los datos de los productos relacionados a la compra por medio de los id recuperados anteriormente
        let datos_productos = [];

        all_data_productos.forEach((element) => {
            id_productos.forEach((e) => {
                if(element.id_producto === e){
                    datos_productos.push( element);
                }
            })
        });

        console.log(datos_productos)

        //Insertamos los datos de los productos relacionados a la compra, en la lista de productos seleccionados
        datos_productos.forEach((element,index) => {

            let producto_seleccionado = {
                id_producto : element.id_producto,
                cod_producto : element.codigo_producto,
                nombre_producto : element.nombre_producto,
                cantidad : data_compra_productos[index]["cantidad_productos"],
                precio : data_compra_productos[index]["precio"],
                tipo : element.grabado_excento == 1 ? 'G' : 'E',
                contenido_neto : element.contenido_neto,
                nombre_presentacion : element.nombre_presentacion
            }

            productos_seleccionados.push(producto_seleccionado);

        });

        //Mostramos los productos seleccionados
        show_lista_productos(productos_seleccionados)
    }    
});
