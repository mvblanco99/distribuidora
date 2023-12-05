<?php include "views/modules/notification_area.php"?>
<?php include "views/modules/nav.php" ?>
<?php include "views/modules/page_content.php"?>


	<div class="full-width divider-menu-h"></div>
	
	<div class="mdl-grid" style="display: flex;">
		
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable" style="width: 40%;">
			<label class="mdl-button mdl-js-button mdl-button--icon" for="searchProduct" id="buscador2">
				<i class="zmdi zmdi-search" id="buscador"></i>
			</label>
			<div class="mdl-textfield__expandable-holder">
				<input class="mdl-textfield__input buscadorcito" type="text" pattern="-?[0-9- ]*(\.[0-9]+)?" id="searchProduct" placeholder="Nombre de Producto">
				<label class="mdl-textfield__label"></label>
			</div>
		</div>

		<div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet" style="width: 50%;padding-top: 10px;">

			<div class="form-group" id="label_presentacion" style="display: flex;">
				<label for="categorias" style="width: 40%; padding-top: 5px; font-weight: bold;">Filtrar Por Categoría:</label>
				<br>
				<select class="form-control" id="categorias" name="categorias">
					<option value="" disabled="" selected="">Seleccionar Categoría</option>
				</select>
			</div>

		</div>
	</div>

	<div class="mdl-grid" id="inventario">

		<div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
			<div class="full-width panel-tittle bg-primary text-center tittles">
				Inventario
			</div>	
			<div class="table-responsive">
				<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp full-width table-responsive">
					<thead>
						<tr>
							<th class="mdl-data-table__cell--non-numeric" style="font-weight: bold; color:black">Nombre de Producto</th>
							<th class="mdl-data-table__cell--non-numeric" style="font-weight: bold; color:black">Existencias</th>
							<th class="mdl-data-table__cell--non-numeric" style="font-weight: bold; color:black">Precio</th>
						</tr>
					</thead>
					<tbody id="lista_productos_inventario"></tbody>
				</table>
			</div>
		</div>
	</div>

	<p class="text-center">
		<div class="form-group" style="display: flex; justify-content: center;">
			<button class="btn btn-primary ejecutar" id="btn_actualizar_inventario" style="font-family: OswaldLight; font-size: 17px;">
				Actualizar Inventario
			</button>
		</div>
	</p>
</section>

<template id="template_items_productos_inventario">
	<tr>
		<td class="mdl-data-table__cell--non-numeric name_product" style="font-weight:500">Product Name</td>
		<td class="mdl-data-table__cell--non-numeric stock" style="font-weight:500; padding-left:3.5%;">7</td>
		<td class="mdl-data-table__cell--non-numeric price" style="font-weight:500;">$77</td>
	</tr>
</template>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/inventario.js" type="module"></script>';
?>
