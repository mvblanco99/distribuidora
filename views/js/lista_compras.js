import {url} from "./urls.js";
import {alerta, extraer_fecha_actual} from "./utils.js";
import { comprobar_permiso_accion } from "./controlador_acciones.js";

const {url_compra,url_sesiones} = url;

const d = document,
$fecha_resumen_compras = d.querySelector('#fecha_resumen'),
$lista_resumen_compra = d.querySelector('#lista_compras'),
$fragment = d.createDocumentFragment();

//Mostramos todas las compras
const show_compras_disponibles = (data_compras) => {

    //enlazamos el template creado en el HTML
    const $template_items_compras_disponibles = d.querySelector('#items_lista_resumen_compras').content;

    if(data_compras.length > 0){

        data_compras.forEach(element => {
            //Insertamos los datos en el template
            $template_items_compras_disponibles.querySelector('.codigo_factura').textContent = element.numero_factura;
            $template_items_compras_disponibles.querySelector('.numero_control').textContent = element.numero_control;
            $template_items_compras_disponibles.querySelector('.monto_total').textContent = `$${element.precio_total_compra}`;
           
            $template_items_compras_disponibles.querySelector('.btn-primary').dataset.id = element['id_compra'];
            $template_items_compras_disponibles.querySelector('.btn-info').dataset.id = element['id_compra'];
            $template_items_compras_disponibles.querySelector('.btn-danger').dataset.id = element['id_compra'];
             //guardamos una copia de la estrutura actual del template en la variable $node
            let $clone = $template_items_compras_disponibles.cloneNode(true);
            //Guardamos el nodo en el fragment
            $fragment.append($clone);

        });
        //Limpiamos la lista
        $lista_resumen_compra.innerHTML = "";
        //Insertamos el fragment en la lista
        $lista_resumen_compra.append($fragment);

    }else{
        //Limpiamos la lista
        $lista_resumen_compra.innerHTML = "<tr><td>No hay compras disponibles</td><td><td><td></td></tr>";
    }
}

//Eliminar  compra
const eliminar_compra = async (id_compra, url) => {

    try {
        const res = await fetch(`${url}?recibimimos_datos_compra_eliminar=${id_compra}`);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        if(data['success'] == 1){
            alerta({
                titulo : 'Compra Eliminada Exitosamente',
                tipo_mensaje : 'success',
                bool : true,
                callback : async () => {
                    const data_compras = await extraer_datos_compras($fecha_resumen_compras.value, url_compra);
                    show_compras_disponibles(data_compras);
                }
            })
        }else if(data['success'] == 2){
            alerta({
                titulo : 'No se puede borrar la compra',
                mensaje : 'Compra es utilizada en varios registros',
                tipo_mensaje : 'warning',
            })
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

//Guardamos el id de la compra en una variable session de php
const crear_variable_session_compra = async(id_compra,url,variable_get,direccion) => {

    try {
        const res = await fetch(`${url}?${variable_get}=${id_compra}`);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        if(data.data !== ""){
            window.location = direccion;
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

const verificar_estatus_seleccionado = async(id_compra, url) => {
    try {
        const res = await fetch(`${url}?verificar_estatus_seleccionado=${id_compra}`);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        if(data.success == 0){
            return true;
        }else{
            return false;
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

d.addEventListener('click', async e => {

    //Modificar compra
    if(e.target.classList.contains('btn-info')){

        //Verificamos si el administrador tiene los privilegios para eliminar
        let permiso_accion = await comprobar_permiso_accion('modificar');

        if(permiso_accion){

            //Verificar si estatus seleccionado de la compra
            const estatus = await verificar_estatus_seleccionado(e.target.dataset.id,url_compra);

            if(estatus){
                crear_variable_session_compra(
                    e.target.dataset.id,url_sesiones,
                    'variable_session_id_compra_modificar',
                    'modificar_compra'
                );
            }else{
                alerta({
                    titulo : "No puede modificar esta compra",
                    mensaje : 'Compra es utilizada en varios registros',
                    tipo_mensaje : 'warning'
                })
            }
        }else{
            alert('No tiene los privilegios para realizar esta acción');
        }
    }

    //Borrar  compra
    if(e.target.classList.contains('btn-danger')){ 

        //Verificamos si el administrador tiene los privilegios para eliminar
        let permiso_accion = await comprobar_permiso_accion('eliminar');

        if(permiso_accion){
            alerta({
                titulo : '¿Esta seguro de eliminar la compra?',
                tipo_mensaje : 'warning',
                bool : true,
                callback : () => {
                    eliminar_compra(e.target.dataset.id,url_compra);
                }
            });
        }else{
            alert('No tiene los privilegios para realizar esta acción');
        }
    }

    //Visualiza compra
    if(e.target.classList.contains('btn-primary')){

        //Verificamos si el administrador tiene los privilegios para eliminar
        let permiso_accion = await comprobar_permiso_accion('buscar');

        if(permiso_accion){
            crear_variable_session_compra(
                e.target.dataset.id, 
                url_sesiones,
                'variable_session_id_compra_visualizar',
                'visualizar_compra'
            );
        }else{
            alert('No tiene los privilegios para realizar esta acción');
        } 
    }
});

const extraer_datos_compras = async (fecha,url) => {
    try {
        const res = await fetch(`${url}?obtener_compras=${fecha}`);
        if(!res.ok) throw {status : res.status, statusText : res.statusText};
        const data = await res.json();
        return data.success;
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

$fecha_resumen_compras.addEventListener('change', async e => {
    const data_compras = await extraer_datos_compras($fecha_resumen_compras.value, url_compra);
    show_compras_disponibles(data_compras);
});

d.addEventListener('DOMContentLoaded',async e=> {

    //Insertamos la fecha actual al input date
    $fecha_resumen_compras.value = extraer_fecha_actual();

    //Extraemos los datos de las compras de la fecha seleccionada
    const data_compras = await extraer_datos_compras($fecha_resumen_compras.value, url_compra);
    //Mostramos las compras
    show_compras_disponibles(data_compras);
});