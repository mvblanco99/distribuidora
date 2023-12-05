<?php 
	session_start();
	if(!isset($_SESSION['user_admin'])){
		echo "<script>window.location='index';</script>";
	}	
?>

<?php include "views/modules/notification_area.php"?>
<?php include "views/modules/nav.php" ?>
<?php include "views/modules/page_content.php"?>


	<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect" id="admin">
			
		<div class="mdl-tabs__tab-bar principal-tabs">
			<a href="registar_administrador" class="principal-tabs__a active acceso">Nuevo</a>
			<a href="lista_administradores" class="principal-tabs__a acceso">Lista</a>
		</div>

        <div class="mdl-tabs__panel is-active" id="tabNewAdmin">
			<div class="mdl-grid">
				<div class="mdl-cell mdl-cell--12-col">
					<div class="full-width panel mdl-shadow--2dp">
						<div class="full-width panel-tittle bg-primary text-center tittles">
							Nuevo Administrador
						</div>
						<div class="full-width panel-content">
							<form>
								<div class="mdl-grid">
									<div class="mdl-cell mdl-cell--12-col">
										<legend class="text-condensedLight"><i class="zmdi zmdi-border-color"></i> &nbsp; Datos del Administrador</legend><br>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input class="mdl-textfield__input" type="text" pattern="-?[A-Za-záéíóúÁÉÍÓÚ]*(\.[0-9]+)?" id="NameAdmin">
											<label class="mdl-textfield__label" for="NameAdmin">Nombre</label>
											<span class="mdl-textfield__error">Nombre Invalido</span>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input class="mdl-textfield__input" type="text" pattern="-?[A-Za-záéíóúÁÉÍÓÚ ]*(\.[0-9]+)?" id="LastNameAdmin">
											<label class="mdl-textfield__label" for="LastNameAdmin">Apellido</label>
											<span class="mdl-textfield__error">Apellido Invalido</span>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input class="mdl-textfield__input" type="text" pattern="-?[A-Za-záéíóúÁÉÍÓÚ _-ñ]*(\.[0-9]+)?" id="one_question_security">
											<label class="mdl-textfield__label" for="one_question_security">Pregunta de seguridad</label>
											<span class="mdl-textfield__error">Pregunta de Seguridad Invalida</span>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input class="mdl-textfield__input" type="text" pattern="-?[A-Za-záéíóúÁÉÍÓÚ _-ñ]*(\.[0-9]+)?" id="one_answer_security">
											<label class="mdl-textfield__label" for="one_answer_security">Respuesta de seguridad</label>
											<span class="mdl-textfield__error">Respuesta de Seguridad Invalida</span>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input class="mdl-textfield__input" type="text" pattern="-?[A-Za-záéíóúÁÉÍÓÚ _-ñ]*(\.[0-9]+)?" id="two_question_security">
											<label class="mdl-textfield__label" for="two_question_security">Pregunta de seguridad</label>
											<span class="mdl-textfield__error">Pregunta de Seguridad Invalida</span>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input class="mdl-textfield__input" type="text" pattern="-?[A-Za-záéíóúÁÉÍÓÚ _-ñ]*(\.[0-9]+)?" id="two_answer_security">
											<label class="mdl-textfield__label" for="two_answer_security">Respuesta de seguridad</label>
											<span class="mdl-textfield__error">Respuesta de Seguridad Invalida</span>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--12-col">
										<legend class="text-condensedLight"><i class="zmdi zmdi-border-color"></i> &nbsp; Detalles de la Cuenta</legend><br>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="text_UserNameAdmin">
											<input class="mdl-textfield__input" type="text" pattern="-?[A-Za-z0-9áéíóúÁÉÍÓÚ_-ñ]*(\.[0-9]+)?" id="UserNameAdmin">
											<label class="mdl-textfield__label" for="UserNameAdmin">Usuario</label>
											<span class="mdl-textfield__error">Invalid user name</span>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
											<input class="mdl-textfield__input" type="password" id="passwordAdmin" pattern="-?[A-Za-z0-9áéíóúÁÉÍÓÚ_-ñ]*(\.[0-9]+)?">
											<label class="mdl-textfield__label" for="passwordAdmin">Contraseña</label>
											<span class="mdl-textfield__error">Invalid password</span>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--6-col mdl-cell--8-col-tablet">
										<div class="mdl-textfield mdl-js-textfield" id="label_tipo_admin">
											<select class="mdl-textfield__input" id="tipo_admin" name="categoria">
												<option value="" disabled="" selected="">Seleccionar Tipo de Administrador</option>
												<option value="1">Super Administrador</option>
												<option value="2">Visualización</option>
												<option value="3">Operador de Ventas</option>
												<option value="4">Registros</option>
											</select>
										</div>
									</div>
									<div class="mdl-cell mdl-cell--12-col">
										<legend class="text-condensedLight"><i class="zmdi zmdi-border-color"></i> &nbsp; Elige un Avatar</legend><br>
									</div>
									<div class="mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet">
										<div class="mdl-grid">
											<div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone">
												<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-1">
													<input type="radio" id="option-1" class="mdl-radio__button" name="options" value="avatar-male.png">
													<img src="views/assets/img/avatar-male.png" alt="avatar" style="height: 45px; width:45px;">
													<span class="mdl-radio__label">Avatar 1</span>
												</label>
											</div>
											<div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone">
												<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-2">
													<input type="radio" id="option-2" class="mdl-radio__button" name="options" value="avatar-female.png" checked>
													<img src="views/assets/img/avatar-female.png" alt="avatar" style="height: 45px; width:45px;" >
													<span class="mdl-radio__label">Avatar 2</span>
												</label>
											</div>
											<div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone">
												<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-3">
													<input type="radio" id="option-3" class="mdl-radio__button" name="options" value="avatar-male2.png">
													<img src="views/assets/img/avatar-male2.png" alt="avatar" style="height: 45px; width:45px;" >
													<span class="mdl-radio__label">Avatar 3</span>
												</label>
											</div>
											<div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--4-col-phone">
												<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-4">
													<input type="radio" id="option-4" class="mdl-radio__button" name="options" value="avatar-female2.png">
													<img src="views/assets/img/avatar-female2.png" alt="avatar" style="height: 45px; width:45px;">
													<span class="mdl-radio__label">Avatar 4</span>
												</label>
											</div>
										</div>
									</div>
								</div>
								<p class="text-center">
									<button class="mdl-button mdl-js-button mdl-button--fab  mdl-button--colored bg-primary" type="button" id="add-admin">
										<i class="zmdi zmdi-plus"></i>
									</button>
									<div class="mdl-tooltip" for="btn-addAdmin">Add Administrator</div>
								</p>
							</form>
						</div>
					</div>
				</div>
			</div>
		<!-- Cerramos el tabNewAdmin -->
		</div>
		
	</div>
<!-- Cerramos el Page Content -->
</section>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/registrar_administrador.js" type="module"></script>';
?>

