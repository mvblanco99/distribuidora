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
			<a href="registrar_productos" class="principal-tabs__a active acceso">Nuevo Producto</a>
			<a href="lista_productos" class="principal-tabs__a acceso">Lista de Productos</a>
		</div>

        <div class="mdl-tabs__panel is-active" id="tabNewProduct">
			<div class="mdl-grid">
				<div class="mdl-cell mdl-cell--12-col">
					<div class="full-width panel mdl-shadow--2dp">
						<div class="full-width panel-tittle bg-primary text-center tittles">
							Nuevo Producto
						</div>
						<div class="full-width panel-content">
							<form>
								<div class="mdl-grid">
									<div class="mdl-cell mdl-cell--12-col">
										<legend class="text-condensedLight"><i class="zmdi zmdi-border-color"></i> &nbsp; Información del Producto</legend><br>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="label_codigo_producto">
											<input class="mdl-textfield__input" type="number" pattern="-?[0-9- ]*(\.[0-9]+)?" id="BarCode" name="BarCode">
											<label class="mdl-textfield__label" for="BarCode">Código de Producto</label>
											<span class="mdl-textfield__error">Código de Producto Inválido</span>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input class="mdl-textfield__input" type="text" pattern="-?[A-Za-z0-9áéíóúÁÉÍÓÚ ]*(\.[0-9]+)?" id="NameProduct" name="NameProduct">
											<label class="mdl-textfield__label" for="NameProduct">Nombre de Producto</label>
											<span class="mdl-textfield__error">Nombre del Producto Invalido</span>
										</div>
									</div>
                                    <div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input class="mdl-textfield__input" type="text" pattern="-?[0-9.]*(\.[0-9]+)?" id="contenidoNeto" name="contenidoNeto">
											<label class="mdl-textfield__label" for="contenidoNeto">Contenido Neto</label>
											<span class="mdl-textfield__error">Contenido Neto Inválido</span>
										</div>
									</div>
                                    <div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield" id="label_presentacion">
											<select class="mdl-textfield__input" id="presentacion" name="presentacion">
												<option value="" disabled="" selected="">Seleccionar Presentación</option>
											</select>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield" id="label_tipo_producto">
											<select class="mdl-textfield__input" id="tipo" name="tipo">
												<option value="" disabled="" selected="">Seleccionar tipo</option>
												<option value="1">Gravado</option>
												<option value="2">Exento</option>
											</select>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield" id="label_categoria">
											<select class="mdl-textfield__input" id="categoria" name="categoria">
												<option value="" disabled="" selected="">Seleccionar Categoria</option>
												<!-- <option value="1">Grabado</option>
												<option value="2">Excento</option> -->
											</select>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input class="mdl-textfield__input" type="text" pattern="-?[0-9.]*(\.[0-9]+)?" id="precio" name="precio">
											<label class="mdl-textfield__label" for="precio">Precio de Producto</label>
											<span class="mdl-textfield__error">Precio del Producto Invalido</span>
										</div>
									</div>
								</div>

								<p class="text-center">
									<div class="form-group" style="display: flex; justify-content: center;">
										<button type='button' class="btn btn-primary" id="btn_registrar_producto" style="font-family: OswaldLight; font-size: 17px;">
											Registrar Producto
										</button>
									</div>
								</p>
							</form>
						</div>
					</div>
				</div>
			</div>
        <!-- Cerramos el tabNewProduct -->
		</div>
    
    </div>
<!-- Cerramos el Page content -->
</section>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/registrar_productos.js" type="module"></script>';
?>
