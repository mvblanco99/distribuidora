<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Administrador</title>
	<link rel="stylesheet" href="views/css/normalize.css">
	<link rel="stylesheet" href="views/css/sweetalert2.css">
	<link rel="stylesheet" href="views/css/material.min.css">
	<link rel="stylesheet" href="views/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="views/css/jquery.mCustomScrollbar.css">
	<link rel="stylesheet" href="views/css/main.css">
	<link rel="stylesheet" href="views/css/css-propio.css">
	<link rel="stylesheet" href="views/css/bootstrap-5.3.0-alpha1-dist/css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="views/js/jquery-1.11.2.min.js"><\/script>')</script>
	<script src="views/js/material.min.js" ></script>
	<script src="views/js/sweetalert2.min.js" ></script>
	<script src="views/js/jquery.mCustomScrollbar.concat.min.js" ></script>
	<script src="views/js/main.js"></script>
	
</head>
<body>
	
        <?php
            $modules = new Controlador_links();
            $modules->controlador_link();
        ?>

<!-- <script src="views/js/bootstrap-5.3.0-alpha1-dist/js/bootstrap.min.js"></script>
<script src="views/js/urls.js"></script>
<script src="views/js/utils.js"></script>
<script src="views/js/validaciones.js" type="module"></script>
<script src="views/js/controlador_acceso.js" type="module"></script>
<script src="views/js/controlador_acciones.js" type="module"></script>
<script src="views/js/nav.js" type="module"></script> -->
<!-- <script src="views/js/verificar_administrador.js" type="module"></script>
<script src="views/js/preguntas_seguridad.js" type="module"></script>
<script src="views/js/modificar_contrasenia_administrador.js" type="module"></script> -->
<!-- <script src="views/js/lista_administradores.js" type="module"></script> -->
<!-- <script src="views/js/registrar_administrador.js" type="module"></script> -->
<!-- <script src="views/js/modificar_administrador.js" type="module"></script> -->
<!-- <script src="views/js/lista_productos.js" type="module"></script> -->
<!-- <script src="views/js/lista_productos_venta.js" type="module"></script> -->
<!-- <script src="views/js/registrar_productos.js" type="module"></script> -->
<!-- <script src="views/js/modificar_productos.js" type="module"></script> -->
<!-- <script src="views/js/compra.js" type="module"></script> -->
<!-- <script src="views/js/compra_productos.js" type="module"></script> -->
<!-- <script src="views/js/lista_compras.js" type="module"></script> -->
<!-- <script src="views/js/lista_compras_total.js" type="module"></script> -->
<!-- <script src="views/js/modificar_compra.js" type="module"></script> -->
<!-- <script src="views/js/modificar_compra_productos.js" type="module"></script> -->
<!-- <script src="views/js/visualizar_compra.js" type="module"></script> -->
<!-- <script src="views/js/inventario.js" type="module"></script> -->
<!-- <script src="views/js/lista_cliente.js" type="module"></script> -->
<!-- <script src="views/js/visualizar_cliente.js" type="module"></script> -->
<!-- <script src="views/js/ventas.js" type="module"></script>
<script src="views/js/venta_pedido.js" type="module"></script> -->
<!-- <script src="views/js/resumen_ventas.js" type="module"></script> -->
<!-- <script src="views/js/visualizar_resumen_venta.js" type="module"></script> -->
</body>
</html>