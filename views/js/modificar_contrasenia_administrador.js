import { url } from "./urls.js";
import { alerta } from "./utils.js"; 
import { validarExpresion } from "./validaciones.js";

const { get_sessiones, url_administradores } = url;

const d = document,
container_modificar_contarsenia = d.querySelector('#container_modificar_contarsenia');

let data_admin;

const extraer_datos_admin = async(url) => {
    try {
        const res = await fetch(`${url}?extraer_all_data_admin=1`)
        if(!res.ok) throw {status: res.status, statusText: res.statusText};
        const data = await res.json();
        data_admin = data;
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

const cambiar_contrasenia = async (data_admin, url) => {

    console.log(data_admin)
    let datos = {
        method : 'POST',
        body : JSON.stringify(data_admin)
    };

    try {
        const res = await fetch(`${url}?Update_Password_Administrador=1`,datos);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data =  await res.json();
        if(data['success'] == 1){
            alerta({
                titulo : "Modificación Exitosa",
                mensaje : "La contraseña se ha modificado exitosamente",
                tipo_mensaje : "success",
                callback : () => {
                    window.location = "index";
                },
                bool :true
            })
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

const confimar_cambio_contrasenia = (new_password, user ,url) => {
    swal({
        title: '¿Esta seguro de cambiar la contraseña?',
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, exit',
        closeOnConfirm: false
    },
    (isConfirm) => {
        if (isConfirm) {
            cambiar_contrasenia({password : new_password, user}, url)
        }
    });
}

d.addEventListener('DOMContentLoaded', async e => {

    if(container_modificar_contarsenia !== null){

        await extraer_datos_admin(get_sessiones);

        d.addEventListener('submit', ev => {

            //Extraemos los datos del formulario
            ev.preventDefault();
            ev.stopPropagation();

            let password = d.querySelector('#password');

            if(!validarExpresion(password)){
                return;
            }

            confimar_cambio_contrasenia(password.value, data_admin[3], url_administradores);
        });
    }
});


