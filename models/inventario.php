<?php

    require_once "./conexion.php";
    $conexion = conexion();

    function extraer_cantidades_productos($id_compra){
        global $conexion;

        try{
            $datos = mysqli_query($conexion, "SELECT productos, cantidad_productos FROM compra_productos WHERE id_compra = '$id_compra'");
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                return $datos;
                exit();
            }else{
                return 0;
            }
        }catch(mysqli_sql_exception $error){
                echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    if(isset($_GET['obtener_cantidades_productos'])){

        $data = json_decode(file_get_contents('php://input'));
        $id_compras = $data->id_compras;

        $cantidades_productos = array();

        //Extraemos las cantidades de los productos relacionados a cada uno de los id de compras 
        for ($i=0; $i < count($id_compras) ; $i++) { 
            array_push(
                $cantidades_productos, 
                [extraer_cantidades_productos($id_compras[$i]),
                $id_compras[$i]]
            );
        }
        //Devolvemos las cantidades
        echo json_encode($cantidades_productos);
    }

    function actualizar_estado_compra($id_compra){
        global $conexion;

        try{
            $data = mysqli_query($conexion,"UPDATE compra SET  
                seleccionado = 1
                WHERE id_compra = '$id_compra' 
            ");
            return $data;
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Actualizar estado de compra
    if(isset($_GET['actualizar_estado_compra'])){
        $data = json_decode(file_get_contents('php://input'));
        $ids_compras = $data->ids_compras;

        $update = false;

        //Actualizamos el estado de cada una de las compras
        for ($i=0; $i < count($ids_compras) ; $i++) { 
            $update = actualizar_estado_compra(
                $ids_compras[$i]
            );
        }

        if($update){
            echo json_encode(["success" => 1]);
        }else{
            echo json_encode(["success" => 0]);
        }
    }