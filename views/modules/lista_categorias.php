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
		<a href="categoria" class="principal-tabs__a">Nueva Categoría</a>
		<a href="lista_categorias" class="principal-tabs__a active">Lista de Categorías</a>
	</div>

	<div class="mdl-tabs__panel" id="tabListCategory">
		<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--8-col-desktop mdl-cell--2-offset-desktop">
				<div class="full-width panel mdl-shadow--2dp">
					<div class="full-width panel-tittle bg-success text-center tittles">
						Lista de Categorías
					</div>
					<div class="full-width panel-content">
						<form action="#">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
								<label class="mdl-button mdl-js-button mdl-button--icon" for="searchCategory">
									<i class="zmdi zmdi-search"></i>
								</label>
								<div class="mdl-textfield__expandable-holder">
									<input class="mdl-textfield__input" type="text" id="searchCategory">
									<label class="mdl-textfield__label"></label>
								</div>
							</div>
						</form>
						<div class="mdl-list" id="body_categories">
							
							
							
						</div>
					</div>
				</div>
			
			</div>
		</div>
	</div>
</section>

<template id="item_categoria">
	<li class="full-width divider-menu-h"></li>
	<div class="mdl-list__item mdl-list__item--two-line">
		<span class="mdl-list__item-primary-content">
			<i class="zmdi zmdi-label mdl-list__item-avatar"></i>
			<span class="name_categoria">1. Category Name</span>
			<span class="mdl-list__item-sub-title subtittle">Sub tittle</span>
		</span>
		<a class="mdl-list__item-secondary-action" href="#!"><i class="zmdi zmdi-more"></i></a>
	</div>
</template>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/lista_categorias.js" type="module"></script>';
?>