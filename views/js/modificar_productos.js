import {url} from "./urls.js";
import { alerta } from "./utils.js";
import { validarNumeros, validarCamposVacios, validarExpresion, validarImagen, validarCantidadConDecimales } from "./validaciones.js";

const {get_sessiones,url_productos, url_modificar_productos,url_categorias,url_presentaciones} = url;

const d = document,
$btn_modificar_producto = d.querySelector('#btn_modificar_producto');

//Extraer data de la base de datos
const extraer_data_api = async(url,endPoint) => {
    try {
        const res = await fetch(`${url}?${endPoint}`);
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

//Mostramos los datos del producto
const showDataProductos = (data_producto) => {

    if(data_producto.length > 0) {
        
        data_producto.forEach(element => {
        
            d.querySelector('#updateBarCode').value = element['codigo_producto'];
            d.querySelector('#label_codigo_producto').classList.add('is-dirty');
            
            d.querySelector('#updateNameProduct').value = element['nombre_producto']; 
            d.querySelector('#label_nombre_producto').classList.add('is-dirty');
    
            d.querySelector('#update_contenidoNeto').value = element['contenido_neto']; 
            d.querySelector('#label_update_contenidoNeto').classList.add('is-dirty');
    
            d.querySelector('#update_precio').value = element['precio']; 
            d.querySelector('#label_update_precio').classList.add('is-dirty');
    
            d.querySelector('#update_tipo').value = element['grabado_excento'];
            d.querySelector('#update_categoria').value = element.categoria_productos;
            d.querySelector('#update_presentacion').value = element.presentacion;
        });
    }
}

//Recuperamos los datos del producto utilizando el id recuperado
const getDataProductos =  async(id_producto,url) => {
    
    try {
        const res = await fetch(`${url}?consultar_producto=${id_producto}`);
        if(!res.ok) throw {status:res.status, statusText:res.statusText};
        const data = await(res.json());
        if(typeof data['success'][0] === "string"){
            alerta({
                titulo: "Error",
                mensaje : data["success"][0],
                tipo_mensaje: "error",
            });
        }else{
            return data.success;
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

//Recuperamos el id del producto por medio de la variable de session creada en php
const extraerIdSession = async() => {
    try {
        const res = await fetch(`${get_sessiones}?extraer_id_producto_session=1`);
        if(!res.ok) throw {status:res.status, statusText:res.statusText};
        const data = await(res.json());
        return data;
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

//////Guardar datos del producto ///////////
const save_producto = async (data_productos, url) => {

    const body = {
        method : 'POST',
        body : data_productos,
    }

    try {
        const res = await fetch(`${url}?modificar_producto=1`,body);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data =  await res.json();
        if(data['success'] == 1){
            alerta({
                titulo : "Modificación Exitosa",
                mensaje : "El Producto se ha Modificado Satisfactoriamente",
                tipo_mensaje : "success",
                callback : () => {
                    window.location = "lista_productos";
                },
                bool :true
            })
        }else{
            alerta({
                titulo: "Error",
                mensaje : data["success"][0],
                tipo_mensaje: "error",
            });
            data['success'][1] !== '' ? d.querySelector(`#${data['success'][1]}`).parentElement.classList.add('is-invalid') : "";
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

$btn_modificar_producto.addEventListener('click', async e => {

    e.preventDefault();
    e.stopPropagation();

    //Extraemos los datos del formulario
    const codigo_producto = d.querySelector('#updateBarCode');
    const nombre_producto = d.querySelector('#updateNameProduct');
    const contenido_neto = d.querySelector('#update_contenidoNeto');
    const presentacion =  d.querySelector('#update_presentacion');
    const precio = d.querySelector('#update_precio');
    const tipo = d.querySelector('#update_tipo');
    const categoria = d.querySelector('#update_categoria');
   
    let arrayInputs = [codigo_producto,nombre_producto,contenido_neto,presentacion,tipo,precio,categoria];

     //Validamos datos del formulario
    if(!validarCamposVacios(arrayInputs) || !validarNumeros(codigo_producto) || !validarExpresion(nombre_producto) || !validarCantidadConDecimales(contenido_neto) || !validarNumeros(presentacion) ||
    !validarNumeros(tipo), !validarCantidadConDecimales(precio),!validarNumeros(categoria)){
        return;
    }

    const formData = new FormData();
    formData.append('updateBarCode',codigo_producto.value);
    formData.append('updateNameProduct',nombre_producto.value);
    formData.append('update_contenidoNeto',contenido_neto.value);
    formData.append('update_presentacion',presentacion.value);
    formData.append('update_tipo',tipo.value);
    formData.append('update_precio', precio.value);
    formData.append('update_categoria', categoria.value);
    
    await save_producto(formData,url_productos);
});

d.addEventListener('DOMContentLoaded', async e => {

    //Buscamos el id del producto
    const id_producto = await extraerIdSession();
    //Buscamos los datos del producto
    const data_producto = await getDataProductos(id_producto,url_productos)
    //Buscamos los datos de las categorias de productos
    const dataCategoria = await extraer_data_api(url_categorias,'extraer_categorias=1');
    //Buscamos los datos de las presentaciones de productos
    const dataPresentacion =  await extraer_data_api(url_presentaciones,'extraer_presentaciones_productos=1');

    //Mostramos los datos de las categorias
    asignar_valores_select(
        { 
            data: dataCategoria,
            titulo: "Seleccionar Categoria",
            input: d.querySelector('#update_categoria'),
            nombre_opciones : {
                id : "id_categoria",
                nombre: "nombre_categoria"  
            }
        }
    );

    //Mostramos los datos de las presentaciones
    asignar_valores_select(
        { 
            data: dataPresentacion,
            titulo: "Seleccionar Presentación",
            input: d.querySelector('#update_presentacion'),
            nombre_opciones : {
                id : "id_presentacion",
                nombre: "nombre_presentacion"  
            }
        }
    );

    //Mostramos los datos del producto
    showDataProductos(data_producto);
})