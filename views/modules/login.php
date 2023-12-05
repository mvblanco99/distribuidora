<div class="login-wrap cover">
    <div class="container-login">
        <p class="text-center" style="font-size: 80px;">
            <i class="zmdi zmdi-account-circle"></i>
        </p>
        <p class="text-center text-condensedLight">Sign in with your Account</p>
        <form action="" method="POST">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input 
                    class="mdl-textfield__input" 
                    type="text" 
                    id="userName"
                    name = "userName" 
                    required
                >
                <label class="mdl-textfield__label" for="userName">Usuario</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input 
                    class="mdl-textfield__input" 
                    type="password" 
                    id="password"
                    name="password"
                    required
                >
                <label class="mdl-textfield__label" for="password">Contraseña</label>
            </div>
            <button 
                class="mdl-button mdl-js-button mdl-js-ripple-effect" 
                style="color: #3F51B5; margin: 0 auto; display: block;" 
                type="submit">
                SIGN IN
            </button>
            <button 
                class="mdl-button mdl-js-button mdl-js-ripple-effect" 
                style="color: #3F51B5; margin: 0 auto; display: block; text-transform: lowercase;" 
                type="button"
                onclick="window.location='verificar_usuario'">
                ¿<span style="text-transform: uppercase;">O</span>lvidaste tu contraseña?
            </button>

            <?php accesar();?>
        </form>
    </div> 
</div>