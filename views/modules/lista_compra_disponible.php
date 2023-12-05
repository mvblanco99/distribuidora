<?php 
	session_start();
	if(!isset($_SESSION['user_admin'])){
		echo "<script>window.location='index';</script>";
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
						Lista Compras Diario
					</div>
					<div class="full-width panel-content" id="container_resumen_compras">
						<form>
							<div class="mdl-grid">
								
								<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
									<!-- Aqui va el setect date -->
									<div class = "form-group">
										<label for="fecha_resumen">Seleccione la fecha:</label>
										<input type="date" id="fecha_resumen" name="fecha_resumen" class="form-control">
									</div>
								</div>
								
							</div>
							
							<div class="mdl-grid">
								<div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
									<div class="table-responsive">
										
										<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp full-width table-responsive">
											<thead>
												<tr>
													<th class="mdl-data-table__cell--non-numeric" style="font-weight: bold;color:black">Código de Factura</th>
													<th style="text-align: center; font-weight: bold;color:black">Número de Control</th>
													<th style="text-align: center; font-weight: bold;color:black">Monto Total Compra</th>
													<th style="text-align: center; font-weight: bold;color:black"></th>
												</tr>
											</thead>
											<tbody id="lista_compras"></tbody>
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

<template id="items_lista_resumen_compras">
	<tr class="id">
		<td class="mdl-data-table__cell--non-numeric codigo_factura">Código de Factura</td>
		<td class="numero_control" style="text-align: center;">Cliente</td>
		<td class="monto_total" style="text-align: center;">Monto Total Venta</td>
		<td class='acciones'>
			<div class="form-group">
				<button class="btn btn-primary btn-sm" style="color: white;" type="button">Visualizar</button>
				<button class="btn btn-info btn-sm" style="color: white;" type="button">Modificar</button>
				<button class="btn btn-danger btn-sm" type="button">Eliminar</button>
			</div>
		</td>
	</tr>
</template>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/lista_compras.js" type="module"></script>';
?>
