import { validar_usuario } from "./validaciones.js";
import { url } from "./urls.js";
import { alerta } from "./utils.js";

const { url_administradores, url_sesiones } = url;

const d = document,
container = d.querySelector('#contenedor_button_verificar_usuario');

if(container !== null){

    const crear_variable_session_administrador = async(admin, url) => {

        let datos = {
            method: 'POST',
            body : JSON.stringify(admin)
        };

        try {
            const res = await fetch(`${url}?all_data_administrador=1`, datos);
            if(!res.ok) throw {status : res.status, statusText : res.statusText};
            const data = await res.json();
            console.log(data)
            if(data.data !== null){
                window.location = 'preguntas_seguridad';
            }
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

    const verificar_usuario = async (usuario, url) => {

        try {
            const res = await fetch (`${url}?consultar_administrador_por_usuario=${usuario}`);
            if(!res.ok) throw {status :  res.status, statusText :  res.statusText};
            const data = await res.json();
            data['success'] !== 0 ? crear_variable_session_administrador({data : data['success'][0]}, url_sesiones) : "";
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

    d.addEventListener('submit' , async e => {

        e.preventDefault();
        e.stopPropagation();

        let usuario = d.querySelector('#searchAdmin');

        console.log(usuario.value)

        if(!validar_usuario(usuario)){
            return;
        }

        await verificar_usuario(usuario.value,url_administradores);

    });
}