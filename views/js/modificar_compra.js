import { url } from "./urls.js";
import { alerta } from "./utils.js";
import { validarCamposVacios, validar_numero_control,validar_numero_factura, validarExpresion, validarNumeros, validarCantidadConDecimales } from "./validaciones.js";

const {get_sessiones, url_compra} = url;

const d = document,
$btn_modificar_compra = d.querySelector('#btn_modificar_compra');
let datos_compra;

//Variables utilizadas para dar formato al numero de control
const mask_numero_control = '##-########';//Patron de numero de control
let array_numero_control = [];

//Mostramos los datos de la compra
const show_data_compra = (data_compra) => {
    if(data_compra.length > 0) {
        
        data_compra.forEach(element => {
        
            d.querySelector('#modificar_numero_factura').value = element['numero_factura'];
            d.querySelector('#modificar_numero_factura').parentElement.classList.add('is-dirty');
            
            d.querySelector('#modificar_numero_control').value = element['numero_control']; 
            d.querySelector('#modificar_numero_control').parentElement.classList.add('is-dirty');
    
            d.querySelector('#modificar_nombre_proveedor').value = element['nombre_proveedor']; 
            d.querySelector('#modificar_nombre_proveedor').parentElement.classList.add('is-dirty');
    
            d.querySelector('#modificar_precio_compra').value = element['precio_total_compra']; 
            d.querySelector('#modificar_precio_compra').parentElement.classList.add('is-dirty');
            
            d.querySelector('#modificar_fecha_compra').value = element['fecha_entrada_compra']; 
            d.querySelector('#modificar_fecha_compra').parentElement.classList.add('is-dirty');
        });
    }
}

//Recuperamos los datos de la compra utilizando el id recuperado
const extraer_datos_compra =  async(id_compra) => {
    
    try {
        const res = await fetch(`${url_compra}?obtener_datos_compra_especifica=${id_compra}`);
        if(!res.ok) throw {status:res.status, statusText:res.statusText};
        const data = await res.json();
        if(typeof data['success'][0] === "string"){
            alerta({
                titulo: "Error",
                mensaje : data["success"][0],
                tipo_mensaje: "error",
            });
        }else{
            datos_compra = data['success'];
            show_data_compra(data['success']);
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
        extraer_datos_compra(data);
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

//Metodo para Insertar un formato especial al numero de control 
const formato_numero_control = (mask, key, arr) => {

    let numbers = ["0","1","2","3","4","5","6","7","8","9"];

    if(key === 'Backspace' && arr.length > 0){
        arr.pop();
        return;
    }

    if(numbers.includes(key) && arr.length + 1 <= mask.length){
        if(mask[arr.length] === '-' || mask[arr.length] === '/'){
            arr.push(mask[arr.length],key);
        }else{
            arr.push(key);
        }
    }
}

//En el caso de que el usuario quiera modificar el numero de factura original, se verifica el nuevo numero de factura ingresado, con el fin de comprobar si este se encuentra registrado en otra compra.
const isNumeroFacturaRegistrado = async (numero_factura,url) => {

    //Comprobamos si el numero de factura ingresado es diferente al numero de factura original
    if(datos_compra[0]["numero_factura"] !== d.querySelector('#modificar_numero_factura').value){
        
        //realizamos verificacion
        try {
            const res = await fetch(`${url}?validar_numero_factura_repetida=${numero_factura}&id_compra=${datos_compra[0]["id_compra"]}`);
            if(!res.ok) throw {status: res.status, statusText:res.statusText};
            const data = await(res.json());
            if(data['success'] == 1){
                return data["success"];
            }else{
                //En caso de fallar la validacion en el backend se envia un alerta
                if(typeof data['success'][0] === "string"){
                    alerta({
                        titulo: "Error",
                        mensaje : data["success"][0],
                        tipo_mensaje: "error",
                    });
                    data['success'][1] !== '' ? d.querySelector(`#${data['success'][1]}`).parentElement.classList.add('is-invalid') : "";
                    return 2;
                }
                //En este caso, se puede registrar el numero de factura
                return 0;
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
    }else{
        return 0;
    }
}

//Guardamos datos de la compra (Tabla Compra)
const saveCompra = async (datos, url) => { 

    const body = {
        method : 'POST',
        body : JSON.stringify(datos) 
    }

    try {
        const res = await fetch(`${url}?modificar_datos_compra=1`,body);
        if(!res.ok) throw {status : res.statusText, statusText: res.statusText};
        const data = await res.json();
        console.log(data);
        if(data['success'] == 1){
            alerta({
                titulo: "Paso 1 Completado",
                tipo_mensaje: "success",
                callback : ()=>{
                  window.location = "modificar_compra_productos";  
                },
                bool: true
            });
        }else if(data['success'] == 0){
            alerta({
                titulo: "Error",
                mensaje: "Ocurrio un error durante el registro",
                tipo_mensaje: "error",
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

d.querySelector('#modificar_numero_control').addEventListener('keydown', e=>{
    if(e.key === 'Tab'){
        return;
    }

    e.preventDefault();
    formato_numero_control(mask_numero_control,e.key,array_numero_control);
    d.querySelector('#modificar_numero_control').value = array_numero_control.join("");
    if(d.querySelector('#modificar_numero_control').value !== ""){
        d.querySelector('#modificar_numero_control').parentElement.classList.add('is-dirty');
    }
});

$btn_modificar_compra.addEventListener('click', async e => {

    e.preventDefault();
    e.stopPropagation();

    //Extraemos los datos del Fomulario
    let numero_factura = d.querySelector('#modificar_numero_factura');
    let numero_control =  d.querySelector('#modificar_numero_control');
    let nombre_proveedor =  d.querySelector('#modificar_nombre_proveedor');
    let precio_compra = d.querySelector('#modificar_precio_compra');
    let fecha_compra = d.querySelector('#modificar_fecha_compra');

    let arrayInputs = [numero_factura,numero_control,nombre_proveedor,precio_compra, fecha_compra];

    //Validamos los datos del formulario
    if(!validarCamposVacios(arrayInputs) || !validar_numero_factura(numero_factura) || !validar_numero_control(numero_control) || !validarExpresion(nombre_proveedor) || !validarCantidadConDecimales(precio_compra)){
        return;
    }
        
    let data = {
        numero_factura :  numero_factura.value.toUpperCase(),
        numero_control : numero_control.value,
        nombre_proveedor : nombre_proveedor.value,
        precio_compra :  precio_compra.value,
        fecha_compra : fecha_compra.value,
        id_compra : datos_compra[0]["id_compra"],
    }

    let is_numero_factura_registrado = await isNumeroFacturaRegistrado(numero_factura.value,url_compra);
    if(is_numero_factura_registrado == 1 ){
        alerta({
            titulo : 'Número de factura ya se encuentra registrado',
            tipo_mensaje : "error"
        });
        numero_factura.parentElement.classList.add('is-invalid');
        return;
    }else if(is_numero_factura_registrado == 2){
        return;
    }

    saveCompra(data,url_compra);
});

d.addEventListener("DOMContentLoaded", async e => {
    await extraer_id_compra(get_sessiones);
});