<?php 
	session_start();
	if(!isset($_SESSION['user_admin'])){
		echo "<script>window.location='index';</script>";
	}	
?>

<?php include "views/modules/notification_area.php"?>
<?php include "views/modules/nav.php" ?>
<?php include "views/modules/page_content.php"?>

	<div class="mdl-tabs__tab-bar">
		<a href="ventas" class="principal-tabs__a active">Venta de Productos</a>
	</div>

	<div class="full-width divider-menu-h"></div>
		<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--12-col">
				<div class="full-width panel mdl-shadow--2dp">
					<div class="full-width panel-tittle bg-primary text-center tittles">
						Ventas
					</div>
					<div class="full-width panel-content" id="venta_productos">
						<form>
							<div class="mdl-grid">
								<div class="mdl-cell mdl-cell--12-col">
		                            <legend class="text-condensedLight"><i class="zmdi zmdi-border-color"></i> &nbsp; Datos de la venta</legend><br>
		                        </div>
								<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="label_codigo_producto">
										<input class="mdl-textfield__input" type="number" pattern="-?[0-9]*(\.[0-9]+)?" id="codigo_producto">
										<label class="mdl-textfield__label" for="codigo_producto">Ingresar Código Producto</label>
										<span class="mdl-textfield__error">Código de Producto Invalido</span>
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
								<div class="form-group" style="display: flex; justify-content: center;">
									<button type='button' class="btn btn-primary" id="btn-add-list" style="font-family: OswaldLight; font-size: 17px;">
										Agregar Producto
									</button>
								</div>
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
													<th class="mdl-data-table__cell--non-numeric" style="color:black;">Nombre Producto</th>
													<th style="text-align: center;color:black;">Cantidad</th>
													<th style="text-align: center;color:black;">Precio unit</th>
													<th style="text-align: center;color:black">subtotal</th>					
													<th style="text-align: center;color:black;">% Iva</th>
													<th style="text-align: center;color:black;">Total</th>
													<th></th>
												</tr>
											</thead>
											<tbody id="lista_venta_productos"></tbody>
										</table>
									</div>
								</div>
							</div>
							<p class="text-center">
								<div class="form-group" style="display: flex; justify-content: center;">
									<button class="btn btn-primary" id="btn_concretar_venta" style="font-family: OswaldLight; font-size: 17px;" type="button">
										Concretar Venta
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
		<td class="mdl-data-table__cell--non-numeric name_producto" style="font-weight:500">Product Name</td>
		<td class="stock" style="text-align: center; font-weight:500">7</td>
		<td class="price" style="text-align: center; font-weight:500">$77</td>
		<td class="subtotal" style="text-align: center; font-weight:500"></td>
		<td class="iva" style="text-align: center;  font-weight:500"></td>
		<td class="total" style="text-align: center;  font-weight:500"></td>
		<td>
			<button class="btn btn-danger borrar id_pedido" type="button" style="color: white; font-family: OswaldLight;">
				<small class="borrar">Borrar</small>
			</button>
		</td>
	</tr>
</template>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/ventas.js" type="module"></script>';
?>
