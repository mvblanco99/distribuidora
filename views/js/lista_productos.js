import { url } from "./urls.js";
import {alerta} from "./utils.js";
import { validarExpresion, validarNumeros } from "./validaciones.js";
import { comprobar_permiso_accion } from "./controlador_acciones.js";

const {url_productos, url_sesiones} = url;

const d = document,
$lista_productos = d.querySelector('#lista_productos'),
$searchProduct = d.querySelector('#searchProduct'),
$fragment_productos = d.createDocumentFragment();

//Mostrar los datos de los productos
const showAllproductos = (data) => {

    //enlazamos el template creado en el HTML
    const $template_productos = d.querySelector('#items_lista_productos').content;
        
    if(data.length > 0){

        data.forEach(element => {
            //Insertamos los datos en el template
            $template_productos.querySelector('.nombre_producto').textContent = `${element['nombre_producto']}`;
            $template_productos.querySelector('.precio').textContent = `$${element['precio']}`;
            $template_productos.querySelector('.contenido_neto').textContent = `${element['contenido_neto']}${element.nombre_presentacion}`;

            $template_productos.querySelector('.btn-info').dataset.id = element['id_producto'];
            $template_productos.querySelector('.btn-danger').dataset.id = element['id_producto']; 

            //guardamos una copia de la estrutura actual del template en la variable $node
            let $node = d.importNode($template_productos,true);
            //Guardamos el nodo en el fragment
            $fragment_productos.append($node);
        });

        //Limpiamos la lista
        $lista_productos.innerHTML = "";
         //Insertamos el fragment en la lista
        $lista_productos.append($fragment_productos);
    }
}

//Extraer datos de los productos
const getAllDataProductos = async (url) => {

    try {
        const res =  await fetch(`${url}?consultar_todos_productos=1`);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        //Verificamos si la consulta trae productos para mostrar
        if(data['success'] == 0){
            alerta({
                titulo : '¡No hay Productos!',
                mensaje : "No se han registrado productos",
                tipo_mensaje : "warning"
            })
        }else{
            console.log(data.success)
            showAllproductos(data['success']);
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

 /* Utilizamos el id del producto para generar una variable de session en php
    y persistir los datos para luego usarlos en la ventana de modificar productos */
const generarVariableSesion = async(id, url) => {
    try {
        const res = await fetch(`${url}?sesion_producto=${id}`);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await(res.json());
        if(data['data'] != ""){
            //Redirigimos a la ventana de modificar productos;
            redirigir_A_modificar_productos();  
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

const redirigir_A_modificar_productos = () => {
    window.location = 'update_productos';
}

//Extraemos los datos de un producto
const buscar_producto = async (nombre_producto, url) => {

    try {
        const res =  await fetch(`${url}?consultar_producto_por_nombre=${nombre_producto}`);
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

//Eliminar Producto
const eliminar_producto = async(id_producto, url) => {
    
    try {
        const res = await fetch(`${url}?eliminar_producto=${id_producto}`);
        const data = await res.json();
        if(data.success == 1){
            alerta({
                titulo : 'Eliminación del Producto Exitosa',
                tipo_mensaje : 'success',
                bool : true,
                callback : () =>{
                    window.location = 'lista_productos';
                }
            });
        }else if(data.success == 2){
            alerta({
                titulo : 'No se puede eliminar el producto',
                mensaje : 'El producto es usado en varios registros',
                tipo_mensaje : 'warning',
            });
        }else if(data.success == 0){
            alerta({
                titulo : 'Ha ocurrido un error durante la eliminación',
                tipo_mensaje : 'error',
            });
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

$searchProduct.addEventListener('input', async e=> {

     // Obtén el valor actual del campo de texto
     let searchText = $searchProduct.value;
  
     // Llama a tu función de búsqueda en la base de datos
     let resConsulta = await buscar_producto(searchText,url_productos);
     console.log(resConsulta);

    showAllproductos(resConsulta)
})

d.addEventListener('click', async e => {

    if(e.target.classList.contains('btn-info')){
        //Verificamos si el administrador tiene los privilegios para eliminar
        let permiso_accion = await comprobar_permiso_accion('modificar');
        if(permiso_accion){
            generarVariableSesion(e.target.dataset.id,url_sesiones);
        }else{
            alert('No tiene los privilegios para realizar esta acción');
        }
    }

    if(e.target.classList.contains('btn-danger')){

        const id_producto = e.target.dataset.id;

        alerta({
            titulo : '¿Esta seguro de eliminar el producto?',
            tipo_mensaje : 'warning',
            bool: true,
            callback: () => {
                eliminar_producto(id_producto,url_productos);
            }
        })

    }
});

d.addEventListener('DOMContentLoaded', async e => {
    await getAllDataProductos(url_productos);
});