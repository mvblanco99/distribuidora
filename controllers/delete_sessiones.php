<?php

if(isset($_GET['delete_id_administrador_session'])){
    session_start();
    if(isset($_SESSION['administrador'])){
        unset($_SESSION['administrador']);
        echo json_encode(["data" => "Session borrada exitosamente"]);
    }
}

if(isset($_GET['cerrarSesion'])){
    session_start();
    if(isset($_SESSION['user_admin'])){
        session_destroy();
        echo json_encode(["data" => "Session borrada exitosamente"]);
    }
}

if(isset($_GET['delete_id_pedido_venta'])){
    session_start();
    if(isset($_SESSION['id_pedido_venta'])){
        unset($_SESSION['id_pedido_venta']);
        echo json_encode(["data" => "Session borrada exitosamente"]);
    }
}