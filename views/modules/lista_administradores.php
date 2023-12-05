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
			<a href="registrar_administrador" class="principal-tabs__a acceso">Nuevo</a>
			<a href="lista_administradores" class="principal-tabs__a active acceso">Lista</a>
		</div>

        <div class="mdl-tabs__panel is-active" id="tabListAdmin">
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--8-col-desktop mdl-cell--2-offset-desktop">
                    <div class="full-width panel mdl-shadow--2dp">
                        <div class="full-width panel-tittle bg-success text-center tittles">
                            Lista de Administratores
                        </div>
                        <div class="full-width panel-content">
                            <form action="#">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
                                    <label class="mdl-button mdl-js-button mdl-button--icon" for="searchAdmin" id="buscador2">
                                        <i class="zmdi zmdi-search" id="buscador"></i>
                                    </label>
                                    <div class="mdl-textfield__expandable-holder">
                                        <input class="mdl-textfield__input" type="text" pattern="-?[A-Za-z0-9áéíóúÁÉÍÓÚ_-ñ]*(\.[0-9]+)?" id="searchAdmin" placeholder="Ingrese Usuario">
                                        <label class="mdl-textfield__label"></label>
                                    </div>
                                </div>
                            </form>
                            <div class="mdl-list" id="mdl-list"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Cerramos el tabListAdmin -->
		</div>
	
    </div>
<!-- Cerramos el Page Content -->
</section>

<template id = "template-item-administrador">
    <div class="mdl-list__item mdl-list__item--two-line">
        <span class="mdl-list__item-primary-content">
            <i class="zmdi zmdi-account mdl-list__item-avatar"></i>
            <span id="name-admin"></span>
            <span class="mdl-list__item-sub-title" id="span-user-admin"></span>
        </span>
        <a class="mdl-list__item-secondary-action" href="#!"><i class="zmdi zmdi-more"></i></a>
    </div>
    <li class="full-width divider-menu-h"></li>
</template>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/lista_administradores.js" type="module"></script>';
?>

