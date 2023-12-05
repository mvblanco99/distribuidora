<?php

    include "../controllers/validaciones.php";

if(isset($_GET['user_admin'])){
    session_start();
    if(isset($_SESSION['user_admin'])){
        $id_administrador = $_SESSION['user_admin'];
        echo json_encode([$id_administrador]);
    }
}

if(isset($_GET['extraer_id_administrador_session'])){
    session_start();
    if(isset($_SESSION['administrador'])){
        $id_administrador = $_SESSION['administrador'];
        echo json_encode($id_administrador);
    }
}

if(isset($_GET['extraer_id_producto_session'])){
    session_start();
    if(isset($_SESSION['producto'])){
        $id_producto = $_SESSION['producto'];
        echo json_encode([$id_producto]);
    }
}

if(isset($_GET['extraer_id_compra_session'])){
    session_start();
    if(isset($_SESSION['compra'])){
        $id_compra = $_SESSION['compra'];
        echo json_encode($_SESSION['compra']);
    }
}

if(isset($_GET['extraer_id_visualizar_compra'])){
    session_start();
    if(isset($_SESSION['visualizar_compra'])){
        $datos_compra = $_SESSION['visualizar_compra'];
        echo json_encode($datos_compra);
    }
}

if(isset($_GET['extraer_id_modificar_compra'])){
    session_start();
    if(isset($_SESSION['id_modificar_compra'])){
        $datos_compra = $_SESSION['id_modificar_compra'];
        echo json_encode($datos_compra);
    }
}

if(isset($_GET['extraer_id_cliente_session'])){
    session_start();
    if(isset($_SESSION['id_cliente'])){
        $id_cliente = $_SESSION['id_cliente'];
        echo json_encode($id_cliente);
    }
}

if(isset($_GET['extraer_all_data_admin'])){
    session_start();
    if(isset($_SESSION['data_admin'])){
        $data_admin = $_SESSION['data_admin'];
        echo json_encode($data_admin);
    }
}

if(isset($_GET['extraer_id_resumen_venta'])){
    session_start();
    if(isset($_SESSION['resumen_ventas'])){
        $data_id_venta = $_SESSION['resumen_ventas'];
        echo json_encode($data_id_venta);
    }
}

if(isset($_GET['extraer_id_pedido'])){
    session_start();
    if(isset($_SESSION['id_pedido'])){
        $data_id_pedido = $_SESSION['id_pedido'];
        echo json_encode($data_id_pedido);
    }
}

if(isset($_GET['extraer_id_pedido_venta'])){
    session_start();
    if(isset($_SESSION['id_pedido_venta'])){
        $data_id_pedido_venta = $_SESSION['id_pedido_venta'];
        echo json_encode($data_id_pedido_venta);
    }
}

if(isset($_GET['extraer_id_categoria'])){
    session_start();
    if(isset($_SESSION['id_categoria'])){
        $data_id_categoria = $_SESSION['id_categoria'];
        echo json_encode($data_id_categoria);
    }
}