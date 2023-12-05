<?php 

    require_once "./conexion.php";
    include "../controllers/validaciones.php";
    
    $conexion = conexion();

    /*
        funcion permite registrar los datos en la tabla venta, recibe como parametros: 
        monto total de la venta, 
        fecha de la venta,  
        administrador (que realizo el registro de la venta) 
    */
    function registrar_datos_tabla_venta($monto_total_venta,$fecha_venta,$id_admin){
        global $conexion;

        try {
            $data = mysqli_query($conexion,"INSERT INTO ventas 
                (
                    precio_total_venta,
                    fecha_venta,
                    administrador
                )
                VALUES 
                (
                    '$monto_total_venta',
                    '$fecha_venta',
                    '$id_admin'
                )"
            );
            return $data;
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    /**
     * Funcion retorna el id generado de la ultima venta registrada.Recibe como parametro:
     * id del administrador (Quien esta realizando la venta)
     */
    function recuperar_id_venta_generado($id_admin){
        global $conexion;

        try {
            $datos = mysqli_query($conexion, "SELECT *
                FROM ventas
                WHERE administrador = '$id_admin'
                ORDER BY id_venta DESC
                LIMIT 1;");

            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                return $datos[0];
                exit();
            }else{
                return 0;
            }
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    /**
     * Funcion registra datos en tabla venta-productos de la base de datos, recibe como parametros:
     * Id de la ultima venta registrada, 
     * Id Producto asociado a la venta,  
     * Cantidad de dicho producto, 
     * retorna true en el caso de que el registro sea exitoso, en caso contrario retorna false.
     */
    function registrar_datos_tabla_venta_productos($id_venta,$producto,$cantidad,$precio_producto_al_vender,$gravado_exento_al_vender){
        global $conexion;

        try {
            $data = mysqli_query($conexion,"INSERT INTO venta_productos 
                (
                    id_venta,
                    productos,
                    cantidad_productos,
                    precio_producto_al_vender,
                    grabado_excento_al_vender
                )
                VALUES 
                (
                    '$id_venta',
                    '$producto',
                    '$cantidad',
                    '$precio_producto_al_vender',
                    '$gravado_exento_al_vender'
                )"
            );
            return $data;
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }


    if(isset($_GET['recibir_datos_venta_local'])){
        $data = json_decode(file_get_contents('php://input'));
        
        $monto_total_venta = $data->monto_total_venta;
        $fecha_venta = $data->fecha_venta;
        $id_admin = $data->id_admin;
        $productos = $data->productos_seleccionados;

        //Registarmos los datos en la tabla ventas
        $registro_venta = registrar_datos_tabla_venta($monto_total_venta,$fecha_venta,$id_admin);

        if($registro_venta){
           
            //Obtener el ultimo id de la venta registrado
           $id_venta = recuperar_id_venta_generado($id_admin);

           //Registramos los datos en la tabla de ventas productos

           $registro = false;

           for ($i=0; $i < count($productos) ; $i++) { 
                
            $registro = false;

                $registro = registrar_datos_tabla_venta_productos(
                    $id_venta['id_venta'],
                    $productos[$i]->id_producto,
                    $productos[$i]->cantidad,
                    $productos[$i]->precio,
                    $productos[$i]->grabado_excento
                );

                if(!$registro){
                    break;
                } 
            }

            if($registro){
                echo json_encode(['success' => 1]);
            }else{
                echo json_encode(['success' => 0]);
            }

        }else{
            echo json_encode(['success' => 0]);
        }  
    }