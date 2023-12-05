<?php 

    require_once "./conexion.php";
    include "../controllers/validaciones.php";
    
    $conexion = conexion();

    class ObjetoPedido {

        public $id_pedido;
        public $fecha_pedido;
        public $pagado;
        public $cliente;
        public $banco;
        public $codigo_pago;
        public $productos = Array();
    
        public function __construct($id_pedido, $fecha_pedido, $pagado, $cliente, $banco,$codigo_pago,$productos)
        {
            $this->id_pedido = $id_pedido;
            $this->fecha_pedido = $fecha_pedido;
            $this->pagado = $pagado;
            $this->cliente = $cliente;
            $this->banco = $banco;
            $this->codigo_pago = $codigo_pago;
            array_push($this->productos, $productos);
        }
        public function getIdPedido()
        {
            return $this->id_pedido;
        }
    
        public function getFechaPedido()
        {
            return $this->fecha_pedido;
        }
    
        public function getPagado()
        {
            return $this->pagado;
        }
    
        public function getCliente()
        {
            return $this->cliente;
        }

        public function getBanco()
        {
            return $this->banco;
        }
    
        public function getCodigoPago()
        {
            return $this->codigo_pago;
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
        public $codigo_producto;
        public $nombre_producto;
        public $contenido_neto;
        public $precio;
        public $image;
        public $grabado_excento;
    
        function __construct($cantidad, $id_producto, $codigo_producto,$nombre_producto,$contenido_neto,$precio,$image,$grabado_excento)
        {
            $this->cantidad = $cantidad;
            $this->id_producto = $id_producto;
            $this->codigo_producto = $codigo_producto;
            $this->nombre_producto = $nombre_producto;
            $this->contenido_neto = $contenido_neto;
            $this->precio = $precio;
            $this->image = $image;
            $this->grabado_excento = $grabado_excento;
        }
    
        public function getCantidad()
        {
            return $this->cantidad;
        }
    
        public function getIdProducto()
        {
            return $this->id_producto;
        }

        public function getCodigoProducto()
        {
            return $this->codigo_producto;
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

        public function getUrlImage()
        {
            return $this->image;
        }

        public function getGrabado_excento()
        {
            return $this->grabado_excento;
        }
    }

    class Cliente {
        public $usuarioCliente;
    
        public function __construct($usuarioCliente) {
            $this->usuarioCliente = $usuarioCliente;
        }
    
        public function getUsuarioCliente() {
            return $this->usuarioCliente;
        }
    
    }
    
    class ClienteNatural extends Cliente {
        public $cedula;
        public $nombre;
        public $apellido;
        public $numeroTelefono;
        public $email;
        public $ubicacion;
    
        public function __construct($usuarioCliente, $cedula, $nombre, $apellido, $numeroTelefono, $email, $ubicacion) {
            parent::__construct($usuarioCliente);
            $this->cedula = $cedula;
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->numeroTelefono = $numeroTelefono;
            $this->email = $email;
            $this->ubicacion = $ubicacion;
        }
    
        // Métodos adicionales específicos de ClienteNatural
    
        public function getCedula() {
            return $this->cedula;
        }
    
        public function getNombre() {
            return $this->nombre;
        }
    
        public function getApellido() {
            return $this->apellido;
        }
    
        public function getNumeroTelefono() {
            return $this->numeroTelefono;
        }
    
        public function getEmail() {
            return $this->email;
        }
    
        public function getUbicacion() {
            return $this->ubicacion;
        }
    }
    
    class ClienteEmpresa extends Cliente {
        public $rif;
        public $nombreEmpresa;
        public $numeroTelefono;
        public $email;
        public $ubicacion;
    
        public function __construct($usuarioCliente, $rif, $nombreEmpresa, $numeroTelefono, $email, $ubicacion) {
            parent::__construct($usuarioCliente);
            $this->rif = $rif;
            $this->nombreEmpresa = $nombreEmpresa;
            $this->numeroTelefono = $numeroTelefono;
            $this->email = $email;
            $this->ubicacion = $ubicacion;
        }
    
        // Métodos adicionales específicos de ClienteEmpresa
    
        public function getRif() {
            return $this->rif;
        }
    
        public function getNombreEmpresa() {
            return $this->nombreEmpresa;
        }
    
        public function getNumeroTelefono() {
            return $this->numeroTelefono;
        }
    
        public function getEmail() {
            return $this->email;
        }
    
        public function getUbicacion() {
            return $this->ubicacion;
        }
    }
    
    //Extraer datos del pedido por medio del id_pedido
    function extraerDataPedido_por_fecha($fecha_pedido){
        global $conexion;

        try{

            $data = mysqli_query($conexion, "SELECT *
            FROM pedido
            INNER JOIN pedido_productos
            ON pedido.id_pedido = pedido_productos.id_pedido
            INNER JOIN productos
            ON pedido_productos.producto = productos.id_producto
            left join cliente_natural on pedido.cliente_natural = cliente_natural.Cedula
            left join cliente_empresa on pedido.cliente_empresa = cliente_empresa.Rif
            WHERE fecha_pedido LIKE '$fecha_pedido%' AND pagado = 2;");

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

    //Extraer datos del pedido por medio del id_pedido
    function extraerDataPedido_por_id_pedido($id_pedido){
        global $conexion;

        try{

            $data = mysqli_query($conexion,"SELECT *
            FROM pedido
            INNER JOIN pedido_productos
            ON pedido.id_pedido = pedido_productos.id_pedido
            INNER JOIN productos
            ON pedido_productos.producto = productos.id_producto
            left join cliente_natural on pedido.cliente_natural = cliente_natural.Cedula
            left join cliente_empresa on pedido.cliente_empresa = cliente_empresa.Rif
            WHERE pedido.id_pedido = '$id_pedido'");

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

    function ordenar_datos_pedido($data_pedido){
        $arrayObjetos = Array();

        for ($i=0; $i < count($data_pedido) ; $i++) { 
        
            //Verificamos si el arrayObjetos esta vacio
            if(count($arrayObjetos) === 0){
                
                // //CREAMOS UNA INSTANCIA DE LA CLASE PRODUCTOS Y PASAMOS VALORES
                $productos = new Producto(
                    $data_pedido[$i]['cantidad_productos'],
                    $data_pedido[$i]['id_producto'],
                    $data_pedido[$i]['codigo_producto'],
                    $data_pedido[$i]['nombre_producto'],
                    $data_pedido[$i]['contenido_neto'],
                    $data_pedido[$i]['precio'],
                    $data_pedido[$i]['url_image'],
                    $data_pedido[$i]['grabado_excento']
                );

                //CREAMOS UNA INSTANCIA DE LA CLASE CLIENTE Y PASAMOS VALORES
                $cliente = null;

                if($data_pedido[$i]['Cedula'] !== null){
                    $cliente = new ClienteNatural(
                        $data_pedido[$i]['usuario_cliente'],
                        $data_pedido[$i]['Cedula'],
                        $data_pedido[$i]['nombre'],
                        $data_pedido[$i]['apellido'],
                        $data_pedido[$i]['numero_telefono'],
                        $data_pedido[$i]['email'],
                        $data_pedido[$i]['ubicacion']
                    );
                }else{
                    $cliente = new ClienteEmpresa(
                        $data_pedido[$i]['usuario_cliente'],
                        $data_pedido[$i]['Rif'],
                        $data_pedido[$i]['nombre_empresa'],
                        $data_pedido[$i]['numero_telefono'],
                        $data_pedido[$i]['email'],
                        $data_pedido[$i]['ubicacion'],
                    );
                }    
                    
                // //CREAMOS UNA INSTANCIA DE LA CLASE OBJETOVENTAS Y PASAMOS VALORES
                    
                $objeto = new ObjetoPedido(
                    $data_pedido[$i]['id_pedido'],
                    $data_pedido[$i]['fecha_pedido'],
                    $data_pedido[$i]['pagado'],
                    $cliente,
                    $data_pedido[$i]['banco'] == null ? '' : $data_pedido[$i]['banco'],
                    $data_pedido[$i]['codigo_pago'] == null ? '' : $data_pedido[$i]['codigo_pago'],
                    $productos
                );

                // //AGREGAMOS EL OBJETO AL ARRAYOBJETOS
                array_push($arrayObjetos,$objeto);
    
            }else{
                    
                //Obtenemos el id de venta actual
                $id_pedido_current = $data_pedido[$i]['id_pedido'];
                
                //Buscamos un objeto dentro del array 'arrayObjetos' que tenga en su propiedad idVenta el mismo valor que el id_venta_current
                $encontrado = array_filter($arrayObjetos, function($objeto) use ($id_pedido_current){
                    if($objeto->getIdPedido() == $id_pedido_current){
                        return $objeto;
                    }
                });

                // //Verificamos si hubo coincidencia
                if(!empty($encontrado)){

                    //CREAMOS UNA INSTANCIA DE LA CLASE PRODUCTOS Y PASAMOS VALORES
                    $productos = new Producto(
                        $data_pedido[$i]['cantidad_productos'],
                        $data_pedido[$i]['id_producto'],
                        $data_pedido[$i]['codigo_producto'],
                        $data_pedido[$i]['nombre_producto'],
                        $data_pedido[$i]['contenido_neto'],
                        $data_pedido[$i]['precio'],
                        $data_pedido[$i]['url_image'],
                        $data_pedido[$i]['grabado_excento']
                    );

                    //Buscamos el indice del objeto que encontramos en el array 'arrayObjetos'
                    $indice = buscarObjetoPorPropiedad($arrayObjetos,'id_pedido',$id_pedido_current);

                    // //Agregamos los nuevos productos a la propiedad productos del objeto 
                    $arrayObjetos[$indice]->addProduct($productos);

                }else{

                    //En el caso de que no existen el array 'arrayObjetos' un item con la propiedad idVenta con el mismo valor que el id_venta actual, creamos un nuevo objeto y lo insertamos al array

                    //CREAMOS UNA INSTANCIA DE LA CLASE PRODUCTOS Y PASAMOS VALORES
                    $productos = new Producto(
                        $data_pedido[$i]['cantidad_productos'],
                        $data_pedido[$i]['id_producto'],
                        $data_pedido[$i]['codigo_producto'],
                        $data_pedido[$i]['nombre_producto'],
                        $data_pedido[$i]['contenido_neto'],
                        $data_pedido[$i]['precio'],
                        $data_pedido[$i]['url_image'],
                        $data_pedido[$i]['grabado_excento']
                    );

                    //CREAMOS UNA INSTANCIA DE LA CLASE CLIENTE Y PASAMOS VALORES
                    $cliente1  = null;

                    if($data_pedido[$i]['Cedula'] !== null){
                        $cliente1 = new ClienteNatural(
                            $data_pedido[$i]['usuario_cliente'],
                            $data_pedido[$i]['Cedula'],
                            $data_pedido[$i]['nombre'],
                            $data_pedido[$i]['apellido'],
                            $data_pedido[$i]['numero_telefono'],
                            $data_pedido[$i]['email'],
                            $data_pedido[$i]['ubicacionl']
                        );
                    }else{
                        $cliente1 = new ClienteEmpresa(
                            $data_pedido[$i]['usuario_cliente'],
                            $data_pedido[$i]['Rif'],
                            $data_pedido[$i]['nombre_empresa'],
                            $data_pedido[$i]['numero_telefono'],
                            $data_pedido[$i]['email'],
                            $data_pedido[$i]['ubicacion'],
                        );
                    }
                    
                    // //CREAMOS UNA INSTANCIA DE LA CLASE OBJETOVENTAS Y PASAMOS VALORES
                        
                    $objeto = new ObjetoPedido(
                        $data_pedido[$i]['id_pedido'],
                        $data_pedido[$i]['fecha_pedido'],
                        $data_pedido[$i]['pagado'],
                        $cliente1,
                        $data_pedido[$i]['banco'] == null ? '' : $data_pedido[$i]['banco'],
                        $data_pedido[$i]['codigo_pago'] == null ? '' : $data_pedido[$i]['codigo_pago'],
                        $productos
                    );

                        // //AGREGAMOS EL OBJETO AL ARRAYOBJETOS
                        array_push($arrayObjetos,$objeto);
                }
            }
        }

        return $arrayObjetos;
    }

    //Obtenemos los datos de un pedido por medio del id_pedido
    if(isset($_GET['extraer_data_pedido'])){

        $id_pedido =  $_GET['extraer_data_pedido'];

        $data_pedido = extraerDataPedido_por_id_pedido($id_pedido);

        $arrayObjetos = ordenar_datos_pedido($data_pedido);
        
        echo json_encode(['success' => $arrayObjetos]);

    }

    /* Funcion permite extraer los pedidos reliazados en la fecha proporcionada por parametro */
    if(isset($_GET['buscar_pedidos_por_fecha'])){
        
        $fecha_pedido =  $_GET['buscar_pedidos_por_fecha'];

        $data_pedido = extraerDataPedido_por_fecha($fecha_pedido);

        $arrayObjetos = ordenar_datos_pedido($data_pedido);
        
        echo json_encode(['success' => $arrayObjetos]);
      
    }

    //Modificamos el estatus del pedido
    if(isset($_GET['modificar_estatus_pedido'])){
        global $conexion;

        $id_pedido= $_GET['modificar_estatus_pedido'];

        try{
            $data = mysqli_query($conexion,"UPDATE pedido SET  
                pagado = 1
                WHERE id_pedido = '$id_pedido' 
            ");
            echo json_encode(['success' => $data]);
        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

     //Function permite realizar consulta para borrar los pedidos vencidos de la tabla pedido_productos de un cliente
     function borrar_pedidos_vencidos_tabla_pedido_productos($id_pedido){
        global $conexion;

        try{
            $data = mysqli_query($conexion,"DELETE FROM pedido_productos WHERE id_pedido = '$id_pedido'");
            return $data;

        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }

    }

    //Function permite realizar consulta para borrar un pedido vencido especifico de la tabla pedido 
    function borrar_pedidos_vencidos_tabla_pedido($id_pedido){
        global $conexion;

        try{
            $data = mysqli_query($conexion,"DELETE FROM pedido WHERE id_pedido = '$id_pedido'");
            return $data;

        }catch(mysqli_sql_exception $error){
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }

    }

    function disminuir_cantidad_apartada($cantidad_requerida,$id_producto){
        global $conexion;
        try {
            $datos = mysqli_query($conexion, "UPDATE productos
            SET cantidad_apartada = cantidad_apartada - $cantidad_requerida
            WHERE id_producto = $id_producto;");

            return $datos;
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Borrar los pedidos expirados
    if(isset($_GET['borrar_pedidos_vencidos'])){

        $data = json_decode(file_get_contents('php://input'));

        $borrado_pedidos_productos = false;
        $array_response = array();

        //Borramos los registros de la tabla pedido productos
        for ($i=0; $i < count($data) ; $i++) { 

            //Verificamos que el codigo de pago del pedido sea igual a null o un string vacio para poder borrar
            if($data[$i]->codigo_pago == null || $data[$i]->codigo_pago == ""){
                //Borramos los datos del pedido de la tabla pedido_productos
                $borrado_pedidos_productos = borrar_pedidos_vencidos_tabla_pedido_productos($data[$i]->id_pedido);

                for ($j=0; $j < count($data[$i]->productos); $j++) { 
                    $is_disminuido = disminuir_cantidad_apartada(
                        $data[$i]->productos[$j]->cantidad,
                        $data[$i]->productos[$j]->id_producto
                    );
                }
                array_push($array_response,$borrado_pedidos_productos);
            }

            //Verificamos que se hayan borrado todos los registros del pedido de la tabla pedido_productos
            if($borrado_pedidos_productos){
                //Borramos los registros del pedido de la tabala pedido
                borrar_pedidos_vencidos_tabla_pedido($data[$i]->id_pedido);
            }

            $borrado_pedido_productos = false;
        }


        $is_borrado = false;

        //Verificamos si hubo un proceso de borrado exitoso
        for ($i=0; $i < count($array_response); $i++) { 
            if($array_response[$i] == true){
                $is_borrado = true;
                break;
            }
        }

        if($is_borrado){
            echo json_encode(['success' => 1]);
        }else{
            echo json_encode(['success' => 0]);
        }
    }

    function buscar_pedidos_por_pagar(){
        global $conexion;

        try {
            $datos = mysqli_query($conexion, "SELECT *
                FROM pedido
                INNER JOIN pedido_productos
                ON pedido.id_pedido = pedido_productos.id_pedido
                WHERE  pagado = '2'
                ");

            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos,MYSQLI_ASSOC);
                return $datos;
                exit();
            }else{
                return [];
            }
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "ha ocurrido un error ".$error]);
        }
    }

    //Obtenemos los datos de los pedidos pendientes
    if(isset($_GET['extraer_data_pedidos'])){
        
        $data_pedido = buscar_pedidos_por_pagar();

        $arrayObjetos = array();

        //Ordenamos los datos de los pedidos
        for ($i=0; $i < count($data_pedido) ; $i++) { 
        
            //Verificamos si el arrayObjetos esta vacio
            if(count($arrayObjetos) === 0){
                
                // //CREAMOS UNA INSTANCIA DE LA CLASE PRODUCTOS Y PASAMOS VALORES
                $productos = new Producto(
                    $data_pedido[$i]['cantidad_productos'],
                    $data_pedido[$i]['producto'],
                    "",
                    "",
                    "",
                    "",
                    "",""
                );
                    
                // //CREAMOS UNA INSTANCIA DE LA CLASE OBJETOVENTAS Y PASAMOS VALORES
                    
                $objeto = new ObjetoPedido(
                    $data_pedido[$i]['id_pedido'],
                    $data_pedido[$i]['fecha_pedido'],
                    $data_pedido[$i]['pagado'],
                    "",
                    $data_pedido[$i]['banco'] == null ? '' : $data_pedido[$i]['banco'],
                    $data_pedido[$i]['codigo_pago'] == null ? '' : $data_pedido[$i]['codigo_pago'],
                    $productos
                );

                // //AGREGAMOS EL OBJETO AL ARRAYOBJETOS
                array_push($arrayObjetos,$objeto);
    
            }else{
                    
                //Obtenemos el id de venta actual
                $id_pedido_current = $data_pedido[$i]['id_pedido'];
                
                //Buscamos un objeto dentro del array 'arrayObjetos' que tenga en su propiedad idVenta el mismo valor que el id_venta_current
                $encontrado = array_filter($arrayObjetos, function($objeto) use ($id_pedido_current){
                    if($objeto->getIdPedido() == $id_pedido_current){
                        return $objeto;
                    }
                });

                //Verificamos si hubo coincidencia
                if(!empty($encontrado)){

                    //CREAMOS UNA INSTANCIA DE LA CLASE PRODUCTOS Y PASAMOS VALORES
                    $productos = new Producto(
                        $data_pedido[$i]['cantidad_productos'],
                        $data_pedido[$i]['producto'],
                        "",
                        "",
                        "",
                        "",
                        "",""
                    );

                    //Buscamos el indice del objeto que encontramos en el array 'arrayObjetos'
                    $indice = buscarObjetoPorPropiedad($arrayObjetos,'id_pedido',$id_pedido_current);

                    // //Agregamos los nuevos productos a la propiedad productos del objeto 
                    $arrayObjetos[$indice]->addProduct($productos);

                }else{
    
                        //En el caso de que no existen el array 'arrayObjetos' un item con la propiedad idVenta con el mismo valor que el id_venta actual, creamos un nuevo objeto y lo insertamos al array
    
                        //CREAMOS UNA INSTANCIA DE LA CLASE PRODUCTOS Y PASAMOS VALORES
                        $productos = new Producto(
                            $data_pedido[$i]['cantidad_productos'],
                            $data_pedido[$i]['producto'],
                            "",
                            "",
                            "",
                            "",
                            "",""
                        );
        
                    //CREAMOS UNA INSTANCIA DE LA CLASE OBJETOVENTAS Y PASAMOS VALORES
                        
                    $objeto = new ObjetoPedido(
                        $data_pedido[$i]['id_pedido'],
                        $data_pedido[$i]['fecha_pedido'],
                        $data_pedido[$i]['pagado'],
                        "",
                        $data_pedido[$i]['banco'] == null ? '' : $data_pedido[$i]['banco'],
                        $data_pedido[$i]['codigo_pago'] == null ? '' : $data_pedido[$i]['codigo_pago'],
                        $productos
                    );
    
                    //AGREGAMOS EL OBJETO AL ARRAYOBJETOS
                    array_push($arrayObjetos,$objeto);
                }
            }
        }

        echo json_encode(['success' => $arrayObjetos]);
    }

