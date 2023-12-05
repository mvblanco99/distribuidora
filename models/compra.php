<?php 

    require_once "./conexion.php";
    include "../controllers/validaciones.php";
    
    $conexion = conexion();

    // Agregar encabezados CORS
    header("Access-Control-Allow-Origin: http://inventario.local");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    //Obtener todas las compras (Tabla Compras)
    if(isset($_GET['obtener_todas_las_compras'])){
        try{
            $datos = mysqli_query($conexion, "SELECT * FROM compra");
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

    //Obtener los datos de una compra especifica (Tabla compra)
    if(isset($_GET['obtener_datos_compra_especifica'])){

        $id_compra = $_GET['obtener_datos_compra_especifica'];

         //Validamos el id de la compra
         if(!validarNumeros($id_compra)){
            echo json_encode(['success' => ['Formato de id de la compra incorrecto','']]);
            return;
        }

        try{
            $datos = mysqli_query($conexion, "SELECT * FROM compra WHERE id_compra = '$id_compra'");
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

    //Obtener los datos de una compra especifica (tabla compra_productos) 
    if(isset($_GET['obtener_datos_compra_productos'])){
        global $conexion;
        $id_compra = $_GET['obtener_datos_compra_productos'];

        //Validamos el id de la compra
        if(!validarNumeros($id_compra)){
            echo json_encode(['success' => ['Formato de id de la compra incorrecto','']]);
            return;
        }

        try{
            $datos = mysqli_query($conexion, "SELECT * FROM compra_productos WHERE id_compra = '$id_compra'");
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

    

    //BUSQUEDAS

    //Obtener los datos de una compra especifica con estatus disponible
    if(isset($_GET['obtener_compras_con_estatus_disponibles'])){

        global $conexion;

        try{
            $datos = mysqli_query($conexion, "SELECT * FROM compra WHERE seleccionado = 0");
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                echo json_encode(["success" => $datos]);
                exit();
            }else{
                echo json_encode(["success" => []]);
            }
        }catch(mysqli_sql_exception $error){
                echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Obtener compras
    if(isset($_GET['obtener_compras'])){

        $fecha = $_GET['obtener_compras'];

        try{
            $datos = mysqli_query($conexion, "SELECT * FROM compra 
            WHERE fecha_entrada_compra LIKE '$fecha%'");
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

    if(isset($_GET['verificar_estatus_seleccionado'])){

        $id_compra = $_GET['verificar_estatus_seleccionado'];

        $estatus_seleccionado = verificar_estatus_seleccionado_compras($id_compra);

        echo json_encode(['success' => $estatus_seleccionado['seleccionado']]);
    }

    //Consulta para validar la existencia de un numero de factura
    if(isset($_GET["validar_numero_factura"])){
        global $conexion;
        $numero_factura = $_GET['validar_numero_factura'];

        //Validamos el numero de factura
        if(!validar_numero_factura($numero_factura)){
            echo json_encode(['success' => ['Formato de número de factura incorrecto','numero_factura']]);
            return;
        }

        try{
            $datos = mysqli_query($conexion, "SELECT * FROM compra WHERE numero_factura = '$numero_factura'");
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                echo json_encode(["success" => 1]);
                exit();
            }else{
                echo json_encode(["success" => 0]);
            }
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }    
    }

     //En el caso de que el usuario quiera modificar el numero de factura original, se verifica el nuevo numero de factura ingresado, con el fin de comprobar si este se encuentra registrado en otra compra.
    if(isset($_GET["validar_numero_factura_repetida"])){
        global $conexion;
        $numero_factura = $_GET['validar_numero_factura_repetida'];
        $id_compra = $_GET['id_compra'];

        //Validamos el numero de factura
        if(!validar_numero_factura($numero_factura)){
            echo json_encode(['success' => ['Formato de número de factura incorrecto','modificar_numero_factura']]);
            return;
        }

        try{
            $datos = mysqli_query($conexion, "SELECT * FROM compra WHERE numero_factura = '$numero_factura' AND id_compra <> '$id_compra';");
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                echo json_encode(["success" => 1]);
                exit();
            }else{
                echo json_encode(["success" => 0]);
            }
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }    
    }

    //Obtener id generado al registrar datos en la tabla compra por medio del numero de factura
    function obtener_id_compra($numero_factura){
        global $conexion;

        if($numero_factura != null){
            try{
                $datos = mysqli_query($conexion, "SELECT id_compra FROM compra WHERE numero_factura = '$numero_factura'");
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
    }

    function verificar_estatus_seleccionado_compras($id_compra){
        global $conexion;
        try{
            $datos = mysqli_query($conexion, "SELECT seleccionado FROM compra WHERE id_compra = '$id_compra'");
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                return $datos[0];
                exit();
            }else{
                return -1;
            }
        }catch(mysqli_sql_exception $error){
                echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //REGISTRO DE LA COMPRA

    //Registramos los datos de la compra (Tabla Compra)
    function registrar_compra($numero_factura,$numero_control,$nombre_proveedor,$precio_compra,$fecha_entrada_compra,$admin){
        global $conexion;
   
        try{
            $data = mysqli_query($conexion,"INSERT INTO compra 
            (
                numero_factura,
                numero_control,
                nombre_proveedor,
                precio_total_compra,
                fecha_entrada_compra,
                seleccionado,
                administrador
            )
            VALUES 
            (
                '$numero_factura',
                '$numero_control',
                '$nombre_proveedor',
                '$precio_compra',
                '$fecha_entrada_compra',
                0,
                '$admin'
            )"
            );
            return $data;
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }   
    }

    //Recibimos los datos para la tabla compra (Tabla Compra)
    if(isset($_GET['recibir_datos_compra'])){

        $data = json_decode(file_get_contents('php://input'));
        $numero_factura = $data->numero_factura;
        $numero_control = $data->numero_control;
        $nombre_proveedor = $data->nombre_proveedor;
        $precio_compra = $data->precio_compra;
        $fecha_compra = $data->fecha_compra;
        $admin = $data->admin;

        //Validamos el numero de factura
        if(!validar_numero_factura($numero_factura)){
            echo json_encode(['success' => ['Formato de número de factura incorrecto','numero_factura']]);
            return;
        }

        //Validamos el numero de control
        if(!validar_numero_control($numero_control)){
            echo json_encode(['success' => ['Formato de número de control incorrecto','numero_control']]);
            return;
        }

        //Validamos nombre de proveedor
        if(!validarExpresion($nombre_proveedor)){
            echo json_encode(['success' => ['Formato de nombre de proveedor incorrecto','nombre_proveedor']]);
            return;
        }

        //Validamos el precio total de la compra
        if(!validarCantidadConDecimales($precio_compra)){
            echo json_encode(['success' => ['Formato de precio total incorrecto','precio_compra']]);
            return;
        }

        //Registramos la compra
        $is_registro_compra = registrar_compra($numero_factura,$numero_control,$nombre_proveedor,$precio_compra,$fecha_compra,$admin);

        if($is_registro_compra){
            //Recuperamos el id de la compra generado, por medio del numero de factura
            $id_compra = obtener_id_compra($numero_factura);
            if($id_compra !== null){
                session_start();
                $_SESSION['compra'] = array();
                array_push($_SESSION['compra'], $id_compra);
                array_push($_SESSION['compra'], $numero_factura);
                echo json_encode(['success' => 1]);
            }else{
                echo json_encode(['success' => "No se encontro el id de la compra"]);
            }   
        }else{
            echo json_encode(['success' => 0]);
        } 
    }

    //Registramos los datos de la compra (Tabla Compra-productos)
    function registrar_compra_productos($id_producto,$precio,$cantidad,$id_compra){
        global $conexion;

        try{
            $data = mysqli_query($conexion,"INSERT INTO compra_productos 
                (
                    id_compra,
                    productos,
                    cantidad_productos,
                    precio
                )
            VALUES 
                (
                    '$id_compra',
                    '$id_producto',
                    '$cantidad',
                    '$precio'
                )"
            );
    
            return $data;

        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Recibimos los datos para la tabla compra-productos (Tabla compra productos)
    if(isset($_GET['recibir_datos_compra_producto'])){

        $data = json_decode(file_get_contents('php://input'));
        $productos_seleccionados = $data->productos_seleccionados;
        $id_compra = $data->id_compra->id_compra;

        //Validamos el id de la compra
        if(!validarNumeros($id_compra)){
            echo json_encode(['success' => ['Formato del id de la Compra incorrecto','']]);
            return;
        }

        //Validamos la cantidad, precio y el id del producto
        for ($i=0; $i < count($productos_seleccionados); $i++) { 
            
            if(!validarNumeros($productos_seleccionados[$i]->cantidad)){
                echo json_encode(['success' => ['Formato de Cantidad de Producto incorrecto',"$i",$productos_seleccionados[$i],'cantidad']]);
                return;
            }

            if(!validarNumeros($productos_seleccionados[$i]->precio)){
                echo json_encode(['success' => ['Formato de Precio incorrecto',"$i",$productos_seleccionados[$i],'precio']]);
                return;
            }

            if(!validarNumeros($productos_seleccionados[$i]->id_producto)){
                echo json_encode(['success' => ['Formato del id del Producto incorrecto',"$i",$productos_seleccionados[$i],'codigo_producto']]);
                return;
            }
        }
        
        $registro = false;

        if(count($productos_seleccionados) > 0){
            
            for($i = 0; $i < count($productos_seleccionados); $i++){
                $cantidad = $productos_seleccionados[$i]->cantidad;
                $precio = $productos_seleccionados[$i]->precio;
                $id_producto = $productos_seleccionados[$i]->id_producto;
                $registro = registrar_compra_productos($id_producto,$precio,$cantidad, $id_compra);
                //Hacemos una verificacion cada vez que se haga un registro
                if(!$registro){
                    echo json_encode(['success' => 'Ocurrio un error durante el registro']);
                    return;
                } 
            }
            
            if($registro){
                echo json_encode(["success" => 1]);
            }else{
                echo json_encode(["success" => 0]);
            }
        }
    }

    //Recibimos los datos para la tabla compra-productos (Tabla compra productos)
    if(isset($_GET['recibir_datos_compra_producto_modificar'])){

        $data = json_decode(file_get_contents('php://input'));
        $productos_seleccionados = $data->productos_seleccionados;
        $id_compra = $data->id_compra;

        //Validamos el id de la compra
        if(!validarNumeros($id_compra)){
            echo json_encode(['success' => ['Formato del id de la Compra incorrecto','']]);
            return;
        }

        //Validamos la cantidad, precio y el id del producto
        for ($i=0; $i < count($productos_seleccionados); $i++) { 
            
            if(!validarNumeros($productos_seleccionados[$i]->cantidad)){
                echo json_encode(['success' => ['Formato de Cantidad de Producto incorrecto',"$i",$productos_seleccionados[$i],'cantidad']]);
                return;
            }

            if(!validarNumeros($productos_seleccionados[$i]->precio)){
                echo json_encode(['success' => ['Formato de Precio incorrecto',"$i",$productos_seleccionados[$i],'precio']]);
                return;
            }

            if(!validarNumeros($productos_seleccionados[$i]->id_producto)){
                echo json_encode(['success' => ['Formato del id del Producto incorrecto',"$i",$productos_seleccionados[$i],'codigo_producto']]);
                return;
            }
        }
        
        $registro = false;

        if(count($productos_seleccionados) > 0){
            
            for($i = 0; $i < count($productos_seleccionados); $i++){
                $cantidad = $productos_seleccionados[$i]->cantidad;
                $precio = $productos_seleccionados[$i]->precio;
                $id_producto = $productos_seleccionados[$i]->id_producto;
                $registro = registrar_compra_productos($id_producto,$precio,$cantidad, $id_compra);
                //Hacemos una verificacion cada vez que se haga un registro
                if(!$registro){
                    echo json_encode(['success' => 'Ocurrio un error durante el registro']);
                    return;
                } 
            }
            
            if($registro){
                echo json_encode(["success" => 1]);
            }else{
                echo json_encode(["success" => 0]);
            }
        }
    }

    function verificar_existencia_productos_tabla_compra_productos($id_compra){
        global $conexion;

        try{
            $datos = mysqli_query($conexion, "SELECT * FROM compra_productos WHERE id_compra = '$id_compra'");
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                return 1;
                exit();
            }else{
                return 0;
            }
        }catch(mysqli_sql_exception $error){
                echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //MODIFICACIONES

    //Modificamos los datos de la compra (Tabla Compra)
    if(isset($_GET['modificar_datos_compra'])){
        global $conexion;

        $data = json_decode(file_get_contents('php://input'));
        $numero_factura = $data->numero_factura;
        $numero_control = $data->numero_control;
        $nombre_proveedor = $data->nombre_proveedor;
        $precio = $data->precio_compra;
        $fecha = $data->fecha_compra;
        $id_compra = $data->id_compra;

        //Validamos el id de la compra
        if(!validarNumeros($id_compra)){
            echo json_encode(['success' => ['Formato del id de la Compra incorrecto','']]);
            return;
        }

        //Validamos el numero de factura
        if(!validar_numero_factura($numero_factura)){
            echo json_encode(['success' => ['Formato de número de factura incorrecto','modificar_numero_factura']]);
            return;
        }

        //Validamos el numero de control
        if(!validar_numero_control($numero_control)){
            echo json_encode(['success' => ['Formato de número de control incorrecto','modificar_numero_control']]);
            return;
        }

        //Validamos nombre de proveedor
        if(!validarExpresion($nombre_proveedor)){
            echo json_encode(['success' => ['Formato de nombre de proveedor incorrecto','modificar_nombre_proveedor']]);
            return;
        }

        //Validamos el precio total de la compra
        if(!validarCantidadConDecimales($precio)){
            echo json_encode(['success' => ['Formato de precio total incorrecto','modificar_precio_compra']]);
            return;
        }

        try{

            $data = mysqli_query($conexion,"UPDATE compra SET  
                numero_factura = '$numero_factura',
                numero_control = '$numero_control',
                nombre_proveedor = '$nombre_proveedor',
                precio_total_compra = '$precio',
                fecha_entrada_compra = '$fecha'
                WHERE id_compra = '$id_compra' 
            ");
            echo json_encode(['success'  => 1]);
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => ["ha ocurrido un error ".$error,]]);
        }
    }

    //ELIMINAR DATOS DE LA COMPRA

    //Eliminamos datos de la tabla compra productos 
    function eliminar_datos_compra_productos($id_compra){
        global $conexion;

        try{
            $data = mysqli_query($conexion,"DELETE FROM compra_productos WHERE id_compra = '$id_compra'"); 
            return $data;
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Eliminamos la compra
    function eliminar_datos_compra($id_compra){
        global $conexion;

        try{
            $data = mysqli_query($conexion,"DELETE FROM compra WHERE  id_compra = '$id_compra'"); 
            return $data;
        }catch(mysqli_sql_exception $error){
                echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Recibimos los datos de la compra que queremos eliminar (Tabla compra y Tabla compra_productos)
    if(isset($_GET['recibimimos_datos_compra_eliminar'])){

        $id_compra = $_GET['recibimimos_datos_compra_eliminar'];

        //Verificamos si podemos borrar la compra

        $estatus_seleccionado = verificar_estatus_seleccionado_compras($id_compra);

        if($estatus_seleccionado['seleccionado'] == 1){
            echo json_encode(['success' => 2]);
            return;
        }

        //Eliminar toda la info relacionada al id de compra que se encuentra en la tabla de compra-productos
        $is_compra_productos_eliminado = eliminar_datos_compra_productos($id_compra);

        $is_compra_eliminado = "";

        if($is_compra_productos_eliminado){
            $is_compra_eliminado = eliminar_datos_compra($id_compra);
        }

        if($is_compra_eliminado){
            echo json_encode(['success' => 1]);
        }else{
            echo json_encode(['success' => 0]);
        }
    }

    //Recibimos los datos de la compra para eliminar datos de la tabla compra_productos
    if(isset($_GET['borrar_datos_tabla_compra_producto'])){

        $id_compra = $_GET['borrar_datos_tabla_compra_producto'];

        //Eliminar toda la info relacionada al id de compra que se encuentra en la tabla de compra-productos

        //Verificamos si el id de compra proporcionado tiene productos relacionados en la tabla compra productos
        $verificacion = verificar_existencia_productos_tabla_compra_productos($id_compra);

        if($verificacion == 1){

            /**
             * 
             * valores retornados
             * 0 = no se puedo eliminar
             * 1 = eliminado
             * 2 = no habia registros para eliminar
             * 
             */

            $is_compra_productos_eliminado = eliminar_datos_compra_productos($id_compra);

            if($is_compra_productos_eliminado){
                echo json_encode(['success' => 1]);
            }else{
                echo json_encode(['success' => 0]);
            }

        }else{
            echo json_encode(['success' => 2]);
        }
    }

