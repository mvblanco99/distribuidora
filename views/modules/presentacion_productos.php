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
			<a href="presentacion_productos" class="principal-tabs__a active">Nueva Tipo de Presentación</a>
			<a href="lista_presentacion" class="principal-tabs__a">Lista de Presentación</a>
		</div>

		<div class="mdl-tabs__panel is-active" id="tabNewCategory">
			<div class="mdl-grid">
				<div class="mdl-cell mdl-cell--12-col">
					<div class="full-width panel mdl-shadow--2dp">
						<div class="full-width panel-tittle bg-primary text-center tittles">
							Presentación Producto
						</div>
						<div class="full-width panel-content">
							<form>
								<div class="mdl-grid">
									<div class="mdl-cell mdl-cell--12-col">
										<legend class="text-condensedLight"><i class="zmdi zmdi-border-color"></i> &nbsp; Datos de la Presentación</legend><br>
									</div>

									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input class="mdl-textfield__input data-categoria" type="text" pattern="-?[A-Za-záéíóúÁÉÍÓÚ]*(\.[0-9]+)?" id="NameCategory">
											<label class="mdl-textfield__label" for="NameCategory">Nombre Presentación</label>
											<span class="mdl-textfield__error">Nombre de Presentación Invalido</span>
										</div>
									</div>
								</div>

								<p class="text-center">
									<div class="form-group" style="display: flex; justify-content: center;">
										<button class="btn btn-primary" id="btn_registrar_datos" style="font-family: OswaldLight; font-size: 17px;" type="submit">
											Registrar Presentación
										</button>
									</div>
								</p>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		
	</div>

</section>
<!-- Cerramos el Page Content -->
<?php 
    include 'importScripts.php';
    echo '<script src="views/js/bancos.js" type="module"></script>';
?>