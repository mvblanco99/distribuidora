<?php 
	session_start();
	if(!isset($_SESSION['user_admin'])){
		echo "<script>window.location='index';</script>";
	}	
?>

<?php include "views/modules/notification_area.php"?>
<?php include "views/modules/nav.php" ?>
<?php include "views/modules/page_content.php"?>

    <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
		
        <div class="mdl-tabs__tab-bar">
			<a href="registrar_productos" class="principal-tabs__a acceso">Nuevo Producto</a>
			<a href="lista_productos" class="principal-tabs__a active acceso">Lista de Productos</a>
		</div>

        <div class="mdl-tabs__panel is-active" id="tabListProductsVenta">
			<div class="mdl-grid">
				<div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
					<form action="#">
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
							<label class="mdl-button mdl-js-button mdl-button--icon" for="search_producto_venta" id="buscador2">
								<i class="zmdi zmdi-search" id="buscador"></i>
							</label>
							<div class="mdl-textfield__expandable-holder">
								<input class="mdl-textfield__input buscadorcito" type="text" pattern="-?[0-9- ]*(\.[0-9]+)?" id="search_producto_venta" placeholder="Nombre del Producto">
								<label class="mdl-textfield__label"></label>
							</div>
						</div>
					</form>
					<nav class="full-width menu-categories">
						<ul class="list-unstyle text-center">
							<li><a href="lista_productos" class="principal-tabs__a acceso">Productos Totales</a></li>
							<li><a href="lista_productos_venta" class="principal-tabs__a active acceso">Productos en Venta</a></li>
						</ul>
					</nav>
					<div class="full-width text-center" style="padding: 30px 0;" id="lista_productos_venta"></div>
				</div>
			</div>
		<!-- Cerramos el tabListProducts -->
        </div>
    
    </div>
<!-- Cerramos el Page Content -->
</section>

<template id="template_productos_venta">
	<div class="mdl-card mdl-shadow--2dp full-width product-card">
		<div class="mdl-card__title">
			<img src="views/assets/img/fontLogin.jpg" alt="product" class="img-responsive">
		</div>
		<div class="mdl-card__supporting-text">
			<small id="precio"></small><br>
			<small id="contenido_neto"></small>
		</div>
		<div class="mdl-card__actions mdl-card--border">
			<span id="name_producto"></span>
			<!-- <button class="mdl-button mdl-button--icon mdl-js-button btn_modificar" id="btn_modificar_producto">
				<i class="zmdi zmdi-more"></i>
			</button> -->
		</div>
	</div>
</template>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/lista_productos_venta.js" type="module"></script>';
?>
