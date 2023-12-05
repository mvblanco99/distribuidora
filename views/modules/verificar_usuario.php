<div class="login-wrap cover">
    <div class="container-login">
        <p class="text-center" style="font-size: 80px;">
            <i class="zmdi zmdi-account-circle"></i>
        </p>
        <p class="text-center text-condensedLight">Verificar Usuario</p>
        <form action="" method="POST">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input 
                    class="mdl-textfield__input" 
                    type="text" 
                    id="searchAdmin"
                    name = "searchAdmin"
                    pattern="-?[A-Za-z0-9áéíóúÁÉÍÓÚ_-ñ]*(\.[0-9]+)?" 
                    required
                >
                <label class="mdl-textfield__label" for="searchAdmin">Usuario</label>
            </div>
            <div id="contenedor_button_verificar_usuario">
                <button 
                    class="mdl-button mdl-js-button mdl-js-ripple-effect" 
                    style="color: #3F51B5; margin: 0 auto; display: block; text-transform: lowercase;" 
                    type="submit">
                    <span style="text-transform: uppercase;">C</span>ontinuar
                </button>
                <button 
                    class="mdl-button mdl-js-button mdl-js-ripple-effect" 
                    style="color: #3F51B5; margin: 0 auto; display: block; text-transform: lowercase;" 
                    type="button"
                    onclick="window.location='index'"
                    >
                    <span style="text-transform: uppercase;">C</span>ancelar
                </button>
            </div>
        </form>
    </div> 
</div>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/verificar_usuario.js" type="module"></script>';
?>

