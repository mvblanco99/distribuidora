<?php 

    require_once "./conexion.php";
    include "../controllers/validaciones.php";
    $conexion = conexion();

    if(isset($_GET['extraer_categorias'])){
        global $conexion;

        try {
            $datos = mysqli_query($conexion, "SELECT *
                FROM categoria_productos
            ");

            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                echo json_encode(['success' => $datos]);
                exit();
            }else{
                return ['success' => 0];
            }
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    if(isset($_GET['verificar_nombre_categoria'])){
        global $conexion;

        $nombre_categoria = $_GET['verificar_nombre_categoria'];

        //validar Nombre Categoria
        if(!validar_nombre_apellidos($nombre_categoria)){
            echo json_encode(['success' => ['Formato del Nombre de Categoría incorrecto','NameCategory']]);
            return;
        }

        try {
            $datos = mysqli_query($conexion, "SELECT *
                FROM categoria_productos
                WHERE nombre_categoria = '$nombre_categoria'
            ");

            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                echo json_encode(["success" => 1]);
                exit();
            }else{
                echo json_encode(['success' => 0]);
            }
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    if(isset($_GET['registrar_categoria'])){

        $data = json_decode(file_get_contents('php://input'));

        $NameCategory = $data->NameCategory;
        $descriptionCategory = $data->descriptionCategory;

        $arrayInputs = array();
        array_push($arrayInputs, $NameCategory);
        array_push($arrayInputs, $descriptionCategory);

        //Validamos los campos vacios
        if(!validarCamposVacios($arrayInputs)){
            echo json_encode(['success' => ['No debe dejar Campos Vacios','']]);
            return;
        }

        //validar Nombre Categoria
        if(!validar_nombre_apellidos($NameCategory)){
            echo json_encode(['success' => ['Formato del Nombre de Categoría incorrecto','NameCategory']]);
            return;
        }

        //validar Descripcion Categoria
        if(!validarExpresion($descriptionCategory)){
            echo json_encode(['success' => ['Formato de la descripción de la Categoría incorrecto','descriptionCategory']]);
            return;
        }

         //Guardamos los datos en la base de datos
        try{

            $data = mysqli_query($conexion,"INSERT INTO categoria_productos 
            (
                nombre_categoria,
                descripcion_categoria
            )
            VALUES 
            (
                '$NameCategory',
                '$descriptionCategory'
            )"
            );
            echo json_encode(['success'  => 1]);
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    if(isset($_GET['extraer_data_categoria'])){
        global $conexion;

        $id_categoria = $_GET['extraer_data_categoria'];

        try {
            $datos = mysqli_query($conexion, "SELECT *
                FROM categoria_productos
                WHERE id_categoria = '$id_categoria'
            ");

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

    if(isset($_GET['modificar_categoria'])){

        $data = json_decode(file_get_contents('php://input'));

        $NameCategory = $data->NameCategory;
        $descriptionCategory = $data->descriptionCategory;
        $id_categoria = $data->id_categoria;

        $arrayInputs = array();
        array_push($arrayInputs, $NameCategory);
        array_push($arrayInputs, $descriptionCategory);

        //Validamos los campos vacios
        if(!validarCamposVacios($arrayInputs)){
            echo json_encode(['success' => ['No debe dejar Campos Vacios','']]);
            return;
        }

        //validar Nombre Categoria
        if(!validar_nombre_apellidos($NameCategory)){
            echo json_encode(['success' => ['Formato del Nombre de Categoría incorrecto','NameCategory']]);
            return;
        }

        //validar Descripcion Categoria
        if(!validarExpresion($descriptionCategory)){
            echo json_encode(['success' => ['Formato de la descripción de la Categoría Incorrecto','descriptionCategory']]);
            return;
        }

         //Guardamos los datos en la base de datos
        try{

            $data = mysqli_query($conexion,"UPDATE categoria_productos SET  
                nombre_categoria = '$NameCategory',
                descripcion_categoria = '$descriptionCategory'
                WHERE id_categoria = '$id_categoria'"
            );

            echo json_encode(['success'  => 1]);
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    if(isset($_GET['buscar_registros'])){
        global $conexion;
        $texto = $_GET['buscar_registros'];

        try {
            $datos = mysqli_query($conexion, "SELECT * FROM categoria_productos WHERE nombre_categoria LIKE '%".$texto."%'");

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

    function verificar_uso_categoria($id_categoria){
        global $conexion;

        try {
            $datos = mysqli_query($conexion, "SELECT *
                FROM productos WHERE categoria = '$id_categoria';
            ");

            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                return true;
                exit();
            }else{
                return false;
            }
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    function eliminar_categoria($id_categoria){
        global $conexion;

        try {
            $datos = mysqli_query($conexion, "DELETE
                FROM categoria_productos 
                WHERE id_categoria = '$id_categoria';
            ");

            return $datos;
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    if(isset($_GET['eliminar_categoria'])){

        $id_categoria = $_GET['eliminar_categoria'];

        //Verificamos si podemos borrar la categoria
        $categoria_usada = verificar_uso_categoria($id_categoria);

        if($categoria_usada){
            echo json_encode(['success' => 2]);
            return;
        }

        $categoria_borrada = eliminar_categoria($id_categoria);

        if($categoria_borrada){
            echo json_encode(['success' => 1]);
        }else{
            echo json_encode(['success' => 0]);
        }
    }

    