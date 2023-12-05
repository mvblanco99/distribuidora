import {url} from "./urls.js";
import { alerta } from "./utils.js";
import { validarCamposVacios, validarExpresion, validarNombres_Apellidos, validarNumeros, validar_usuario } from "./validaciones.js";

const {url_administradores} = url;

const d = document,
$tabNewAdmin = d.querySelector('#tabNewAdmin'),
$btn_addAdmin = d.querySelector('#add-admin');

const saveDataAdmin = async (url,dataAdministrador) => {

    const body = {
        method : 'POST',
        body : JSON.stringify(dataAdministrador)
    }

    try {
        const res = await fetch(`${url}?RegistrarAdministrador=1`,body);
        if(!res.ok) throw {status: res.status, statusText: res.statusText};
        const data = await res.json();
        if(data['success'] == 1){
            alerta({
                titulo: "Registro Exitoso",
                mensaje : "El Administrador se ha registrado Satisfactoriamente",
                tipo_mensaje: "success",
                callback : ()=>{
                  window.location = "lista_administradores";  
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
            tipo_mensaje: "error"
        });
    }
}

const seleccionAvatar = data => {
    let avatarSeleccionado = null;
    for (let i = 0; i < data.length; i++) {
        if(data[i].checked){
            avatarSeleccionado = data[i].value;
            break;
        }
    }
    return avatarSeleccionado;
}

const isUsuarioRegistrado = async (usuario, url) => {

    try {
        const res = await fetch(`${url}?validar_usuario=${usuario}`);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        if(data['success'] == 1){
            return data;
        }else{
            //En caso de fallar la validacion en el backend se envia un alerta y se detiene el proceso de registro
            if(typeof data['success'][0] === "string"){
                alerta({
                    titulo: "Error",
                    mensaje : data["success"][0],
                    tipo_mensaje: "error",
                });
                data['success'][1] !== '' ? d.querySelector(`#${data['success'][1]}`).parentElement.classList.add('is-invalid') : "";
                return data['success'] = 2;
            }
            //Se continua el proceso de registro
            return data['success'] = 0;
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

d.addEventListener('DOMContentLoaded', e=> {

    if($tabNewAdmin !== null){

        d.addEventListener('click', async ev => {

            if(ev.target === $btn_addAdmin){

                //Extraemos los datos del formulario
                let name = d.querySelector('#NameAdmin');
                let last_name = d.querySelector('#LastNameAdmin');
                let user_name = d.querySelector('#UserNameAdmin');
                let password = d.querySelector('#passwordAdmin');
                let primera_pregunta = d.querySelector('#one_question_security');
                let primera_respuesta = d.querySelector('#one_answer_security');
                let segunda_pregunta = d.querySelector('#two_question_security');
                let segunda_respuesta = d.querySelector('#two_answer_security');
                let avatares = d.getElementsByName('options');
                let tipo_admin = d.querySelector('#tipo_admin');

                let avatarSeleccionado = seleccionAvatar(avatares);

                let arrayInputs = [name, last_name, primera_pregunta, primera_respuesta, segunda_pregunta, segunda_respuesta, user_name, password,tipo_admin];
1
                 //Validamos los datos del formulario
                 if(!validarCamposVacios(arrayInputs)  || !validarNombres_Apellidos(name) || !validarNombres_Apellidos(last_name) || !validarExpresion(primera_pregunta) || !validarExpresion(primera_respuesta) || !validarExpresion(segunda_pregunta) || 
                 !validarExpresion(segunda_pregunta) || !validarExpresion(segunda_respuesta) ||
                 !validar_usuario(user_name) || !validarExpresion(password) ||
                 !validarNumeros(tipo_admin)){
                     return;
                 }
                
                //Comprobamos si el usuario ingresado ya se encuentra registrado
                let is_usuario_registrado = await isUsuarioRegistrado(user_name.value, url_administradores);
                if(is_usuario_registrado["success"] == 1){
                    alerta({
                        titulo:'Usuario ya se encuentra registrado',
                        tipo_mensaje : 'error'
                    });
                    d.querySelector('#text_UserNameAdmin').classList.add('is-invalid');
                    return;
                }else if(is_usuario_registrado["success"] == 2){
                    return;
                }

                let data = {
                    name : name.value,
                    last_name : last_name.value,
                    user_name : user_name.value,
                    password : password.value,
                    primera_pregunta : primera_pregunta.value,
                    primera_respuesta : primera_respuesta.value,
                    segunda_pregunta : segunda_pregunta.value,
                    segunda_respuesta : segunda_respuesta.value,
                    tipo_admin : tipo_admin.value,
                    avatarSeleccionado 
                }
                
                saveDataAdmin(url_administradores,data) 
            }
        });
    }
});