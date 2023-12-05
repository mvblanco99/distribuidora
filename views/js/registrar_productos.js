import { url } from "./urls.js";
import { alerta } from "./utils.js";
import { validarNumeros, validarCamposVacios, validarExpresion, validarImagen, validarCantidadConDecimales } from "./validaciones.js";

const {url_productos, url_categorias, url_presentaciones} = url;

const d = document,
$categoria = d.querySelector('#categoria'),
$presentacion = d.querySelector('#presentacion'),
$btn_registrar_producto = d.querySelector('#btn_registrar_producto'); 

//Extraer data de la base de datos
const extraer_data_api = async(url,endPoint) => {
    try {
        const res = await fetch(`${url}?${endPoint}`);
        const data = await res.json();
        return data.success;
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

//////Guardar datos del producto /////////
const save_producto = async (data_productos, url) => {

    console.log(data_productos);

    const body = {
        method : 'POST',
        body : data_productos,
    }

    try {
        const res = await fetch(`${url}?registrar_producto=1`,body);
        if(!res.ok) throw {status: res.status, statusText: res.statusText};
        const data = await res.json();
        if(data['success'] == 1){
            alerta({
                titulo: "Registro Exitoso",
                mensaje : "El Producto se ha registrado Satisfactoriamente",
                tipo_mensaje: "success",
                callback : ()=>{
                    window.location = "lista_productos";  
                },
                bool: true
            });
        }else{
             //En caso de fallar la validacion en el backend se envia un alerta
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

//Comprobamos si el codigo de producto ya se encuentra registrado
const isCodigoProductoRegistrado = async (codigo_producto, url) => {

    try {
        const res = await fetch(`${url}?validar_producto=${codigo_producto}`);
        if(!res.ok) throw {status: res.status, statusText:res.statusText};
        const data = await(res.json());
        if(data['success'] == 1){
            return data;
        }else{
            //En caso de fallar la validacion en el backend se envia un alerta
            if(typeof data['success'][0] === "string"){
                alerta({
                    titulo: "Error",
                    mensaje : data["success"][0],
                    tipo_mensaje: "error",
                });
                data['success'][1] !== '' ? d.querySelector(`#${data['success'][1]}`).parentElement.classList.add('is-invalid') : "";
                return data['success'] = 2;
            }
            return data['success'] = 0;
        }     
    } catch (error) {
        let titulo = error.status || "Error";
        let mensaje = error.statusText || "Ocurrio un error, Contacte al Administrador";
        alerta({
            titulo,
            mensaje,
            tipo_mensaje: "error"
        });
    }
}

//Registramos el producto
$btn_registrar_producto.addEventListener('click', async e => {

    e.preventDefault();
    e.stopPropagation();

    //Extraemos los datos del formulario
    const codigo_producto = d.querySelector('#BarCode');
    const nombre_producto = d.querySelector('#NameProduct');
    const contenido_neto = d.querySelector('#contenidoNeto');
    const tipo = d.querySelector('#tipo');
    const precio = d.querySelector('#precio');

    let arrayInputs = [codigo_producto,nombre_producto,contenido_neto,$presentacion,$categoria,tipo,precio];

     //Validamos datos del formulario
    if(!validarCamposVacios(arrayInputs) || !validarNumeros(codigo_producto) || !validarExpresion(nombre_producto) || !validarCantidadConDecimales(contenido_neto) || !validarNumeros($presentacion) ||
    !validarNumeros($categoria) || !validarNumeros(tipo) || !validarCantidadConDecimales(precio)){
        return;
    }

    //Comprobamos si el codigo de producto ingresado ya se encuentra registrado
    let is_codigo_producto_registrado = await isCodigoProductoRegistrado(codigo_producto.value,url_productos);

    if(is_codigo_producto_registrado['success'] == 1 ){
        alerta({
            titulo : 'Codigo de Producto ya se encuentra registrado',
            tipo_mensaje : "error"
        });
        d.querySelector('#label_codigo_producto').classList.add('is-invalid');
        return;
    }else if(is_codigo_producto_registrado['success'] == 2){
        return;
    }

    const formData = new FormData();
    formData.append('BarCode',codigo_producto.value);
    formData.append('NameProduct',nombre_producto.value);
    formData.append('contenidoNeto',contenido_neto.value);
    formData.append('presentacion',$presentacion.value);
    formData.append('tipo',tipo.value);
    formData.append('categoria', $categoria.value);
    formData.append('precio', precio.value);
    
    save_producto(formData,url_productos);

});

d.addEventListener('DOMContentLoaded', async e => {

    //Extraemos los datos de las categorias
   const dataCategoria = await extraer_data_api(url_categorias,'extraer_categorias=1');

    //Extraemos los datos de la presentaciones
    const dataPresentaciones = await extraer_data_api(url_presentaciones,'extraer_presentaciones_productos=1');


    console.log(dataCategoria);
    console.log(dataPresentaciones);
    
    //Mostramos los datos de las categorias
    asignar_valores_select(
        { 
            data: dataCategoria,
            titulo: "Seleccionar Categoria",
            input: $categoria,
            nombre_opciones : {
                id : "id_categoria",
                nombre: "nombre_categoria"  
            }
        }
    );

    //Mostramos los datos de las presentaciones
    asignar_valores_select(
        { 
            data: dataPresentaciones,
            titulo: "Seleccionar Presentación",
            input: $presentacion,
            nombre_opciones : {
                id : "id_presentacion",
                nombre: "nombre_presentacion"  
            }
        }
    );
});