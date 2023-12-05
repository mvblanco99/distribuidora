<?php

    $conexion = null;

    function conexion() {
    
        global $conexion;
        if($conexion === null){

            // Creamos la conexion y la retornamos
            $servidor = "localhost"; 
            $usuario = "root"; 
            $pass = ""; 
            $db_name = "licoreria";
            $conexion = new mysqli($servidor,$usuario,$pass,$db_name);  
            return $conexion;       
        
        }else{

            // Si la conexion esta creada se retorna
            return $conexion;
        }
    }