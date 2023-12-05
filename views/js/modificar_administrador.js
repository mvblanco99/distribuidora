import { url } from "./urls.js";
import { alerta,loadDatos_user } from "./utils.js";
import { validarNombres_Apellidos, validarCamposVacios, validarExpresion, validar_usuario, validarNumeros } from "./validaciones.js";

const {url_administradores, get_sessiones} = url;

const d = document,
$tabUpdateAdmin = d.querySelector('#tabUpdateAdmin'),
$btn_update_admin = d.querySelector('#btn-update-admin');
let id_administrador = null;//Guardaremos el id del administrador

//Se selecciona el avatar extraido de la base de datos
const getAvatar = (avatar) => {
    let avatares = d.getElementsByName('options');
    for (let i = 0; i < avatares.length; i++) {
        if(avatar == avatares[i].value){
            avatares[i].checked = true;
            avatares[i].parentElement.classList.add('is-checked');
            break;
        }
    }
}

//Se selecciona el el nuevo avatar
const setAvatar = data => {
    let avatarSeleccionado = null;
    for (let i = 0; i < data.length; i++) {
        if(data[i].checked){
            avatarSeleccionado = data[i].value;
            break;
        }
    }
    return avatarSeleccionado;
}

//Actualizar datos del localStorage
const updateDataLocalStorage = (dataAdministrador) => {

    //EN el caso de que el administrador quen esta siendo actualizado sea el mismo que tiene la sesion activa, se debe actualizar los datos del localStorage

    //Extreamos los datos del localstorage
    let datos_user = loadDatos_user();

     let array_datos_user;
        try {
          array_datos_user = JSON.parse(datos_user);  
        } catch (error) {
            array_datos_user = [];
        }

        console.log(dataAdministrador.id_administrador)
        console.log(array_datos_user.id_usuario)
    //Comprobamos si el administrador modificado es el mismo que tiene la sesion activa
    if(array_datos_user.id_usuario === dataAdministrador.id_administrador){

        let data = {
            apellido : dataAdministrador.last_name,
            contrasenia  : dataAdministrador.password,
            id_usuario : dataAdministrador.id_administrador,
            nombre: dataAdministrador.name,
            p_pregunta_seguridad : dataAdministrador.primera_pregunta,
            p_respuesta_seguridad: dataAdministrador.primera_respuesta,
            s_pregunta_seguridad: dataAdministrador.segunda_pregunta,
            s_respuesta_seguridad: dataAdministrador.segunda_respuesta,
            url_image: dataAdministrador.avatarSeleccionado,
            usuario: dataAdministrador.user_name,
            tipo_admin : dataAdministrador.tipo_admin,
        }
    
        //Si pasa la validacion, se modifican los datos
        localStorage.setItem("datos_user",JSON.stringify(data));
    }
}

