<?php
    
    Class Modelo_links{

        public function modelo_link($links){
            $module = null;

            if($links === "home" || 
                $links === "registrar_administrador" ||
                $links === "lista_administradores" ||
                $links === "update_administrador" ||
                $links === "registrar_productos" ||
                $links === "lista_productos" ||
                $links === "update_productos" ||
                $links === "compra" ||
                $links === "compra_productos" ||
                $links === "lista_compra_disponible" ||
                $links === "visualizar_compra" || 
                $links === "modificar_compra" ||
                $links === "modificar_compra_productos" ||
                $links === "inventario" ||
                $links === "ventas" ||
                $links === "verificar_usuario" ||
                $links === "preguntas_seguridad" ||
                $links === "modificar_contrasenia_usuario" ||
                $links === "resumen_ventas" ||
                $links === "visualizar_resumen_venta" ||
                $links === "categoria" ||
                $links === "lista_categorias" ||
                $links === "modificar_categoria" ||
                $links === "presentacion_productos" ||
                $links === "lista_presentacion"){

                $module = "views/modules/".$links.".php";

            }else if($links === "index" ){
                $module = "views/modules/login.php";
            }else{
                $module = "views/modules/login.php";
            }

            return $module;
            
        }
        
    }