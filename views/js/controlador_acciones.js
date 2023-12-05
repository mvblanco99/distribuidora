import { loadDatos_user } from "./utils.js";
import { url } from "./urls.js";

const { url_controlador_acceso } = url;
const d = document;

const consultar_permiso = async (accion,tipo_admin,url) => {

    try {
        const res = await fetch(`${url}?comprobarPermisoAccion=1&accion=${accion}&tipo_admin=${tipo_admin}`);
        const data = await res.json();
        return data.success;
    } catch (error) {
        console.log(error);
    }

}

export const comprobar_permiso_accion = async (accion) => {

    let permiso = null;

    //Extraemos los datos del usuario logeado
    const datos = loadDatos_user();

    let arrayDatos;
    try {
    arrayDatos = JSON.parse(datos);  
    } catch (error) {
        arrayDatos = [];
    }

    //Extraemos el tipo de administrador
    let tipo_admin = arrayDatos['tipo_admin'];

    permiso = await consultar_permiso(accion,tipo_admin,url_controlador_acceso);

    return permiso;

}


