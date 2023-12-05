<?php 

    require_once "./conexion.php";
    include "../controllers/validaciones.php";
    $conexion = conexion();

    if(isset($_GET['extraer_data_bancos'])){
        global $conexion;

        try{

            $data = mysqli_query($conexion,"SELECT * FROM bancos");

            if(mysqli_num_rows($data) > 0){
                $data = mysqli_fetch_all($data,MYSQLI_ASSOC);
                echo json_encode(['success' => $data]);
                exit();
            }else{
                echo json_encode(['success' => []]);
            }

        }catch(mysqli_sql_exception $error){
            echo json_encode(["error" => "Ha ocurrido un error durante la busqueda".$error]);
        }
    }

    function registrar_bancos($codigo,$nombre){
        global $conexion;

        //Guardamos los datos en la base de datos
        try{

            $data = mysqli_query($conexion,"INSERT INTO bancos 
            (
                codigo_banco,
                nombre_banco
            )
            VALUES 
            (
                '$codigo',
                '$nombre'
            )"
            );
            return $data;
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }

    }

    if(isset($_GET['registrar_bancos'])){

        $data = json_decode(file_get_contents('php://input'));

        
        
    }