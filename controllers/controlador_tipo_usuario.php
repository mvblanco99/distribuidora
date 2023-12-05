<?php

    function buscarCoincidencia($valor, $array) {
        foreach ($array as $posicion => $elemento) {
            if ($valor === $elemento || $valor === $posicion) {
                return array(true, $elemento);
            }
        }
        return array(false, null);
    }

    function comprobacion_acceso($destino, $tipo_usuario){

        $administrador_registros = [
            'registrar_productos',
            'lista_productos' ,
            'update_productos',
            'compra',
            'compra_productos',
            'modificar_compra',
            'modificar_compra_productos',
            'lista_compra_disponible',
            'lista_compra_total',
            'inventario'
        ];

        $administrador_ventas = ['ventas','venta_pedido','pedidos'];

        $administrador_visualizacion = [
            'lista_administradores',
            'lista_productos', 
            'lista_productos_venta', 
            'lista_compra_disponible',
            'lista_compra_total',
            'visualizar_compra',
            'lista_cliente',
            'visualizar_cliente',
            'resumen_ventas',
            'registrar_productos' => 'lista_productos', 
            'compra' => 'lista_compra_disponible', 
            'registrar_administrador' => 
            'lista_administradores'
        ];

        $encontrado = null;

        switch ($tipo_usuario) {
            case 1:
                $encontrado = [true,$destino];
                break;

            case 2:
                $encontrado = buscarCoincidencia($destino,$administrador_visualizacion);
                break;

            case 3://completed
                $encontrado = buscarCoincidencia($destino, $administrador_ventas);
                break;

            case 4:
                $encontrado = buscarCoincidencia($destino, $administrador_registros);
                break;
            
            default:
                # code...
                break;
        }

        return $encontrado;
    }

    if(isset($_GET['comprobar_acceso'])){

        $destino = $_GET['destino'];
        $tipo_admin = $_GET['tipo_admin'];

        //comprobacion_acceso($destino,$tipo_admin);
        $encontrado = comprobacion_acceso($destino,$tipo_admin);
        echo json_encode(['success' => $encontrado]);

    }

    function tienePrivilegiosAdministrador($accion, $tipo_usuario){
        
        $acciones = ['buscar' => [1,2,3,4],'modificar' => [1,3,4],'eliminar' => [1,3,4],'registrar' => [1,3,4]];

        $arrayUsuariosConPermisos = null;

        //Extraemos el array el que contiene los usuarios con permisos para ejecutar la accion solicitada
        foreach ($acciones as $posicion => $elemento){
            if($accion === $posicion){
                $arrayUsuariosConPermisos = $elemento;
            }
        }

        $usuarioPermiso = false;

        // //Verificamos si el usuario tiene los permisos para ejecutar la accion solicitada
        for ($i=0; $i < count($arrayUsuariosConPermisos); $i++) { 
            if($tipo_usuario == $arrayUsuariosConPermisos[$i]){
                $usuarioPermiso = true;
                break;
            }
        }

        return $usuarioPermiso;
    }

    if(isset($_GET['comprobarPermisoAccion'])){

        $accion = $_GET['accion'];
        $tipo_admin = $_GET['tipo_admin'];

        //Verificamos si el administrador cuenta con los permisos necesarios
        $permiso = tienePrivilegiosAdministrador($accion,$tipo_admin);

        echo json_encode(['success' => $permiso]);

    }