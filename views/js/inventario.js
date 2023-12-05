import { url } from "./urls.js";
import { alerta } from "./utils.js";

const {url_productos, url_compra, url_inventario, url_categorias} = url;

const d = document,
$searchProduct = d.querySelector('#searchProduct'),
$btn_actualizar_inventario = d.querySelector('#btn_actualizar_inventario'),
$lista_productos_inventario = d.querySelector('#lista_productos_inventario'),
$categoria_productos = d.querySelector('#categorias'),
$fragment_list = d.createDocumentFragment() ;

let datos_productos;

//MOSTRAR LOS DATOS EN PANTALLA
const show_lista_productos = (lista_productos_seleccionados) => {

    //enlazamos el template creado en el HTML
    const $items_lista_productos = d.querySelector('#template_items_productos_inventario').content;

    if(lista_productos_seleccionados.length > 0){

        lista_productos_seleccionados.forEach(elemento => {
            //Insertamos los datos en el template
            
            $items_lista_productos.querySelector('.name_product').textContent = `${elemento.nombre_producto} ${elemento.contenido_neto}${elemento.nombre_presentacion} (${elemento.grabado_excento == 1 ? 'G' : 'E'})`;

            $items_lista_productos.querySelector('.stock').textContent = elemento.cantidad_disponible;

            $items_lista_productos.querySelector('.price').textContent = `$${elemento.precio}`;

             //guardamos una copia de la estrutura actual del template en la variable $node
            let $clone = $items_lista_productos.cloneNode(true);
            //Guardamos el nodo en el fragment
            $fragment_list.append($clone);
        })

        //Limpiamos la lista
        $lista_productos_inventario.innerHTML ="";
        //Insertamos el fragment en la lista
        $lista_productos_inventario.append($fragment_list);

    }else{
        //Limpiamos la lista
        $lista_productos_inventario.innerHTML ="";
    }
}

//Extraemos los datos de todos los productos registrados
const extraer_datos_productos = async (url) => {

    try {
        const res =  await fetch(`${url}?consultar_todos_productos=1`);
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
            tipo_mensaje: "error"
        });
    }
}

