<?php

    require_once "conexion.php";
    $conexion = conexion();

    class ObjetoVenta {

        public $idVenta;
        public $fechaVenta;
        public $montoVenta;
        public $administrador;
        public $productos = Array();
    
        public function __construct($idVenta, $fechaVenta, $montoVenta, $administrador, $productos)
        {
            $this->idVenta = $idVenta;
            $this->fechaVenta = $fechaVenta;
            $this->montoVenta = $montoVenta;
            $this->administrador = $administrador;
            array_push($this->productos, $productos);
        }
        public function getIdVenta()
        {
            return $this->idVenta;
        }
    
        public function getFechaVenta()
        {
            return $this->fechaVenta;
        }
    
        public function getMontoVenta()
        {
            return $this->montoVenta;
        }

        public function getAdministrador()
        {
            return $this->administrador;
        }
    
        public function getProductos()
        {
            return $this->productos;
        }

        public function addProduct($product){
            array_push($this->productos, $product);
        }
    }

    class Producto {
        
        public $cantidad;
        public $id_producto;
        public $nombre_producto;
        public $contenido_neto;
        public $precio;
        public $grabado_excento;
        public $nombre_presentacion;

        function __construct($cantidad, $id_producto,$nombre_producto,$contenido_neto,$precio,$grabado_excento,$nombre_presentacion)
        {
            $this->cantidad = $cantidad;
            $this->id_producto = $id_producto;
            $this->nombre_producto = $nombre_producto;
            $this->contenido_neto = $contenido_neto;
            $this->precio = $precio;
            $this->grabado_excento = $grabado_excento;
            $this->nombre_presentacion = $nombre_presentacion;
        }
    
        public function getCantidad()
        {
            return $this->cantidad;
        }
    
        public function getIdProducto()
        {
            return $this->id_producto;
        }

        public function getNombreProducto()
        {
            return $this->nombre_producto;
        }
    
        public function getContenidoNeto()
        {
            return $this->contenido_neto;
        }

        public function getPrecio()
        {
            return $this->precio;
        }

        public function getNombrePresentacion()
        {
            return $this->nombre_presentacion;
        }
    }

    class Administrador {
        public $nombre;
        public $apellido;
    
        public function __construct($nombre, $apellido) {
            $this->nombre = $nombre;
            $this->apellido = $apellido;
        }
    
        public function getNombre() {
            return $this->nombre;
        }
    
        public function getApellido() {
            return $this->apellido;
        }
    }
    
    //Extraer datos de la venta por medio de la fecha
    function extraerDataVentas($fecha){
        global $conexion;

        try{

            $data = mysqli_query($conexion,"SELECT *
            FROM ventas
            INNER JOIN venta_productos
            ON ventas.id_venta = venta_productos.id_venta
            INNER JOIN productos
            ON venta_productos.productos = productos.id_producto
            INNER JOIN presentacion_producto
            on productos.presentacion = presentacion_producto.id_presentacion
            INNER JOIN categoria_productos
            on productos.categoria_productos = categoria_productos.id_categoria
            WHERE fecha_venta LIKE '$fecha%' ORDER BY ventas.id_venta;");

            if(mysqli_num_rows($data) > 0){
                $data = mysqli_fetch_all($data,MYSQLI_ASSOC);
                return $data;
                exit();
            }else{
                return [];
            }

        }catch(mysqli_sql_exception $error){
            echo json_encode(["error" => "Ha ocurrido un error durante la busqueda".$error]);
        } 

    }

    //Extraer datos de una venta especifica
    function extraerDataVenta($id_venta){
        global $conexion;

        try{

            $data = mysqli_query($conexion,"SELECT *
            FROM ventas
            INNER JOIN venta_productos
            ON ventas.id_venta = venta_productos.id_venta
            INNER JOIN productos
            ON venta_productos.productos = productos.id_producto
            INNER JOIN presentacion_producto
            on productos.presentacion = presentacion_producto.id_presentacion
            INNER JOIN categoria_productos
            on productos.categoria_productos = categoria_productos.id_categoria
            WHERE ventas.id_venta = $id_venta
            ORDER BY ventas.id_venta");

            if(mysqli_num_rows($data) > 0){
                $data = mysqli_fetch_all($data,MYSQLI_ASSOC);
                return $data;
                exit();
            }else{
                return [];
            }

        }catch(mysqli_sql_exception $error){
            echo json_encode(["error" => "Ha ocurrido un error durante la busqueda".$error]);
        } 

    }

    function buscarObjetoPorPropiedad($array, $propiedad, $valor) {
        foreach ($array as $indice => $objeto) {
            if ($objeto->$propiedad === $valor) {
                return $indice;
            }
        }
        return false; // Si no se encuentra el objeto en el array
    }

    function ordenar_datos_ventas($dataVentas){
        $arrayObjetos = Array();

        for ($i=0; $i < count($dataVentas) ; $i++) { 
        
            //Verificamos si el arrayObjetos esta vacio
            if(count($arrayObjetos) === 0){
               
                // //CREAMOS UNA INSTANCIA DE LA CLASE PRODUCTOS Y PASAMOS VALORES
                $productos = new Producto(
                    $dataVentas[$i]['cantidad_productos'],
                    $dataVentas[$i]['codigo_producto'],
                    $dataVentas[$i]['nombre_producto'],
                    $dataVentas[$i]['contenido_neto'],
                    $dataVentas[$i]['precio_producto_al_vender'],
                    $dataVentas[$i]['grabado_excento_al_vender'],
                    $dataVentas[$i]['nombre_presentacion']
                );
                
                //CREAMOS UNA INSTANCIA DE LA CLASE ADMINISTRADOR Y PASAMOS VALORES

                // $administrador = new Administrador(
                //     $dataVentas[$i]['nombre_administrador'],
                //     $dataVentas[$i]['apellido_administrador']
                // );
                    
                // //CREAMOS UNA INSTANCIA DE LA CLASE OBJETOVENTAS Y PASAMOS VALORES
                    
                $objeto = new ObjetoVenta(
                    $dataVentas[$i]['id_venta'],
                    $dataVentas[$i]['fecha_venta'],
                    $dataVentas[$i]['precio_total_venta'],
                    "",
                    $productos
                );

                // //AGREGAMOS EL OBJETO AL ARRAYOBJETOS
                array_push($arrayObjetos,$objeto);

            }else{
                
                //Obtenemos el id de venta actual
                $id_venta_current = $dataVentas[$i]['id_venta'];
                
                //Buscamos un objeto dentro del array 'arrayObjetos' que tenga en su propiedad idVenta el mismo valor que el id_venta_current
                $encontrado = array_filter($arrayObjetos, function($objeto) use ($id_venta_current){
                    if($objeto->getIdVenta() === $id_venta_current){
                        return $objeto;
                    }
                });

                //Verificamos si hubo coincidencia
                if(!empty($encontrado)){

                    //CREAMOS UNA INSTANCIA DE LA CLASE PRODUCTOS Y PASAMOS VALORES
                    $productos = new Producto(
                        $dataVentas[$i]['cantidad_productos'],
                        $dataVentas[$i]['codigo_producto'],
                        $dataVentas[$i]['nombre_producto'],
                        $dataVentas[$i]['contenido_neto'],
                        $dataVentas[$i]['precio_producto_al_vender'],
                        $dataVentas[$i]['grabado_excento_al_vender'],
                        $dataVentas[$i]['nombre_presentacion']
                    );

                    //Buscamos el indice del objeto que encontramos en el array 'arrayObjetos'
                    $indice = buscarObjetoPorPropiedad($arrayObjetos,'idVenta',$id_venta_current);

                    //Agregamos los nuevos productos a la propiedad productos del objeto 
                    $arrayObjetos[$indice]->addProduct($productos);

                }else{

                    //En el caso de que no existen el array 'arrayObjetos' un item con la propiedad idVenta con el mismo valor que el id_venta actual, creamos un nuevo objeto y lo insertamos al array

                    //CREAMOS UNA INSTANCIA DE LA CLASE PRODUCTOS Y PASAMOS VALORES
                    $productos = new Producto(
                        $dataVentas[$i]['cantidad_productos'],
                        $dataVentas[$i]['codigo_producto'],
                        $dataVentas[$i]['nombre_producto'],
                        $dataVentas[$i]['contenido_neto'],
                        $dataVentas[$i]['precio_producto_al_vender'],
                        $dataVentas[$i]['grabado_excento_al_vender'],
                        $dataVentas[$i]['nombre_presentacion']
                    );
                    
                    //CREAMOS UNA INSTANCIA DE LA CLASE ADMINISTRADOR Y PASAMOS VALORES

                    // $administrador = new Administrador(
                    //     $dataVentas[$i]['nombre_administrador'],
                    //     $dataVentas[$i]['apellido_administrador']
                    // );
                            
                    // //CREAMOS UNA INSTANCIA DE LA CLASE OBJETOVENTAS Y PASAMOS VALORES  
                    $objeto = new ObjetoVenta(
                        $dataVentas[$i]['id_venta'],
                        $dataVentas[$i]['fecha_venta'],
                        $dataVentas[$i]['precio_total_venta'],
                        "",
                        $productos
                    );

                    // //AGREGAMOS EL OBJETO AL ARRAYOBJETOS
                    array_push($arrayObjetos,$objeto);
                }
            }
        }

        return $arrayObjetos;
    }

    //Extraer datos de las ventas realizadas en una fecha especifica
    if(isset($_GET['extraerVentas'])){

        $fecha_ventas = $_GET['extraerVentas'];

        //Validar fecha
        //fuction validarFecha 

        //Extraemos los datos de las ventas que se realizaron en un fecha especifica
        $dataVentas = extraerDataVentas($fecha_ventas);

        $arrayObjetos = ordenar_datos_ventas($dataVentas);

        echo json_encode(['success' => $arrayObjetos]);
    }
    
    //Extraer datos de una venta especifica
    if(isset($_GET['extraerDatosVenta'])){

        $id_venta = $_GET['extraerDatosVenta'];

        //Extraemos los datos de una venta especifica
        $dataVentas = extraerDataVenta($id_venta);

        $arrayObjetos = ordenar_datos_ventas($dataVentas);

        echo json_encode(['success' => $arrayObjetos]);
    }

    

