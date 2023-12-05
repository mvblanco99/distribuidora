import {url} from "./urls.js";
import {avatarSeleccionado, loadDatos_user} from "./utils.js";

const {get_sessiones, url_administradores} = url;

const d = document,
$full_width_navLateral = d.querySelector('#full-width_navLateral'),
$nav_user_name_logeado = d.querySelector('#nav_user_name_logeado'),
$small_user_name_logeado = d.querySelector('#small_user_name_logeado'),
$nav_avatar = d.querySelector('#nav-avatar'),
$header_avatar = d.querySelector('#header-avatar');

//Mostramos los datos del administrador
const show_data_admin = (data) => {

    data.forEach(element => {
        $nav_user_name_logeado.insertAdjacentText('afterbegin',
        `${element['nombre']} ${element['apellido']}`);
        $small_user_name_logeado.insertAdjacentText('afterbegin',
        `${element['nombre']} ${element['apellido']}`);
        $nav_avatar.src = avatarSeleccionado(element['url_image']);
        $header_avatar.src = avatarSeleccionado(element['url_image']);
    });
}
    
//Extraemos los datos del administrador
const extraer_datos_administrador = async (url,administrador) => {
    try {
        const res = await fetch(`${url}?consultar_administrador_por_usuario=${administrador}`);
        const data = await res.json();
        show_data_admin(data); 
    } catch (error) {
        
    }
}


//Recuperamos el id del administrador por medio de la variable de session creada en php
const extraerIdSession = async(url) => {
    try {
        const res = await fetch(`${url}?user_admin=1`);
        const data = await(res.json());
        console.log(data[0]);
        extraer_datos_administrador(url_administradores,data[0]);
    } catch (error) {
        //Acordate de Gestionar el error
        console.log(error);
    }
}

d.addEventListener('DOMContentLoaded', e => {

    if($full_width_navLateral !== null){
        //extraerIdSession(get_sessiones);

        const datos = loadDatos_user();

        let arrayDatos;
        try {
          arrayDatos = JSON.parse(datos);  
        } catch (error) {
            arrayDatos = [];
        }

        show_data_admin([arrayDatos]);
    
    }

})



