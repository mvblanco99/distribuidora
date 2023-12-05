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
		<a href="compra" class="principal-tabs__a active acceso">Nueva Compra</a>
		<a href="lista_compra_disponible" class="principal-tabs__a acceso">Lista de Compras</a>
	</div>

	<div class="full-width divider-menu-h"></div>
	
		<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--12-col">
				<div class="full-width panel mdl-shadow--2dp">
					<div class="full-width panel-tittle bg-primary text-center tittles">
						Compras
					</div>
					<div class="full-width panel-content">
						<form>
							<div class="mdl-grid">
								<div class="mdl-cell mdl-cell--12-col">
		                            <legend class="text-condensedLight"><i class="zmdi zmdi-border-color"></i> &nbsp; Datos de la Compra</legend><br>
		                        </div>
								<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
										<input class="mdl-textfield__input" type="text" pattern="-?[A-Za-z0-9]*(\.[0-9]+)?" id="numero_factura">
										<label class="mdl-textfield__label" for="numero_factura">Número de Factura</label>
										<span class="mdl-textfield__error">Número de Factura Invalida</span>
									</div>
								</div>
								<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
										<input class="mdl-textfield__input" type="text" pattern="-?[0-9-]*(\.[0-9]+)?" id="numero_control">
										<label class="mdl-textfield__label" for="numero_control">Número de Control</label>
										<span class="mdl-textfield__error">Número de Control Inválido</span>
									</div>
								</div>
								<div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
										<input class="mdl-textfield__input" type="text" id="nombre_proveedor">
										<label class="mdl-textfield__label" for="nombre_proveedor">Nombre de Proveedor</label>
										<span class="mdl-textfield__error">Nombre de Proveedor Inválido</span>
									</div>
								</div>
								<div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input class="mdl-textfield__input" type="text" pattern="-?[0-9.]*(\.[0-9]+)?" id="precio_compra" name="precio_compra">
											<label class="mdl-textfield__label" for="precio_compra">Monto Total de la Compra</label>
											<span class="mdl-textfield__error">Monto Total de la Compra Inválido</span>
										</div>
									</div>
								<div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet">
									<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
										<input class="mdl-textfield__input" type="date" id="fecha_compra">
										<label class="mdl-textfield__label" for="fecha_compra" style="display: none;" id="">Fecha Ingreso</label>
										<span class="mdl-textfield__error">Fecha Inválida</span>
									</div>
								</div>
							</div>
							<p class="text-center">
								<div class="form-group" style="display: flex; justify-content: center;">
									<button type='button' class="btn btn-primary" id="btn_registrar_compra" style="font-family: OswaldLight; font-size: 17px;">
										Registrar Compra
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

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/compra.js" type="module"></script>';
?>
