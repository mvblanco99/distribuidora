import { url } from './urls.js';
import { validarCamposVacios, validarExpresion, validarNombres_Apellidos } from './validaciones.js';
import { alerta } from './utils.js';

const { url_categorias } = url;

const d = document;

const verificar_existencia_nombre_categoria = async (url,nombre) => {

    try {
        const res = await fetch(`${url}?verificar_nombre_categoria=${nombre}`);
        if(!res.ok) throw {status: res.status, statusText:res.statusText};
        const data = await res.json();
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

//////Guardar datos del producto /////////
const save_categoria = async (data_categoria, url) => {

    const body = {
        method : 'POST',
        body : JSON.stringify(data_categoria),
    }

    try {
        const res = await fetch(`${url}?registrar_categoria=1`,body);
        if(!res.ok) throw {status: res.status, statusText: res.statusText};
        const data = await res.json();
        if(data['success'] == 1){
            alerta({
                titulo: "Registro Exitoso",
                mensaje : "La Categoría se ha registrado Satisfactoriamente",
                tipo_mensaje: "success",
                callback : ()=>{
                    window.location = "lista_categorias";  
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
            tipo_mensaje:"error"
        });
    }
}

d.addEventListener('submit', async e => {

    e.preventDefault();

    //Enalazamos los inputs del formulario
    const inputs = Array.from(e.target.querySelectorAll('.data-categoria'));

    const data_categoria = {};

    //Insertamos valores al objeto
    inputs.forEach((element) => {
        data_categoria[`${element.id}`] = element.value;
    });

    console.log(inputs[1].value);

    //Validamos los inputs
    if(
        !validarCamposVacios(inputs) || 
        !validarNombres_Apellidos(inputs[0]) || 
        !validarExpresion(inputs[1])
    ){
        return;
    }

    //Verificamos si el nombre de categoria proporcionado ya se encuentra guardado en la base de datos
    let is_nombre_categoria_registrado = await verificar_existencia_nombre_categoria(url_categorias,data_categoria.NameCategory);

    if(is_nombre_categoria_registrado['success'] == 1 ){
        alerta({
            titulo : 'El nombre de la Categoría ya se encuentra registrado',
            tipo_mensaje : "error"
        });
        inputs[0].parentElement.classList.add('is-invalid');
        return;
    }else if(is_nombre_categoria_registrado['success'] == 2){
        return;
    }

    //Guardamos la nueva categoria
    save_categoria(data_categoria,url_categorias)
});