//Extraemos los datos de las compras con estatus disponible
const extraer_datos_compras_disponibles = async(url) => {

    try {
        const res = await fetch(`${url}?obtener_compras_con_estatus_disponibles=1`);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data =  await res.json();
        return data["success"];
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

//FUNCIONES PARA ACTUALIZAR Y GUARDAR LAS CANTIDADES DISPONIBLES DE LOS PRODUCTOS
const actualizar_estado_compra = async(id_compras,url) => {

    const body = {
        method : 'POST',
        body : JSON.stringify(id_compras)
    };

    try {
        const res = await fetch(`${url}?actualizar_estado_compra=1`,body);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        console.log(data);
    }catch(error){
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

const saveCantidadesDisponibles = async(datos_productos,url) =>{
    
    const body = {
        method : 'POST',
        body : JSON.stringify(datos_productos)
    };

    try {
        const res = await fetch(`${url}?modificar_cantidad_disponible_producto=1`,body);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
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

const obtener_cantidad_productos = async(id_compras,url) => {

    const body = {
        method : 'POST',
        body : JSON.stringify(id_compras)
    };

    try {
        const res = await fetch(`${url}?obtener_cantidades_productos=1`,body);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
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

const filtar_productos = (array_productos) => {

    //filtramos las compras que tienen registros en la tabla compra_productos
    let array_compras_con_productos = array_productos.filter(element => Object.getPrototypeOf(element[0]) === Array.prototype);

    //Obtenemos los id de los productos que contiene cada compra, sin repeticion
    let array_id_productos_sin_repetir = new Set();
    
    array_compras_con_productos.forEach(element => {
        element[0].forEach(element2 => {
            array_id_productos_sin_repetir.add(element2.productos);
        });   
    });

    //Convertimos a array
    array_id_productos_sin_repetir = Array.from(array_id_productos_sin_repetir);

    return [array_id_productos_sin_repetir, array_compras_con_productos];

}

const sumatoria_cantidades_productos = (array_id_productos_sin_repetir, array_compras_con_productos) => {

    //Creamos un array para guardar los datos los productos con su cantidad
    let productos_cantidad = [];

    array_compras_con_productos.forEach(element => {
        element[0].forEach(element2 => {
            productos_cantidad.push(element2);
        });
    });

    let array = [];//guardaremos objetos que tendran como propiedad el id del producto y la cantidad total que se agregara al inventario

    array_id_productos_sin_repetir.forEach( id =>{
        let contador_cantidad = null;
        productos_cantidad.forEach(element => {
            if(id == element.productos){
                contador_cantidad += Number(element.cantidad_productos);
            }
        });

        let obj = {
            id_producto : id,
            cantidad_total : contador_cantidad
        }
        array.push(obj);
    });

    return array;
}

const actualizar_cantidad_disponible = (cantidades_productos) => {

    //recuperamos los productos a los cuales se les va a actualizar la cantidad disponible
    let productos = [];
    cantidades_productos.forEach(e => {
        productos.push(...datos_productos.filter(e2 => e2.id_producto === e.id_producto));
    });

    //Actualizamos las cantidades disponibles de los productos
    cantidades_productos.forEach(e => {
        productos.forEach(e2 =>{

            if(e.id_producto === e2.id_producto){
                if(e2.cantidad_disponible === null){
                    e2.cantidad_disponible = Number(e.cantidad_total);
                }else{
                    e2.cantidad_disponible = Number(e2.cantidad_disponible)  + Number(e.cantidad_total);
                }
            }
        });
    });

    return productos;
}

//Extraemos los datos de un producto
const buscar_producto = async (nombre_producto, categoria ,url) => {

    let endpoint ="";
    let value = null;

    if(nombre_producto !== "" && categoria === ""){
        endpoint = 'consultar_producto_por_nombre';
        value = nombre_producto;
    }else if(nombre_producto === "" && categoria !== ""){
        endpoint = 'consultar_producto_por_categoria';
        value = categoria;
    }else if(nombre_producto !== "" && categoria !== ""){
        endpoint = 'consultar_producto_por_nombre_and_categoria';
        value = [nombre_producto,categoria];
    }else{
        endpoint = 'consultar_producto_por_nombre';
        value = ""
    }

    try {
        const res =  await fetch(`${url}?${endpoint}=${value}`);
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

//Extraer data de la base de datos
const extraer_categorias = async(url,) => {
    try {
        const res = await fetch(`${url}?extraer_categorias=1`);
        const data = await res.json();
        return data.success
    } catch (error) {
        console.log(error);
    }
}

//Mostrar valores en los selects
const asignar_valores_select = (param) => {

    let {data,titulo,input,nombre_opciones} = param;
    let {id,nombre} = nombre_opciones;

    for(let i = 0; i < data.length+1; i++){
        if(i < 1){
            //Insertamos el titulo, que indicara al usuario que debe seleccionar alguna opcion
            input.options[i] = new Option(titulo,"");
        }else{
            //Insertamos las opciones
            input.options[i] = new Option(
                `${data[i-1][nombre]}`,
                `${data[i-1][id]}`,
            );
        }
    }
}

$searchProduct.addEventListener('input', async e=> {

    // Obtén el valor actual del campo de texto
    let searchText = $searchProduct.value;
 
    // Llama a tu función de búsqueda en la base de datos
    let resConsulta = await buscar_producto(searchText,$categoria_productos.value,url_productos);
    show_lista_productos(resConsulta);
})

$btn_actualizar_inventario.addEventListener('click', async e=> {
    
    //Obtenemos los datos de las compras con estatus disponible
    let datos_compras_disponible = await extraer_datos_compras_disponibles(url_compra);

    //Verificamos si la consulta viene vacia o trajo datos
    if(datos_compras_disponible.length === 0){
        alerta({
            titulo : "No hay actualizaciones disponibles",
            tipo_mensaje : "warning"
        });
        return;
    }

    //Extraemos los id's de cada una de las compras disponibles
    let id_compras = datos_compras_disponible.map(compra => Number(compra.id_compra));

    //Obtenemos la cantidad de cada producto relacionado a cada compra
    let cantidades = await obtener_cantidad_productos({id_compras},url_inventario);
    
    //Creamos un nuevo array y guardamos los productos contenidos en cada compra pero sin repeticion
    let [array_id_productos_sin_repetir, array_compras_con_productos] = filtar_productos(cantidades);

    /*
        el array "array_id_productos_sin_repetir" tiene los id de los productos a los cuales se les
        tiene que modificar la cantidad disponible
        
        el array "array_compras_con_productos" tiene todas aquellas compras disponibles que tienen registros en la tabla compra productos

    */
    
    // Obtenemos cual es la cantidad total que se agregara al inventario de cada uno de los productos que se encuentran en las compras

    let cantidades_productos_por_cada_compra = sumatoria_cantidades_productos(array_id_productos_sin_repetir, array_compras_con_productos);
    
    //actualizamos la cantidad disponible de los productos 
    let cantidades_actualizadas = actualizar_cantidad_disponible(cantidades_productos_por_cada_compra);

    //Guardamos las nuevas cantidades disponibles

    //Obtenemos los id de cada una de las compras
    let ids_compras = array_compras_con_productos.map(element => element[1]);

    await Promise.all([
        saveCantidadesDisponibles({datos_productos : cantidades_actualizadas},url_productos),
        actualizar_estado_compra({ids_compras},url_inventario),
    ]).then( values => alerta({
        titulo: "Actualización Exitosa",
        tipo_mensaje : "success",
        callback : () => {
            window.location = 'inventario'
        }, bool : true
    }));
});

$categoria_productos.addEventListener('change', async e=> {

    const categoria = $categoria_productos.value;

    // Llama a tu función de búsqueda en la base de datos
    let resConsulta = await buscar_producto($searchProduct.value,categoria,url_productos);

    show_lista_productos(resConsulta);
});

d.addEventListener('DOMContentLoaded', async e => {
    datos_productos = await extraer_datos_productos(url_productos);
    const data_categorias = await extraer_categorias(url_categorias);

    //Mostramos los datos de las categorias
    asignar_valores_select(
        { 
            data: data_categorias,
            titulo: "Seleccionar Categoria",
            input: $categoria_productos,
            nombre_opciones : {
                id : "id_categoria",
                nombre: "nombre_categoria"  
            }
        }
    );
    show_lista_productos(datos_productos);
});