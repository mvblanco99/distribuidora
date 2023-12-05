import { url } from "./urls.js";
import { alerta} from "./utils.js";
import { validarCamposVacios, validarNombres_Apellidos, validarExpresion } from "./validaciones.js";
import { comprobar_permiso_accion } from "./controlador_acciones.js";

const { get_sessiones,url_categorias } = url;

const d = document,
$btn_eliminar_categoria = d.querySelector('#btn_eliminar_categoria');

let id_categoria;

const extraer_id_categoria = async(url) => {
    try {
        const res = await fetch(`${url}?extraer_id_categoria=1`);
        const data = await res.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}

const extraer_data_categoria = async(url,id_categoria) => {
    try {
        const res = await fetch(`${url}?extraer_data_categoria=${id_categoria}`);
        const data = await res.json();
        return data.success;
    } catch (error) {
        console.log(error);
    }
}

const show_data_categoria = (data) => {

    if(data.length > 0){

        data.forEach(element => {
            d.querySelector('#NameCategory').value = element.nombre_categoria;
            d.querySelector('#NameCategory').parentElement.classList.add('is-dirty');
            d.querySelector('#descriptionCategory').value = element.descripcion_categoria;
            d.querySelector('#descriptionCategory').parentElement.classList.add('is-dirty');
        });
    }
}

const eliminar_categoria = async (url,id_categoria) => {
    try {
        const res = await fetch(`${url}?eliminar_categoria=${id_categoria}`);
        const data = await res.json();
        if(data['success'] == 1){
            alerta({
                titulo : 'Categoría Eliminada Exitosamente',
                tipo_mensaje : 'success',
                bool : true,
                callback : () => {
                    window.location = 'lista_categorias';
                }
            });
        }else if(data['success'] == 2){
            alerta({
                titulo : 'No se puede borrar la categoría',
                mensaje : 'Categoría es utilizada en varios registros',
                tipo_mensaje : 'warning',
            });
        }else if(data['success'] == 0){
            alerta({
                titulo : 'Error al borrar categoría',
                tipo_mensaje : 'error',
            });
        }
    } catch (error) {
        console.log(error);
    }
}

//////Guardar datos del producto ///////////
const save_categoria = async (data_categoria, url) => {

    const body = {
        method : 'POST',
        body : JSON.stringify(data_categoria),
    }

    try {
        const res = await fetch(`${url}?modificar_categoria=1`,body);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data =  await res.json();
        if(data['success'] == 1){
            alerta({
                titulo : "Modificación Exitosa",
                mensaje : "La Categoría se ha Modificado Satisfactoriamente",
                tipo_mensaje : "success",
                callback : () => {
                    window.location = "lista_categorias";
                },
                bool :true
            })
        }else{
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

$btn_eliminar_categoria.addEventListener('click', async e => {

    //Verificamos si el administrador tiene los privilegios para eliminar
    let permiso_accion = await comprobar_permiso_accion('eliminar');

    if(permiso_accion){
        alerta({
            titulo : '¿Esta seguro de eliminar la categoría?',
            tipo_mensaje : 'warning',
            bool : true,
            callback : () => {
                eliminar_categoria(url_categorias,id_categoria);
            }
        });
    }else{
        alert('No tiene los privilegios para realizar esta acción');
    }
});

d.addEventListener('submit', async e => {

    e.preventDefault();

    //Enalazamos los inputs del formulario
    const inputs = Array.from(e.target.querySelectorAll('.data-categoria'));

    const data_categoria = {};

    //Insertamos valores al objeto
    inputs.forEach((element) => {
        data_categoria[`${element.id}`] = element.value;
    });

    //Validamos los inputs
    if(!validarCamposVacios(inputs) || !validarNombres_Apellidos(inputs[0]) || !validarExpresion(inputs[1])){
        return;
    }

    //INSERTAMOS EL ID DE LA CATEGORIA
    data_categoria.id_categoria = id_categoria;
    //Guardamos la nueva categoria
    save_categoria(data_categoria,url_categorias)

});

d.addEventListener('DOMContentLoaded', async e => {

    //EXTRAEMOS EL ID DE LA CATEGORIA
    id_categoria = await extraer_id_categoria(get_sessiones);
    //EXTRAEMOS LOS DATOS DE LA CATEGORIA
    const data_categoria = await extraer_data_categoria(url_categorias, id_categoria);
    //MOSTRAMOS LOS DATOS DE LA CATEGORIA
    show_data_categoria(data_categoria);
});