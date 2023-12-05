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
        <a href="compra" class="principal-tabs__a acceso">Nueva Compra</a>
        <a href="lista_compra_disponible" class="principal-tabs__a accesso">Lista de Compras</a>
    </div>

	<div class="full-width divider-menu-h"></div>
		<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--12-col">
				<div class="full-width panel mdl-shadow--2dp">
					<div class="full-width panel-tittle bg-primary text-center tittles">
						Datos de la Compra
					</div>
					<div class="full-width panel-content" id="visualizar_compra">
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
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="label_numero_control">
										<input class="mdl-textfield__input" type="text" id="numero_control" pattern="-?[0-9-]*(\.[0-9-]+)?"readonly>
										<label class="mdl-textfield__label" for="numero_control">Número de Control</label>
										<span class="mdl-textfield__error">Número de Control Inválido</span>
									</div>
								</div>
								<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="label_nombre_proveedor">
										<input class="mdl-textfield__input" type="text" pattern="-?[A-Za-z0-9áéíóúÁÉÍÓÚ ]*(\.[0-9]+)?" id="nombre_proveedor" readonly>
										<label class="mdl-textfield__label" for="nombre_proveedor">Nombre Proveedor</label>
										<span class="mdl-textfield__error">Nombre Proveedor Inválido</span>
									</div>
								</div>
								<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="label_precio_compra">
										<input class="mdl-textfield__input" type="text" pattern="-?[0-9.]*(\.[0-9]+)?" id="precio_compra" readonly>
										<label class="mdl-textfield__label" for="precio_compra">Precio Total del Compra</label>
										<span class="mdl-textfield__error">Precio Total del Compra Inválido</span>
									</div>
								</div>
								<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
										<input class="mdl-textfield__input" type="date" id="fecha_compra" readonly>
										<label class="mdl-textfield__label" for="fecha_compra" style="display: none;" id="">Fecha Ingreso</label>
										<span class="mdl-textfield__error">Fecha Inválida</span>
									</div>
								</div>
								
								
							</div>
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
												</tr>
											</thead>
											<tbody id="lista_compra_productos"></tbody>
										</table>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<!-- Cerramos el Page content -->
</section>

<template id="template_items_lista_productos">
	<tr>
	<td class="mdl-data-table__cell--non-numeric name_producto"></td>
		<td class="stock" style="text-align: center;"></td>
		<td class="price" style="text-align: center;"></td>
		<td class="monto_producto" style="text-align: center;"></td>
	</tr>	
</template>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/visualizar_compra.js" type="module"></script>';
?>