//Guardar datos del administrador
const saveDataAdmin = async (url,dataAdministrador) => {
    
    const body = {
        method: 'POST',
        body: JSON.stringify(dataAdministrador)
    }

    try {
        const res = await fetch(`${url}?Update_Administrador=1`,body);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        if(data['success'] == 1){
            updateDataLocalStorage(dataAdministrador);
            alerta({
                titulo: "Modificación Exitosa",
                mensaje: "Los Datos del Administrador se ha Modificado Satisfactoriamente",
                tipo_mensaje: "success",
                callback: () => {
                    window.location = "lista_administradores";
                },
                bool: true
            })
        }else{
            alerta({
                titulo: "Error",
                mensaje: data["success"][0],
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

//Mostrar datos del administrador
const showDataAdministrador = (data) => {

    console.log(data)
    data.forEach(element => {

        d.querySelector('#text_name_admin').classList.add('is-dirty');
        d.querySelector('#updateNameAdmin').value = element['nombre'];

        d.querySelector('#text_last_name_admin').classList.add('is-dirty');
        d.querySelector('#updateLastNameAdmin').value = element['apellido'];

        d.querySelector('#text_one_question_security').classList.add('is-dirty');
        d.querySelector('#update_one_question_security').value = element['p_pregunta_seguridad'];
       
        d.querySelector('#text_one_answer_security').classList.add('is-dirty');
        d.querySelector('#update_one_answer_security').value = element['p_respuesta_seguridad'];
       
        d.querySelector('#text_two_question_security').classList.add('is-dirty');
        d.querySelector('#update_two_question_security').value = element['s_pregunta_seguridad'];
        
        d.querySelector('#text_two_answer_security').classList.add('is-dirty');
        d.querySelector('#update_two_answer_security').value = element['s_respuesta_seguridad'];
       
        d.querySelector('#text_UserNameAdmin').classList.add('is-dirty');
        d.querySelector('#updateUserNameAdmin').value = element['usuario'];
        
        d.querySelector('#text_passwordAdmin').classList.add('is-dirty');
        d.querySelector('#update_passwordAdmin').value = element['contrasenia'];

        d.querySelector('#tipo_admin').value = element.tipo_admin;
        d.querySelector('#tipo_admin').classList.add('is-dirty');

        getAvatar(element['url_image']);
    });
}

//Recuperamos los datos del administrador utilizando el id recuperado
const getDataAdministrador =  async(id_administrador) => {
    
    try {
        const res = await fetch(`${url_administradores}?consultar_administrador=${id_administrador}`);
        if(!res.ok) throw {status:res.status, statusText:res.statusText};
        const data = await(res.json());
        if(typeof data['success'][0] === "string"){
            alerta({
                titulo: "Error",
                mensaje : data["success"][0],
                tipo_mensaje: "error",
            });
        }else{
            showDataAdministrador(data['success']);
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

//Recuperamos el id del administrador por medio de la variable de session creada en php
const extraerIdSession = async() => {
    try {
        const res = await fetch(`${get_sessiones}?extraer_id_administrador_session=1`)
        if(!res.ok) throw {status: res.status, statusText: res.statusText};
        const data = await res.json();
        id_administrador = data;
        getDataAdministrador(data)
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

d.addEventListener('DOMContentLoaded', e => {

    if($tabUpdateAdmin !== null){
        
        extraerIdSession();

        d.addEventListener('click', ev => {

            if(ev.target === $btn_update_admin){

                //Extreamos los datos del formulario
                let name = d.querySelector('#updateNameAdmin');
                let last_name = d.querySelector('#updateLastNameAdmin');
                let user_name = d.querySelector('#updateUserNameAdmin');
                let password = d.querySelector('#update_passwordAdmin');
                let primera_pregunta = d.querySelector('#update_one_question_security');
                let primera_respuesta = d.querySelector('#update_one_answer_security');
                let segunda_pregunta = d.querySelector('#update_two_question_security');
                let segunda_respuesta = d.querySelector('#update_two_answer_security');
                let avatares = d.getElementsByName('options');
                let tipo_admin = d.querySelector('#tipo_admin');

                let avatarSeleccionado = setAvatar(avatares);

                let arrayInputs = [name, last_name, primera_pregunta, primera_respuesta, segunda_pregunta, segunda_respuesta, user_name, password,tipo_admin];

                //Validamos los datos del formulario
                if(!validarCamposVacios(arrayInputs)  || !validarNombres_Apellidos(name) || !validarNombres_Apellidos(last_name) || !validarExpresion(primera_pregunta) || !validarExpresion(primera_respuesta) || !validarExpresion(segunda_pregunta) || 
                !validarExpresion(segunda_pregunta) || !validarExpresion(segunda_respuesta) ||
                !validar_usuario(user_name) ||
                !validarNumeros(tipo_admin)){
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
                    avatarSeleccionado, 
                    id_administrador,
                    tipo_admin : tipo_admin.value 
                }

                saveDataAdmin(url_administradores,data);
            }
        })
    }
})