<?php

    require_once "conexion.php";

    $conexion = conexion();

    function buscar_clientes($tabla){
        global $conexion;

        try{

            $data = mysqli_query($conexion,"SELECT * from $tabla");

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

    function buscar_cliente($cliente, $tabla, $campo_clave){
        global $conexion;
    
        try {
            $consulta = "SELECT *
            FROM $tabla
            WHERE $campo_clave LIKE ? ";
            
            $stmt = mysqli_prepare($conexion, $consulta);
            mysqli_stmt_bind_param($stmt, "s", $parametro);
            
            $parametro = "%" . $cliente . "%";
            
            mysqli_stmt_execute($stmt);
            $datos = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($datos) > 0){
                $datos = mysqli_fetch_all($datos, MYSQLI_ASSOC);
                return $datos;
                exit();
            } else {
                return [];
            }
        } catch (mysqli_sql_exception $error) {
            echo json_encode(["success" => "Ha ocurrido un error: " . $error]);
        }
    }
    
    function ordenar_datos($data_clientes,$tabla){

        $arrayDataClientes = array();

        //Ordenamos los datos de los clientes
        for ($i=0; $i <count($data_clientes) ; $i++) { 
            
            //CREAMOS UNA INSTANCIA DE LA CLASE CLIENTE Y PASAMOS VALORES
            $cliente = null;

            if($tabla == 'cliente_natural'){

                $cliente = new ClienteNatural(
                    $data_clientes[$i]['usuario_cliente'],
                    $data_clientes[$i]['Cedula'],
                    $data_clientes[$i]['nombre'],
                    $data_clientes[$i]['apellido'],
                    $data_clientes[$i]['numero_telefono'],
                    $data_clientes[$i]['email'],
                    $data_clientes[$i]['ubicacion']
                );
            }else{
                $cliente = new ClienteEmpresa(
                    $data_clientes[$i]['usuario_cliente'],
                    $data_clientes[$i]['Rif'],
                    $data_clientes[$i]['nombre_empresa'],
                    $data_clientes[$i]['numero_telefono'],
                    $data_clientes[$i]['email'],
                    $data_clientes[$i]['ubicacion'],
                );
            }

            array_push($arrayDataClientes,$cliente);
        }

        
        return $arrayDataClientes;
    }

    if(isset($_GET['obtener_datos_clientes'])){

        $tabla_cliente = $_GET['obtener_datos_clientes'];

        //Realizamos la busqueda de los datos de los clientes
        $data_clientes = buscar_clientes($tabla_cliente);

        //Ordenamos la data
        $arrayDataClientes = ordenar_datos($data_clientes,$tabla_cliente);

        //Retornamos la data de los clientes de forma ordenada por cliente natural y cliente empresa
        echo json_encode(['success' => $arrayDataClientes]);

    }

    if(isset($_GET['buscar_cliente'])){
        
        $cliente = $_GET['buscar_cliente'];
        $campo_clave = $_GET['campo_clave'];
        $tabla = $_GET['tabla'];

        //Realizamos la busqueda de los clientes
        $data_clientes = buscar_cliente($cliente,$tabla,$campo_clave);

        $arrayDataClientes = ordenar_datos($data_clientes,$tabla);

        //Retornamos la data de los clientes de forma ordenada por cliente natural y cliente empresa
        echo json_encode(['success' => $arrayDataClientes]);
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