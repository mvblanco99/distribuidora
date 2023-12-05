<?php

    #Controladores
    require_once "./controllers/template.php";
    require_once "./controllers/controlador_links.php";
    require_once "./controllers/login.php";

    #Modelos
    require_once "./models/modelo_links.php";
    require_once "./models/login.php";
    
    // Creamos una instancia del controlador de los templates
    $template = new TemplateController();
    $template -> template();