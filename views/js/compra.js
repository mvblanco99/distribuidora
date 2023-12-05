import { loadDatos_user } from "./utils.js";
import {url} from "./urls.js";
import {validarCamposVacios, validarCantidadConDecimales, validarExpresion, validarNumeros, validar_numero_control, validar_numero_factura} from "./validaciones.js";
import { alerta } from "./utils.js";

const {url_compra} = url;
const d = document,
$btn_registrar_compra = d.querySelector('#btn_registrar_compra');

//Variables utilizadas para dar formato al numero de control
const mask_numero_control = '##-###########';//Patron de numero de control
let array_numero_control = [];

//Guardamos datos de la compra (Tabla Compra)
const saveCompra = async (datos, url) => { 

    const body = {
        method : 'POST',
        body : JSON.stringify(datos) 
    }

    try {
        const res = await fetch(`${url}?recibir_datos_compra=1`,body);
        if(!res.ok) throw {status : res.statusText, statusText: res.statusText};
        const data = await res.json();
        console.log(data);
        if(data['success'] == 1){
            alerta({
                titulo: "Paso 1 Completado",
                tipo_mensaje: "success",
                callback : ()=>{
                  window.location = "compra_productos";  
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

const formatear_numero_factura = (cadena) => {
    let nuevaCadena = "";

    for (let i = 0; i < cadena.length; i++) {
        if(cadena[i].charCodeAt(0) > 96 && cadena[i].charCodeAt(0) < 123){
            nuevaCadena +=  cadena[i].toUpperCase();
        }else{
            nuevaCadena += cadena[i];
        }
    }

    return nuevaCadena;
}

//Comprobamos si el codigo de producto ya se encuentra registrado
const isNumeroFacturaRegistrado = async (numero_factura, url) => {

    try {
        const res = await fetch(`${url}?validar_numero_factura=${numero_factura}`);
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
            //En este caso se puede registrar el numero de factura
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

d.querySelector('#numero_control').addEventListener('keydown', e=>{
    if(e.key === 'Tab'){
        return;
    }

    e.preventDefault();
    formato_numero_control(mask_numero_control,e.key,array_numero_control);
    d.querySelector('#numero_control').value = array_numero_control.join("");
    if(d.querySelector('#numero_control').value !== ""){
        d.querySelector('#numero_control').parentElement.classList.add('is-dirty');
    }
})

$btn_registrar_compra.addEventListener('click', async e => {

    e.preventDefault();
    e.stopPropagation();

    //Extraemos los datos del Fomulario
    let numero_factura = d.querySelector('#numero_factura');
    let numero_control =  d.querySelector('#numero_control');
    let nombre_proveedor =  d.querySelector('#nombre_proveedor');
    let precio_compra = d.querySelector('#precio_compra');
    let fecha_compra = d.querySelector('#fecha_compra');

    let arrayInputs = [numero_factura,numero_control,nombre_proveedor,precio_compra, fecha_compra];

    //Validamos los datos del formulario
    if(!validarCamposVacios(arrayInputs) || !validar_numero_factura(numero_factura) || !validar_numero_control(numero_control) || !validarExpresion(nombre_proveedor) || !validarCantidadConDecimales(precio_compra)){
        return;
    }

    //Le cambiamos el formato al numero de factura
    let nueva_cadena = formatear_numero_factura(numero_factura.value);

    //Extraemos los datos del usuario logeado para asi tener agregarlo al registro y tener control sobre que usuario hizo tal registro
    const datos = loadDatos_user();

    let arrayDatos;
    try {
    arrayDatos = JSON.parse(datos);  
    } catch (error) {
        arrayDatos = [];
    }

    //Extraemos el tipo de administrador
    let admin = arrayDatos['id_usuario'];
        
    let data = {
        numero_factura :  nueva_cadena,
        numero_control : numero_control.value,
        nombre_proveedor : nombre_proveedor.value,
        precio_compra :  precio_compra.value,
        fecha_compra : fecha_compra.value,
        admin
    }

    //Comprobamos si el numero de factura ingresado ya se encuentra registrado
    let is_numero_factura_registrado = await isNumeroFacturaRegistrado(numero_factura.value,url_compra);
    if(is_numero_factura_registrado['success'] == 1 ){
        alerta({
            titulo : 'Número de factura ya se encuentra registrado',
            tipo_mensaje : "error"
        });
        numero_factura.parentElement.classList.add('is-invalid');
        return;
    }else if(is_numero_factura_registrado['success'] == 2){
        return;
    }

    saveCompra(data,url_compra);
});
