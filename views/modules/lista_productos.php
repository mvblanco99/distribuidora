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

        <div class="mdl-tabs__panel is-active" id="tabListProducts">
			<div class="mdl-grid">
				<div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
					<form action="#">
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
							<label class="mdl-button mdl-js-button mdl-button--icon" for="searchProduct" id="buscador2">
								<i class="zmdi zmdi-search" id="buscador"></i>
							</label>
							<div class="mdl-textfield__expandable-holder">
								<input class="mdl-textfield__input buscadorcito" type="text" pattern="-?[0-9- ]*(\.[0-9]+)?" id="searchProduct" placeholder="Nombre de Producto">
								<label class="mdl-textfield__label"></label>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="mdl-grid">
				<div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
					<div class="table-responsive">				
						<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp full-width table-responsive">
							<thead>
								<tr>
									<th class="mdl-data-table__cell--non-numeric" style="font-weight: bold;color:black">Nombre de Producto</th>
									<th style="text-align: center; font-weight: bold;color:black">Contenido Neto</th>
									<th style="text-align: center; font-weight: bold;color:black">Precio</th>
									<th style="text-align: center; font-weight: bold;color:black"></th>
								</tr>
							</thead>
							
							<tbody id="lista_productos"></tbody>

						</table>
					</div>
				</div>
			</div>
        </div><!-- Cerramos el tabListProducts -->
    </div><!-- Cerramos el Page Content -->
</section>

<template id="items_lista_productos">
	<tr class="id">
		<td class="mdl-data-table__cell--non-numeric nombre_producto">Nombre de Producto</td>
		<td class="contenido_neto" style="text-align: center;">Contenido Neto</td>
		<td class="precio" style="text-align: center;">Precio</td>
		<td class='acciones'>
			<div class="form-group">
				<button class="btn btn-info btn-sm" type="button" style="color: white;">Modificar</button>
				<button class="btn btn-danger btn-sm" type="button">Borrar</button>
			</div>
		</td>
	</tr>
</template>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/lista_productos.js" type="module"></script>';
?>

