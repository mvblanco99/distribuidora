<div class="login-wrap cover">
    <div class="container-login">
        <p class="text-center" style="font-size: 80px;">
            <i class="zmdi zmdi-account-circle"></i>
        </p>
        <p class="text-center text-condensedLight">Preguntas de Seguridad</p>
        <form action="" method="POST" id="preguntas_seguridad">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input 
                    class="mdl-textfield__input" 
                    type="text" 
                    id="p_respuesta"
                    name = "p_respuesta"
                    pattern="-?[A-Za-záéíóúÁÉÍÓÚ _-ñ0-9]*(\.[0-9]+)?" 
                    required
                >
                <label class="mdl-textfield__label" for="p_respuesta" id="label_primer_pregunta"></label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input 
                    class="mdl-textfield__input" 
                    type="text" 
                    id="s_respuesta"
                    name = "s_respuesta"
                    pattern="-?[A-Za-záéíóúÁÉÍÓÚ _-ñ0-9]*(\.[0-9]+)?" 
                    required
                >
                <label class="mdl-textfield__label" for="s_respuesta" id="label_segunda_pregunta"></label>
            </div>
            <button 
                class="mdl-button mdl-js-button mdl-js-ripple-effect" 
                style="color: #3F51B5; margin: 0 auto; display: block;" 
                type="submit">
                Continuar
            </button>
        </form>
    </div> 
</div>

<?php 
    include 'importScripts.php';
    echo '<script src="views/js/preguntas_seguridad.js" type="module"></script>';
?>