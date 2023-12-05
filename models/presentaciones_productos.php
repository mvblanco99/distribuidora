<?php 

    require_once "./conexion.php";
    include "../controllers/validaciones.php";
    $conexion = conexion();

    if(isset($_GET['extraer_presentaciones_productos'])){
        global $conexion;

        try {
            $datos = mysqli_query($conexion, "SELECT *
                FROM presentacion_producto
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