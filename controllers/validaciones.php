<?php

//Validar numeros
function validarNumeros($data){

    if(!preg_match('/^[0-9]+$/', $data)){
        return false;
    }

    return true;
}

//Validar numeros
function validarCantidadConDecimales($data){

    if(!preg_match('/^[0-9.]+$/', $data)){
        return false;
    }

    return true;
}

//Validamos los campos vacios
function validarCamposVacios($data){

    $verificado = true;

    for ($i=0; $i < count($data); $i++) { 
        if($data[$i] === ""){
            $verificado = false;
            break;
        }
    }

    return $verificado;
}

/*Validar datos tipo descripciones donde se pueden utilizar letras, numeros,espacios y cantidad limitada de caracteres especiales */
function validarExpresion($data){

    if(!preg_match("/^[A-Za-z0-9áéíóúÁÉÍÓÚ_,`'ñ. ]+$/", $data)){
        return false;
    }

    return true;
}

/*Validar datos tipo nombre, apellido donde se pueden utilizar letras,letras acentuadas */
function validar_nombre_apellidos($data){

    if(!preg_match("/^[A-Za-záéíóúÁÉÍÓÚñ]+$/", $data)){
        return false;
    }

    return true;
}

/**Validar user_name para administradores, clientes */
function validar_usuario($data){

    if(!preg_match("/^[A-Za-z0-9áéíóúÁÉÍÓÚ_-ñ]+$/", $data)){
        return false;
    }

    return true;
}

//Validar numero de factura
function validar_numero_factura($data){

    if(!preg_match("/^[[0-9A-Za-z]+$/", $data)){
        return false;
    }

    return true;
}

//Validar numero de control
function validar_numero_control($data){

    if(!preg_match("/^[0-9-]+$/", $data)){
        return false;
    }

    return true;
}


