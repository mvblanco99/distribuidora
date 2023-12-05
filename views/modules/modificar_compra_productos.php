
<?php 
	session_start();
	if(!isset($_SESSION['user_admin'])){
		echo "<script>window.location='index';</script>";
	}else{
		if(!isset($_SESSION['id_modificar_compra'])){
			echo "<script>window.location='home';</script>";
		}
	}	
?>

<?php include "views/modules/notification_area.php"?>
<?php include "views/modules/nav.php" ?>
<?php include "views/modules/page_content.php"?>

	<div class="full-width divider-menu-h"></div>
		<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--12-col">
				<div class="full-width panel mdl-shadow--2dp">
					<div class="full-width panel-tittle bg-primary text-center tittles">
						Modificar Datos de la Compra
					</div>
					<div class="full-width panel-content" id="modificar_compra_productos">
						<form>
							<div class="mdl-grid">
								<div class="mdl-cell mdl-cell--12-col">
		                            <legend class="text-condensedLight"><i class="zmdi zmdi-border-color"></i> &nbsp; Datos de la Compra</legend><br>
		                        </div>
								<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="label_numero_factura">
										<input class="mdl-textfield__input" type="text" pattern="-?[A-Za-z0-9]*(\.[0-9]+)?" id="numero_factura" readonly>
										<label class="mdl-textfield__label" for="numero_factura">Número de Factura</label>
										<span class="mdl-textfield__error">Número de Factura Invalida</span>
									</div>
								</div>
								<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="label_codigo_producto">
										<input class="mdl-textfield__input" type="number" pattern="-?[0-9]*(\.[0-9]+)?" id="codigo_producto">
										<label class="mdl-textfield__label" for="codigo_producto">Ingresar Código Producto</label>
										<span class="mdl-textfield__error">Código de Producto Invalido</span>
									</div>
								</div>
								<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="label_precio">
										<input class="mdl-textfield__input" min=1 type="number" pattern="-?[0-9]*(\.[0-9]+)?" id="precio">
										<label class="mdl-textfield__label" for="precio">Precio de Compra</label>
										<span class="mdl-textfield__error">Precio Inválido</span>
									</div>
								</div>
								<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="label_cantidad">
										<input class="mdl-textfield__input" min=1 type="number" pattern="-?[0-9]*(\.[0-9]+)?" id="cantidad">
										<label class="mdl-textfield__label" for="cantidad">Cantidad</label>
										<span class="mdl-textfield__error">Cantidad Inválida</span>
									</div>
								</div>
							</div>
							<p class="text-center">
								<button class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored bg-primary" id="btn-add-list" type="button">
									<i class="zmdi zmdi-plus"></i>
								</button>
								<div class="mdl-tooltip" for="btn-add-list">Agregar Producto a la Lista</div>
							</p>
							<div class="mdl-grid">
								<div class="mdl-cell mdl-cell--12-col">
		                            <legend class="text-condensedLight"><i class="zmdi zmdi-border-color"></i> &nbsp; Productos</legend><br>
		                        </div>
								<div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
									<div class="table-responsive">
										<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp full-width table-responsive">
											<thead>
												<tr>
													<th class="mdl-data-table__cell--non-numeric" style="font-weight: bold;color:black">Nombre Producto</th>
													<th style="text-align: center;font-weight: bold;color:black">Cantidad</th>
													<th style="text-align: center;font-weight: bold;color:black">Precio</th>
													<th style="text-align: center;font-weight: bold;color:black">Monto</th>
													<th></th>
												</tr>
											</thead>
											<tbody id="lista_compra_productos"></tbody>
										</table>
									</div>
								</div>
							</div>
							<p class="text-center">
								<div class="form-group" style="display: flex; justify-content: center;">
									<button type='button' class="btn btn-primary" id="btn_modificar_compra" style="font-family: OswaldLight; font-size: 17px;">
										Modificar Compra
									</button>
								</div>
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>
	<!-- Cerramos el Page content -->
</section>

<template id="items_lista_productos">
	<tr class="id">
		<td class="mdl-data-table__cell--non-numeric name_producto"></td>
		<td class="stock" style="text-align: center;"></td>
		<td class="price" style="text-align: center;"></td>
		<td class="monto_producto" style="text-align: center;"></td>
		<td>
			<button class="btn btn-danger" type="button">
				Remover
			</button>
		</td>
	</tr>
</template>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/modificar_compra_productos.js" type="module"></script>';
?>




