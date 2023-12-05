<?php

    function accesar(){

        if(isset($_POST["userName"])){

            #preg_match = Realiza una comparacion con una expresion regular

            if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["userName"]) &&
            preg_match('/^[a-zA-Z0-9]+$/', $_POST["password"])){

               
                $datosUser = array("user" => $_POST["userName"],
                            "password" => $_POST["password"]);

                $data_user = extraer_data_user($datosUser['user']);

                if($data_user !== null){

                    $storedHash = $data_user['contrasenia'];

                        $verificacion_contrasenia = password_verify($_POST["password"],$storedHash);

                        if($verificacion_contrasenia){
                            #Creamos ua variable de sesion para mantener los datos del usuario
                            session_start();
                            $_SESSION['user_admin'] = $datosUser['user'];
                            echo '
                            <script type="text/javascript">

                            const guardarUsuarioLocalStorage = (datos) => {

                                if(typeof(Storage) === "undefined"){
                                    return;
                                }
                            
                                if(datos !== null || datos !== undefined){
                                    localStorage.setItem("datos_user",JSON.stringify(datos));
                                    window.location = "home";
                                }
                            }
                        
                            guardarUsuarioLocalStorage('.json_encode($data_user).')</script>';
                        }else{
                            echo "<div class='alert alert-danger' style='text-align: center;'>Datos Incorrectos</div>";
                        } 
                }else{
                    echo "<div class='alert alert-danger' style='text-align: center;'>Usuario no registrado</div>";
                }
            }else{
                echo "<div class='alert alert-danger' style='text-align: center;'>No debe ingresar caracteres especiales</div>";
            }
            
        }
    }