<?php

   if(isset($_GET['sesion_administrador'])){
      session_start();
      $_SESSION['administrador'] = $_GET['sesion_administrador'];
      echo json_encode(["data" => "variable de sesion creada = ".$_SESSION['administrador']]);  
   }

   if(isset($_GET['sesion_producto'])){
      session_start();
      $_SESSION['producto'] = $_GET['sesion_producto'];
      echo json_encode(["data" => "variable de sesion creada = ".$_SESSION['producto']]);  
   }

   if(isset($_GET['variable_session_id_compra_visualizar'])){
      session_start();
      $_SESSION['visualizar_compra'] = $_GET['variable_session_id_compra_visualizar'];
      echo json_encode(["data" => "variable de sesion creada = ".$_SESSION['visualizar_compra']]); 
   }

   if(isset($_GET['variable_session_id_compra_modificar'])){
      session_start();
      $_SESSION['id_modificar_compra'] = $_GET['variable_session_id_compra_modificar'];
      echo json_encode(["data" => "variable de sesion creada = ".$_SESSION['id_modificar_compra']]); 
   }

   if(isset($_GET['sesion_cliente'])){
      session_start();
      $_SESSION['id_cliente'] = $_GET['sesion_cliente'];
      echo json_encode(["data" => "variable de sesion creada = ".$_SESSION['id_cliente']]);  
   }

   if(isset($_GET['all_data_administrador'])){

      $data = json_decode(file_get_contents('php://input'));
      session_start();
      $_SESSION['data_admin'] = array();
      array_push($_SESSION['data_admin'], $data->data->id_usuario);
      array_push($_SESSION['data_admin'], $data->data->nombre);
      array_push($_SESSION['data_admin'], $data->data->apellido);
      array_push($_SESSION['data_admin'], $data->data->usuario);
      array_push($_SESSION['data_admin'], $data->data->contrasenia);
      array_push($_SESSION['data_admin'], $data->data->p_pregunta_seguridad);
      array_push($_SESSION['data_admin'], $data->data->p_respuesta_seguridad);
      array_push($_SESSION['data_admin'], $data->data->s_pregunta_seguridad);
      array_push($_SESSION['data_admin'], $data->data->s_respuesta_seguridad);
      echo json_encode(["data" => $_SESSION['data_admin']]);
   }

   if(isset($_GET['resumen_ventas'])){
      session_start();
      $_SESSION['resumen_ventas'] = $_GET['resumen_ventas'];
      echo json_encode(["data" => "variable de sesion creada = ".$_SESSION['resumen_ventas']]);
   }

   if(isset($_GET['id_pedido'])){
      session_start();
      $_SESSION['id_pedido'] = $_GET['id_pedido'];
      echo json_encode(["data" => "variable de sesion creada = ".$_SESSION['id_pedido']]);
   }

   if(isset($_GET['id_pedido_venta'])){
      session_start();
      $_SESSION['id_pedido_venta'] = $_GET['id_pedido_venta'];
      echo json_encode(["data" => "variable de sesion creada = ".$_SESSION['id_pedido_venta']]);
   }

   if(isset($_GET['id_categoria'])){
      session_start();
      $_SESSION['id_categoria'] = $_GET['id_categoria'];
      echo json_encode(["data" => "variable de sesion creada = ".$_SESSION['id_categoria']]);
   }
