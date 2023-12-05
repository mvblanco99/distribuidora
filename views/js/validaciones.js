import { alerta } from "./utils.js";

/**Validar datos tipo descripciones, nombre de productos donde se pueden utilizar letras, numeros,espacios y cantidad limitada de caracteres especiales */
export const validarExpresion = (data) => {

    const expression = /^[A-Za-z0-9áéíóúÁÉÍÓÚ_-ñ., ]*(\.?)[A-Za-z0-9áéíóúÁÉÍÓÚ_-ñ., ]+$/;
    if(!expression.test(data.value)){
        alerta({
            titulo : "¡Formato incorrecto!",
            mensaje : "Utilizar un formato que coincida con el solicitado", 
            tipo_mensaje : "error"
        });
        data.parentElement.classList.add('is-invalid');
        return false;
    }

    return true;

}

/**Validar user_name para administradores, clientes */
export const validar_usuario = (data) => {
    const expression = /^[A-Za-z0-9áéíóúÁÉÍÓÚ_-ñ]*(\.?)[A-Za-z0-9áéíóúÁÉÍÓÚ_-ñ]+$/;
    if(!expression.test(data.value)){
        alerta({
            titulo : "¡Formato incorrecto!",
            mensaje : "Utilizar un formato que coincida con el solicitado", 
            tipo_mensaje : "error"
        });
        data.parentElement.classList.add('is-invalid');
        return false;
    }

    return true;
}

/**Validar datos tipo nombre, apellido donde solo pueden utilizar letras, letras acentuadas*/
export const validarNombres_Apellidos = (data) => {
    const expression = /^[A-Za-záéíóúÁÉÍÓÚñ]*(\.?)[A-Za-záéíóúÁÉÍÓÚñ]+$/;
    if(!expression.test(data.value)){
        alerta({
            titulo : "¡Formato incorrecto!",
            mensaje : "Utilizar un formato que coincida con el solicitado", 
            tipo_mensaje : "error"
        });
        data.parentElement.classList.add('is-invalid');
        return false;
    }

    return true;
}

/**Validar numero de control para las facturas del modulo de compras */
export const validar_numero_control = (data) => {

    const expression = /^[0-9-]*(\.?)[0-9-]+$/;

    if(!expression.test(data.value)){
        alerta({
            titulo : "¡Formato incorrecto!",
            mensaje : "Utilizar un formato que coincida con el solicitado", 
            tipo_mensaje : "error"
        });
        data.parentElement.classList.add('is-invalid');
        return false;
    }

    return true;
}

/**Validar numero de factura para las facturas del modulo de compras */
export const validar_numero_factura = (data) => {

    const expression = /^[0-9A-Za-z]*(\.?)[0-9A-Za-z]+$/;

    if(!expression.test(data.value)){
        alerta({
            titulo : "¡Formato incorrecto!",
            mensaje : "Utilizar un formato que coincida con el solicitado", 
            tipo_mensaje : "error"
        });
        data.parentElement.classList.add('is-invalid');
        return false;
    }

    return true;
}

/**Solo numeros */
export const validarNumeros = (data, elementHtml = "") => {

    const expression = /^[0-9]*(\.?)[0-9]+$/;

    const comparacion = () => {
        if(elementHtml !==""){
            elementHtml.parentElement.classList.add('is-invalid');
        }else{
            data.parentElement.classList.add('is-invalid');
        }
    }

    //Validamos que el digito sea Positivo
    if(Number(data.value) < 0){
        alerta({
            titulo : "¡Números Negativos!",
            mensaje : "No se permiten números negativos", 
            tipo_mensaje : "error"
        });
        comparacion();
        return false;
    }

    if(!expression.test(data.value)){
        alerta({
            titulo : "¡Solo números!",
            mensaje : "Solo se permiten números", 
            tipo_mensaje : "error"
        });
        comparacion();
        return false;
    }

    return true;

}

export const validarCantidadConDecimales = (data, elementHtml = "") => {

    const expression = /^[0-9.]*(\.?)[0-9.]+$/;

    const comparacion = () => {
        if(elementHtml !==""){
            elementHtml.parentElement.classList.add('is-invalid');
        }else{
            data.parentElement.classList.add('is-invalid');
        }
    }

    //Validamos que el digito sea Positivo
    if(Number(data.value) < 0){
        alerta({
            titulo : "Cantidad Inválida",
            tipo_mensaje : "error"
        });
        comparacion();
        return false;
    }

    if(!expression.test(data.value)){
        alerta({
            titulo : "Cantidad Inválida",
            tipo_mensaje : "error"
        });
        comparacion();
        return false;
    }

    return true;

}

/**Validar campos vacios */
export const validarCamposVacios = (inputs) => {

    let verificado = true;

    //Validar campos vacios
    for (let index = 0; index < inputs.length; index++) {
        if(inputs[index].value  === ""){
            alerta({
                titulo:"¡Campos Vacios!",
                mensaje:"No Dejar campos vacios",
                tipo_mensaje:"warning",
                bool:true,
                callback : () => {
                    console.log("Hola no puedes dejar campos vacios, mi vida");
                }
            });
            inputs[index].parentElement.classList.add('is-invalid');
            verificado = false;
            break;
        }
        
    }

    return verificado;

}

/**Validamos imagen */
export const validarImagen = (imagen) => {

    if(imagen === undefined){
        alerta({
            titulo : "¡No hay Imagen!",
            mensaje : "Debe ingresar una imagen para el producto",
            tipo_mensaje : "warning"
        })
        return false;
    }

    if(imagen.type !== "image/png" && imagen.type !== "image/jpg" &&
    imagen.type !== "image/jpeg"){
        alerta({
            titulo : "¡Formato inválido!",
            mensaje : "Formato de imagen no válido",
            tipo_mensaje : "error"
        })
        return false;
    }

    if(imagen.size > 3670016){
                    
        alerta({
            titulo : "¡Imagen muy Pesada!",
            mensaje : "Imagen tiene que pesar menos de 3.5 mega bytes",
            tipo_mensaje : "warning"
        })
        return false;
    }

    return true;

}