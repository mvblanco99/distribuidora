import { loadDatos_user } from "./utils.js";
import { url } from "./urls.js";

const { url_controlador_acceso } = url;
 
const d = document,
$nav_lateral = d.querySelector('#nav-lateral');

const comprobar_acceso = async (destino,tipo_admin, url) =>{

    try {
        const res = await fetch(`${url}?comprobar_acceso=1&destino=${destino}&tipo_admin=${tipo_admin}`);
        const data = await res.json();
        return data.success;
    } catch (error) {
        console.log(error);
    }

}

d.addEventListener('click', async e => {
        
    if(e.target.classList.contains('acceso')){
        e.preventDefault();

        let hrefValue; 

        //Recuperamos el contenido de la propiedad href de la etiqueta a

        //Verificamos que los click esten dentro del contenido de la barra de lateral
        if($nav_lateral.contains(e.target)){
            hrefValue = e.target.parentElement.getAttribute('href');
        }else{
            hrefValue = e.target.getAttribute('href');
        }

        console.log(hrefValue)

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

        console.log(tipo_admin)

        // Comprobamos si el usuario tiene los permisos para redireccionar a la pagina solicitada
        let permiso_acceso = await comprobar_acceso(hrefValue,tipo_admin,url_controlador_acceso);

        //Redirecciona
        if(permiso_acceso[0]){
            window.location = permiso_acceso[1];
        }else{
            alert('No tiene los privilegios para accesar la direccion seleccionada');
        }
        
    }
});