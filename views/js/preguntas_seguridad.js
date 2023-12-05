import { url } from './urls.js';
import { alerta } from './utils.js';
import { validarExpresion } from './validaciones.js';

const { get_sessiones } = url;

const d = document,
container_preguntas = d.querySelector('#preguntas_seguridad');

let data_admin;

const mostrar_preguntas = (data) => {
    d.querySelector('#label_primer_pregunta').textContent = data[0];
    d.querySelector('#label_segunda_pregunta').textContent = data[1];
}

const extraer_datos_admin = async(url) => {
    try {
        const res = await fetch(`${url}?extraer_all_data_admin=1`)
        if(!res.ok) throw {status: res.status, statusText: res.statusText};
        const data = await res.json();
        data_admin = data;
        mostrar_preguntas([data[5],data[7]]);
        console.log(data_admin);
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

const comprobar_respuesta = (respuestas) => {

    let array_respuestas = [data_admin[6],data_admin[8]];
    let confirmado = true;

    for (let i = 0; i < array_respuestas.length; i++) {
    
        if(respuestas[i].value !== array_respuestas[i]){
            alerta({
                titulo : "Respuesta equivocada",
                tipo_mensaje : "error"
            });
            respuestas[i].parentElement.classList.add('is-invalid');
            confirmado = false;
            break;
        }
    }

    return confirmado;

}

d.addEventListener('DOMContentLoaded', async e => {

    if(container_preguntas !== null){
        await extraer_datos_admin(get_sessiones);

        d.addEventListener('submit', ev => {

            ev.preventDefault();
            ev.stopPropagation();

            //Extraemos los datos del formulario

            let p_respuesta = d.querySelector('#p_respuesta');
            let s_respuesta = d.querySelector('#s_respuesta');

            if(!validarExpresion(p_respuesta) || !validarExpresion(s_respuesta)){
                return;
            }

            if(comprobar_respuesta([p_respuesta,s_respuesta])){
                window.location = 'modificar_contrasenia_usuario';
            }

        });
    }
});
