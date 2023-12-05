<?php

    require_once "conexion.php";
    include "../controllers/validaciones.php";
    include "./login.php";

    $conexion = conexion();

    //Consultar los datos de los administradores
    if(isset($_GET["consultar_todos_administradores"])){
        global $conexion;
        try{
            $datos = mysqli_query($conexion, "SELECT * FROM administrador");
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                echo json_encode(["success" => $datos]);
                exit();
            }else{
                echo json_encode(["success" => 0]);
            }
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
        
    }

    //Consultar los datos de un administradores especifico por medio del id de administrador
    if(isset($_GET["consultar_administrador"])){
        global $conexion;
        $id_administrador = $_GET['consultar_administrador'];

        if(!validarExpresion($id_administrador)){
            echo json_encode(['success' => ['Formato del dato de busqueda incorrecto','']]);
            return;
        }

        try {
            $datos = mysqli_query($conexion, "SELECT * FROM administrador WHERE id_usuario = '$id_administrador'");
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                echo json_encode(["success" => $datos]);
                exit();
            }else{
                echo json_encode(["success" => 0]);
            }
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Consultar los datos de un administradores especifico por medio del usuario de administrador
    if(isset($_GET["consultar_administrador_por_usuario"])){
        global $conexion;
        $usuario_administrador = $_GET['consultar_administrador_por_usuario'];

        if(!validar_usuario($usuario_administrador)){
            echo json_encode(['success' => ['Formato del dato de busqueda incorrecto','searchAdmin']]);
            return;
        }

        try {
            $datos = mysqli_query($conexion, "SELECT * FROM administrador WHERE usuario = '$usuario_administrador'");
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                echo json_encode(["success" => $datos]);
                exit();
            }else{
                echo json_encode(["success" => 0]);
            }
        } catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Consultar para validar la existencia de un administrador
    if(isset($_GET["validar_usuario"])){
        global $conexion;
        $usuario_administrador = $_GET['validar_usuario'];

        if(!validar_usuario($usuario_administrador)){
            echo json_encode(['success' => ['Formato del dato de busqueda incorrecto','UserNameAdmin']]);
            return;
        }

       try {
            $datos = mysqli_query($conexion, "SELECT * FROM administrador WHERE usuario = '$usuario_administrador'");
            if(mysqli_num_rows($datos) > 0){
                echo json_encode(["success" => 1]);
                exit();
            }else{
                echo json_encode(["success" => 0]);
            }
       } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
       }
    }

    //Registrar datos de administradores
    if(isset($_GET["RegistrarAdministrador"])){
        global $conexion;

        $data = json_decode(file_get_contents("php://input"));
        $nombre = $data->name;
        $apellido = $data->last_name;
        $usuario = $data->user_name;
        $contrasenia = $data->password;
        $primera_pregunta =  $data->primera_pregunta;
        $primera_respuesta = $data->primera_respuesta;
        $segunda_pregunta = $data->segunda_pregunta;
        $segunda_respuesta = $data->segunda_respuesta;
        $avatarSeleccionado = $data->avatarSeleccionado;
        $tipo_admin = $data->tipo_admin;

        //Validamos los datos

        $arrayInputs = array();

        array_push($arrayInputs,$nombre);
        array_push($arrayInputs,$apellido);
        array_push($arrayInputs,$usuario);
        array_push($arrayInputs,$contrasenia);
        array_push($arrayInputs,$primera_pregunta);
        array_push($arrayInputs,$primera_respuesta);
        array_push($arrayInputs,$segunda_pregunta);
        array_push($arrayInputs,$segunda_respuesta);
        array_push($arrayInputs,$tipo_admin);

         //Validamos los campos vacios
        if(!validarCamposVacios($arrayInputs)){
            echo json_encode(['success' => ['No debe dejar Campos Vacios','']]);
            return;
        }

        //Validamos nombre y apellido
        if(!validar_nombre_apellidos($nombre)){
            echo json_encode(['success' => ['Formato de Nombre de Administrador incorrecto','NameAdmin']]);
            return;
        }

        if(!validar_nombre_apellidos($apellido)){
            echo json_encode(['success' => ['Formato de Apellido de Administrador incorrecto','LastNameAdmin']]);
            return;
        }

        //Validamos usuario, contraseña, preguntas y respuestas de seguridad 
        if(!validar_usuario($usuario)){
            echo json_encode(['success' => ['Formato de Usuario de Administrador incorrecto','UserNameAdmin']]);
            return;
        }

        if(!validarExpresion($contrasenia)){
            echo json_encode(['success' => ['Formato de contraseña de Administrador incorrecto','passwordAdmin']]);
            return;
        }

        if(!validarExpresion($primera_pregunta)){
            echo json_encode(['success' => ['Formato de Pregunta de Seguridad incorrecto','one_question_security']]);
            return;
        }

        if(!validarExpresion($primera_respuesta)){
            echo json_encode(['success' => ['Formato de Respuesta de Seguridad incorrecto','one_answer_security']]);
            return;
        }

        if(!validarExpresion($segunda_pregunta)){
            echo json_encode(['success' => ['Formato de Pregunta de Seguridad incorrecto','two_question_security']]);
            return;
        }

        if(!validarExpresion($segunda_respuesta)){
            echo json_encode(['success' => ['Formato de Respuesta de Seguridad incorrecto','two_answer_security']]);
            return;
        }

        if(!validarNumeros($tipo_admin)){
            echo json_encode(['success' => ['Formato de Tipo de Administrador incorrecto','tipo_admin']]);
            return;
        }

        $encrypt = crypt($contrasenia, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

        try{
            $data = mysqli_query($conexion,"INSERT INTO administrador 
            (
                nombre,
                apellido,
                usuario,
                contrasenia,
                p_pregunta_seguridad,
                p_respuesta_seguridad,
                s_pregunta_seguridad,
                s_respuesta_seguridad,
                url_image,
                tipo_admin
            )
            VALUES 
            (
                '$nombre',
                '$apellido',
                '$usuario',
                '$encrypt',
                '$primera_pregunta',
                '$primera_respuesta',
                '$segunda_pregunta',
                '$segunda_respuesta',
                '$avatarSeleccionado',
                '$tipo_admin'
            )"
            );
    
            echo json_encode(["success" => 1]);
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Actualizar datos de un administrador especifico
    if(isset($_GET["Update_Administrador"])){
        global $conexion;

        $data = json_decode(file_get_contents("php://input"));
        $nombre = $data->name;
        $apellido = $data->last_name;
        $usuario = $data->user_name;
        $contrasenia = $data->password;
        $primera_pregunta =  $data->primera_pregunta;
        $primera_respuesta = $data->primera_respuesta;
        $segunda_pregunta = $data->segunda_pregunta;
        $segunda_respuesta = $data->segunda_respuesta;
        $id_administrador = $data->id_administrador;
        $avatarSeleccionado = $data->avatarSeleccionado;
        $tipo_admin = $data->tipo_admin;

        //Validamos los datos

        $arrayInputs = array();

        array_push($arrayInputs,$nombre);
        array_push($arrayInputs,$apellido);
        array_push($arrayInputs,$usuario);
        array_push($arrayInputs,$contrasenia);
        array_push($arrayInputs,$primera_pregunta);
        array_push($arrayInputs,$primera_respuesta);
        array_push($arrayInputs,$segunda_pregunta);
        array_push($arrayInputs,$segunda_respuesta);
        array_push($arrayInputs,$tipo_admin);

         //Validamos los campos vacios
        if(!validarCamposVacios($arrayInputs)){
            echo json_encode(['success' => ['No debe dejar Campos Vacios','']]);
            return;
        }

        //Validamos nombre y apellido
        if(!validar_nombre_apellidos($nombre)){
            echo json_encode(['success' => ['Formato de Nombre de Administrador incorrecto','NameAdmin']]);
            return;
        }

        if(!validar_nombre_apellidos($apellido)){
            echo json_encode(['success' => ['Formato de Apellido de Administrador incorrecto','LastNameAdmin']]);
            return;
        }

        //Validamos usuario, contraseña, preguntas y respuestas de seguridad 
        if(!validar_usuario($usuario)){
            echo json_encode(['success' => ['Formato de Usuario de Administrador incorrecto','UserNameAdmin']]);
            return;
        }

        // if(!validarExpresion($contrasenia)){
        //     echo json_encode(['success' => ['Formato de contraseña de Administrador incorrecto','passwordAdmin']]);
        //     return;
        // }

        if(!validarExpresion($primera_pregunta)){
            echo json_encode(['success' => ['Formato de Pregunta de Seguridad incorrecto','one_question_security']]);
            return;
        }

        if(!validarExpresion($primera_respuesta)){
            echo json_encode(['success' => ['Formato de Respuesta de Seguridad incorrecto','one_answer_security']]);
            return;
        }

        if(!validarExpresion($segunda_pregunta)){
            echo json_encode(['success' => ['Formato de Pregunta de Seguridad incorrecto','two_question_security']]);
            return;
        }

        if(!validarExpresion($segunda_respuesta)){
            echo json_encode(['success' => ['Formato de Respuesta de Seguridad incorrecto','two_answer_security']]);
            return;
        }

        if(!validarNumeros($tipo_admin)){
            echo json_encode(['success' => ['Formato de Tipo de Administrador incorrecto','tipo_admin']]);
            return;
        }

        //Realizamos una validacionm para comprobar si el usuario ha cambiado la clave
        $newPassword = "";

        $data_user = extraer_data_user($usuario);

        if($contrasenia === $data_user['contrasenia']){
            //En caso de que no haya cambiado se guarda el mismo valor
            $newPassword = $contrasenia;
        }else{
            //En se defecto, se encripta la nueva clave y se guarda en la base de datos
            $encrypt = crypt($contrasenia, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
            $newPassword = $encrypt;
        }
           
        try{
            $data = mysqli_query($conexion,"UPDATE administrador SET 

                nombre = '$nombre',
                apellido = '$apellido',
                usuario = '$usuario',
                contrasenia = '$newPassword',
                p_pregunta_seguridad = '$primera_pregunta',
                p_respuesta_seguridad = '$primera_respuesta',
                s_pregunta_seguridad = '$segunda_pregunta',
                s_respuesta_seguridad = '$segunda_respuesta',
                url_image = '$avatarSeleccionado',
                tipo_admin = '$tipo_admin'
                WHERE id_usuario = '$id_administrador'
                ");
            
            echo json_encode(["success" => 1]);
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    
    if(isset($_GET['eliminar_administrador'])){
        global $conexion;
        $id_administrador = $_GET['eliminar_administrador'];

        try{
            $data = mysqli_query($conexion,"DELETE FROM administrador WHERE id_usuario = '$id_administrador'");
            echo json_encode(["success" => 1]);
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Actualizar contraseña de un administrador especifico
    if(isset($_GET["Update_Password_Administrador"])){
        global $conexion;

        $data = json_decode(file_get_contents("php://input"));
        $contrasenia = $data->password;
        $usuario_administrador = $data->user;

        if(!validarExpresion($contrasenia)){
            echo json_encode(['success' => ['Formato de contraseña erroneo','password']]);
            return;
        }
           
        try{
            $data = mysqli_query($conexion,"UPDATE administrador SET 
                contrasenia = '$contrasenia' 
                WHERE usuario = '$usuario_administrador'");
    
            echo json_encode(["success" => 1]);
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    if(isset($_GET['consultar_administrador_por_nombre'])){
        global $conexion;

        $texto = $_GET['consultar_administrador_por_nombre'];

        try {
            $datos = mysqli_query($conexion, "SELECT * FROM administrador WHERE nombre LIKE '%".$texto."%'");

            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                echo json_encode(["success" => $datos]);
                exit();
            }else{
                echo json_encode(['success' => 0]);
            }
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }




    

    