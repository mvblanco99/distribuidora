<?php

    require_once "conexion.php";
    include "../controllers/validaciones.php";

    $conexion = conexion();

    //Consultar los datos de los productos (En uso)
    if(isset($_GET["consultar_todos_productos"])){
        global $conexion;
        try{
            $datos = mysqli_query($conexion, "SELECT * FROM productos
            INNER JOIN presentacion_producto
            on productos.presentacion = presentacion_producto.id_presentacion
            INNER JOIN categoria_productos
            on productos.categoria_productos = categoria_productos.id_categoria");
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                echo json_encode(["success"=>$datos]);
                exit();
            }else{
                echo json_encode(["success" => 0]);
            }
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
        
    }

     //Consultar los datos de un producto especifico por medio del id de producto (En uso)
     if(isset($_GET["consultar_producto"])){
        global $conexion;
        $id_producto = $_GET['consultar_producto'];

        if(!validarNumeros($id_producto)){
            echo json_encode(['success' => ['Formato del dato de busqueda incorrecto','']]);
            return;
        }

        try {
            $datos = mysqli_query($conexion, "SELECT * FROM productos WHERE id_producto = '$id_producto'");
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                echo json_encode(["success" =>$datos]);
                exit();
            }else{
                echo json_encode(["success" => 0]);
            }
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Consultar los datos de un producto especifico por medio del codigo de producto (En uso)
    if(isset($_GET["consultar_producto_por_codigo"])){
        global $conexion;
        $cod_producto = $_GET['consultar_producto_por_codigo'];

        if(!validarNumeros($cod_producto)){
            echo json_encode(['success' => ['Formato del dato de busqueda incorrecto','searchProduct']]);
            return;
        }

        try{
            $datos = mysqli_query($conexion, "SELECT * FROM productos WHERE codigo_producto = '$cod_producto'");
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

    //Consultar los datos de los productos por nombre (En uso)
    if(isset($_GET['consultar_producto_por_nombre'])){
        global $conexion;

        $texto = $_GET['consultar_producto_por_nombre'];

        try {
            $datos = mysqli_query($conexion, "SELECT * FROM productos 
            INNER JOIN presentacion_producto
            on productos.presentacion = presentacion_producto.id_presentacion
            INNER JOIN categoria_productos
            on productos.categoria_productos = categoria_productos.id_categoria
            WHERE nombre_producto LIKE '%".$texto."%'");

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

    //Consultar los datos de los productos por nombre (En uso)
    if(isset($_GET['consultar_producto_por_categoria'])){
        global $conexion;

        $categoria = $_GET['consultar_producto_por_categoria'];

        try {
            $datos = mysqli_query($conexion, "SELECT * FROM productos 
            INNER JOIN presentacion_producto
            on productos.presentacion = presentacion_producto.id_presentacion
            INNER JOIN categoria_productos
            on productos.categoria_productos = categoria_productos.id_categoria
            WHERE productos.categoria_productos = '$categoria'");

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

    //Consultar los datos de los productos por nombre y categoria (En uso)
    if(isset($_GET['consultar_producto_por_nombre_and_categoria'])){
        global $conexion;

        $categoria = $_GET['consultar_producto_por_nombre_and_categoria'];

        $id_categoria = "";
        $nombre_producto = "";
        $coma_encontrada = false;

        $cantidad_caracteres = strlen($categoria);
  
        for ($i=$cantidad_caracteres; $i>0; $i--) {

            if($categoria[$i-1] === ","){
                $coma_encontrada = true;
            }else{

                if(!$coma_encontrada){
                    $id_categoria .= $categoria[$i-1];
                }else{
                    $nombre_producto .= $categoria[$i-1];
                }
            }
        }

        $nombre_producto = strrev($nombre_producto);

        try {
            $datos = mysqli_query($conexion, "SELECT * FROM productos 
            INNER JOIN presentacion_producto
            on productos.presentacion = presentacion_producto.id_presentacion
            INNER JOIN categoria_productos
            on productos.categoria_productos = categoria_productos.id_categoria
            WHERE productos.categoria_productos = '$id_categoria' AND nombre_producto LIKE '%".$nombre_producto."%'");

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

    //Consultar para validar la existencia de un producto (En uso)
    if(isset($_GET["validar_producto"])){
        global $conexion;
        $codigo_producto = $_GET['validar_producto'];

        if(!validarExpresion($codigo_producto)){
            echo json_encode(['success' => ['Formato del dato de busqueda incorrecto','BarCode']]);
            return;
        }

        try{
            $datos = mysqli_query($conexion, "SELECT * FROM productos WHERE codigo_producto = '$codigo_producto'");
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
        
    /**Funcion personalizada de buscar productos por medio del id_producto */
    function consultar_datos_productos($id_producto){
        global $conexion;
        
        try{
            $datos = mysqli_query($conexion, "SELECT * FROM productos 
            INNER JOIN presentacion_producto
            on productos.presentacion = presentacion_producto.id_presentacion
            INNER JOIN categoria_productos
            on productos.categoria_productos = categoria_productos.id_categoria
            WHERE id_producto = '$id_producto'");
            
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                return($datos[0]);
                exit();
            }else{
                return $datos;
            }

        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    if(isset($_GET['consultar_datos_productos'])){

        $data = json_decode(file_get_contents('php://input'));
        $id_productos = $data->data;

        $datos_productos = array();

        for($i = 0; $i < count($id_productos); $i++){
            array_push($datos_productos, consultar_datos_productos($id_productos[$i]));
        }

        echo json_encode(["success" => $datos_productos]);
    }

    function modificar_cantidad_disponible_producto($id_producto,$cantidad_disponible){
        global $conexion;

        if($id_producto != ""){

            try{
                $data = mysqli_query($conexion,"UPDATE productos SET  
                    cantidad_disponible = '$cantidad_disponible'
                    WHERE id_producto = '$id_producto' 
                ");
                return $data;
            }catch(mysqli_sql_exception $error){
                echo json_encode(["success" => "ha ocurrido un error ".$error]);
            }

        }else{
            echo json_encode(['success' => 0]);
        }

    }

    //Modificar la cantidad disponible de los Productos (ventas) (USADA EN INVENTARIO)
    if(isset($_GET['modificar_cantidad_disponible_producto'])){

        $data = json_decode(file_get_contents("php://input"));
        $datos_productos =  $data->datos_productos;
        
        $registro = false;

        for ($i=0; $i < count($datos_productos); $i++) { 
            $registro = modificar_cantidad_disponible_producto(
                $datos_productos[$i]->id_producto,
                $datos_productos[$i]->cantidad_disponible
            );
        }
        
        if($registro){
            echo json_encode(["success" => 1]);
        }else{
            echo json_encode(["success" => 0]);
        }
    }

    function modificar_cantidad_disponible_producto_pedido($id_producto,$cantidad_disponible,$cantidad_apartada){
        global $conexion;

        if($id_producto != ""){

            try{
                $data = mysqli_query($conexion,"UPDATE productos SET  
                    cantidad_disponible = '$cantidad_disponible',
                    cantidad_apartada = '$cantidad_apartada'
                    WHERE id_producto = '$id_producto' 
                ");
                return $data;
            }catch(mysqli_sql_exception $error){
                echo json_encode(["success" => "ha ocurrido un error ".$error]);
            }

        }else{
            echo json_encode(['success' => 0]);
        }

    }

    //Modificar la cantidad disponible de los Productos (ventas_pedido) 
    if(isset($_GET['modificar_cantidad_disponible_producto_pedido'])){

        $data = json_decode(file_get_contents("php://input"));
        $datos_productos =  $data->datos_productos;
        
        $registro = false;

        for ($i=0; $i < count($datos_productos); $i++) { 
            $registro = modificar_cantidad_disponible_producto_pedido(
                $datos_productos[$i]->id_producto,
                $datos_productos[$i]->cantidad_disponible,
                $datos_productos[$i]->cantidad_apartada
            );
        }
        
        if($registro){
            echo json_encode(["success" => 1]);
        }else{
            echo json_encode(["success" => 0]);
        }
    }

    //Funcion utilizada en la seccion de venta_pedido para actualizar la cantidad_apartada y cantidad disponible
    function actualizar_cantidad_apartada($cantidad_requerida,$id_producto){
        global $conexion;
        try {
            $datos = mysqli_query($conexion, "UPDATE productos SET
                cantidad_apartada = cantidad_apartada - $cantidad_requerida,
                cantidad_disponible = cantidad_disponible - $cantidad_requerida
                WHERE id_producto = $id_producto;");

            return $datos;
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }
    
    if(isset($_GET['actualizar_cantidades'])){

        $data = json_decode(file_get_contents('php://input'));
        $is_actualizado = false;

        for ($i=0; $i < count($data) ; $i++) { 
            $is_actualizado = actualizar_cantidad_apartada($data[$i]->cantidad, $data[$i]->id_producto);
        }

        if($is_actualizado){
            echo json_encode(['success' => 1]);
        }
        
    }

    //Registrar Producto (En uso)
    if(isset($_GET['registrar_producto'])){

        // Obtener los datos del formulario
        $codigo_producto = $_POST['BarCode'];
        $nombre_producto = $_POST['NameProduct'];
        $contenido_neto = $_POST['contenidoNeto'];
        $tipo = $_POST['tipo'];
        $categoria = $_POST['categoria'];
        $presentacion_producto = $_POST['presentacion'];
        $precio = $_POST['precio'];

        $arrayInputs = array();

        array_push($arrayInputs,$codigo_producto);
        array_push($arrayInputs,$nombre_producto);
        array_push($arrayInputs,$contenido_neto);
        array_push($arrayInputs,$tipo);
        array_push($arrayInputs,$categoria);
        array_push($arrayInputs,$presentacion_producto);
        array_push($arrayInputs,$precio);
                  

        //Validamos los campos vacios
        if(!validarCamposVacios($arrayInputs)){
            echo json_encode(['success' => ['No debe dejar Campos Vacíos','']]);
            return;
        }

        //Verificamos que el codigo de producto, precio, tipo, categoria contengan solo numeros (Positivos)
        if(!validarNumeros($codigo_producto)){
            echo json_encode(['success' => ['Formato de Código de Producto incorrecto','BarCode']]);
            return;
        }

        if(!validarExpresion($nombre_producto)){
            echo json_encode(['success' => ['Formato de nombre de Producto incorrecto','NameProduct']]);
            return;
        }
        
        if(!validarCantidadConDecimales($contenido_neto)){
            echo json_encode(['success' => ['Formato de contenido neto incorrecto','contenidoNeto']]);
            return;
        }

        if(!validarNumeros($tipo)){
            echo json_encode(['success' => ['Formato de tipo incorrecto','tipo']]);
            return;
        }


        if(!validarNumeros($categoria)){
            echo json_encode(['success' => ['Formato de tipo incorrecto','categoria']]);
            return;
        }

        if(!validarNumeros($presentacion_producto)){
            echo json_encode(['success' => ['Formato de presentacion incorrecto','presentacion']]);
            return;
        }

        if(!validarCantidadConDecimales($precio)){
            echo json_encode(['success' => ['Formato de precio incorrecto','precio']]);
            return;
        }

        //Guardamos los datos en la base de datos
        try{

            $data = mysqli_query($conexion,"INSERT INTO productos 
            (
                codigo_producto,
                nombre_producto,
                contenido_neto,
                presentacion,
                grabado_excento,
                categoria_productos,
                precio
            )
            VALUES 
            (
                '$codigo_producto',
                '$nombre_producto',
                '$contenido_neto',
                '$presentacion_producto',
                '$tipo',
                '$categoria',
                '$precio'
            )"
            );
            echo json_encode(['success'  => 1]);
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Modificar Producto (En uso)
    if(isset($_GET['modificar_producto'])){
       
        // Obtener los datos del formulario
        $codigo_producto = $_POST['updateBarCode'];
        $nombre_producto = $_POST['updateNameProduct'];
        $contenido_neto = $_POST['update_contenidoNeto'];
        $presentacion_producto = $_POST['update_presentacion'];
        $tipo = $_POST['update_tipo'];
        $categoria = $_POST['update_categoria'];
        $precio = $_POST['update_precio'];
        
        $arrayInputs = array();

        array_push($arrayInputs,$codigo_producto);
        array_push($arrayInputs,$nombre_producto);
        array_push($arrayInputs,$contenido_neto);
        array_push($arrayInputs,$tipo);
        array_push($arrayInputs,$presentacion_producto);
        array_push($arrayInputs,$categoria);
        array_push($arrayInputs,$precio);

        //Validamos los campos vacios
        if(!validarCamposVacios($arrayInputs)){
            echo json_encode(['success' => ['No debe dejar Campos Vacíos','']]);
            return;
        }

        //Verificamos que el codigo de producto, precio, tipo, categoria contengan solo numeros (Positivos)
        if(!validarNumeros($codigo_producto)){
            echo json_encode(['success' => ['Formato de Código de Producto incorrecto','BarCode']]);
            return;
        }

        if(!validarExpresion($nombre_producto)){
            echo json_encode(['success' => ['Formato de nombre de Producto incorrecto','NameProduct']]);
            return;
        }
        
        if(!validarCantidadConDecimales($contenido_neto)){
            echo json_encode(['success' => ['Formato de contenido neto incorrecto','contenidoNeto']]);
            return;
        }

        if(!validarNumeros($tipo)){
            echo json_encode(['success' => ['Formato de tipo incorrecto','tipo']]);
            return;
        }

        if(!validarNumeros($categoria)){
            echo json_encode(['success' => ['Formato de tipo incorrecto','categoria']]);
            return;
        }

        if(!validarNumeros($presentacion_producto)){
            echo json_encode(['success' => ['Formato de presentacion incorrecto','presentacion']]);
            return;
        }

        if(!validarCantidadConDecimales($precio)){
            echo json_encode(['success' => ['Formato de precio incorrecto','precio']]);
            return;
        }

        //Guardamos los datos en la base de datos
        try{

            $data = mysqli_query($conexion,"UPDATE productos SET  
                nombre_producto = '$nombre_producto',
                contenido_neto = '$contenido_neto',
                presentacion = '$presentacion_producto',
                precio = '$precio',
                grabado_excento = '$tipo',
                categoria_productos = '$categoria'
                WHERE codigo_producto = '$codigo_producto' 
            ");
        
            echo json_encode(['success'  => 1]);
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    function verificar_producto_usado($id_producto,$tabla){
        global $conexion;
        try{
            $datos = mysqli_query($conexion, "SELECT * FROM $tabla where productos = '$id_producto'");
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                return true;
                exit();
            }else{
                return false;
            }
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    function eliminar_producto($id_producto){
        global $conexion;
        try{
            $datos = mysqli_query($conexion, "DELETE FROM productos where id_producto = '$id_producto'");
            return $datos;
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    if(isset($_GET['eliminar_producto'])){

        $id_producto = $_GET['eliminar_producto'];

        //Verificamos si el producto ya fue utilizado en un registro de la tabla venta-productos
        $verificar_tabla_venta_productos = verificar_producto_usado($id_producto,'venta_productos');

        if($verificar_tabla_venta_productos){
            echo json_encode(['success' => 2]);
            return;
        }

        //Verificamos si el producto ya fue utilizado en un registro de la tabla compra-productos
        $verificar_tabla_compra_productos = verificar_producto_usado($id_producto,'compra_productos');

        if($verificar_tabla_compra_productos){
            echo json_encode(['success' => 2]);
            return;
        }

        //En caso de que no haya sido usado, procedemos a borrar el registro del producto
        $producto_eliminado = eliminar_producto($id_producto);
        if($producto_eliminado){
            echo json_encode(['success' => 1]);
        }else{
            echo json_encode(['success' => 0]);
        }   
        
    }

    