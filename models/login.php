<?php

require_once "conexion.php";

$conexion = conexion();

function extraer_data_user($usuario){
    global $conexion;
    $res = mysqli_query($conexion, "SELECT * FROM administrador WHERE usuario = '$usuario'");
    $id_user = mysqli_fetch_assoc($res);
    return $id_user;
}

function verificarDatosUsuario($usuario, $pass){
    global $conexion;
    $res = mysqli_query($conexion, "SELECT * FROM administrador WHERE usuario = '$usuario' AND
    contrasenia = '$pass'");
    $id_user = mysqli_fetch_assoc($res);
    return $id_user;
}

function getUsuario($usuario){
    global $conexion;
    $res = mysqli_query($conexion, "SELECT nombre, apellido FROM administrador WHERE usuario = '$usuario'");
    
    $nombres = mysqli_fetch_assoc($res);
    return $nombres;
}