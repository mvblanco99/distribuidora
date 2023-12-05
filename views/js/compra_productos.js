import {url} from "./urls.js";
import { alerta } from "./utils.js";
import { validarCamposVacios, validarNumeros } from "./validaciones.js";


    const {get_sessiones,url_productos,url_compra} = url;

    const d = document,
    $btn_add_list = d.querySelector('#btn-add-list'),
    $btn_registrar_compra = d.querySelector('#btn_registrar_compra'),
    $lista_compra_productos = d.querySelector('#lista_compra_productos'),
    $fragment_list = d.createDocumentFragment();
    const data_compra = []; //Guardaremos los datos de la compra extraidos de la variable de session
    let data_productos;// Guardaremos los datos de los productos extraidos de la base de datos
    const productos_seleccionados = [];

    //Mostramos el numero de factura de la compra
    const showDataCompra = (data) => {
        d.querySelector('#numero_factura').value = data;
        d.querySelector('#label_numero_factura').classList.add('is-dirty');    
    }

    //Recuperamos los datos de la compra por medio de la variable de session creada en php
    const extraer_id_compra = async(url) => {
       
        try {
            const res = await fetch(`${url}?extraer_id_compra_session=1`);
            if(!res.ok) throw {status : res.status, statusText : res.statusText};
            const data = await res.json();
            data_compra.push(data[0][0],data[1]);//Guardamos los datos de la compra
            showDataCompra(data_compra[1]);
        } catch (error) {
            console.log(error);
            let titulo = error.status || "Error";
            let mensaje = error.statusText || "Ocurrio un error, Contacte al Administrador";
            alerta({
                titulo,
                mensaje,
                tipo_mensaje:"error"
            });
        }
    }

    //Extraemos la data de todos los productos registrados
    const extraerDatosProductos = async (url) => {

        try {
            const res =  await fetch(`${url}?consultar_todos_productos=1`);
            if(!res.ok) throw {status : res.status, statusText : res.statusText};
            const data = await res.json();
            data_productos = data["success"]; //Guardamos los datos de los productos
        } catch (error) {
            console.log(error);
            let titulo = error.status || "Error";
            let mensaje = error.statusText || "Ocurrio un error, Contacte al Administrador";
            alerta({
                titulo,
                mensaje,
                tipo_mensaje:"error"
            });
        }

    }

    //Mostramos los productos seleccionados
    const show_lista_productos = (lista_productos_seleccionados) => {

        console.log(lista_productos_seleccionados)
        //enlazamos el template creado en el HTML
        const $items_lista_productos = d.querySelector('#items_lista_productos').content;

        if(lista_productos_seleccionados.length > 0){

            lista_productos_seleccionados.forEach(elemento => {
                //Insertamos los datos en el template
                $items_lista_productos.querySelector('.name_producto').textContent = elemento['nombre_producto'];
                $items_lista_productos.querySelector('.stock').textContent = elemento['cantidad'];
                $items_lista_productos.querySelector('.price').textContent = `$${elemento['precio']}`;
                $items_lista_productos.querySelector('.monto_producto').textContent = `$${Number(elemento['cantidad']) * Number(elemento['precio'])}`;

                $items_lista_productos.querySelector('.btn-danger').dataset.id = elemento['cod_producto'];
                //guardamos una copia de la estrutura actual del template en la variable $node
                let $clone = $items_lista_productos.cloneNode(true);
                //Guardamos el nodo en el fragment
                $fragment_list.append($clone);
            });
            
            //Limpiamos la lista
            $lista_compra_productos.innerHTML = "";
            //Insertamos el fragment en la listas
            $lista_compra_productos.append($fragment_list);
        }else{
            $lista_compra_productos.innerHTML = "";
        }
    }

    //Agregamos el producto al array de productos seleccionados
    const addProduct_in_array_product = (id_producto, cantidad, precio) => {
        
        //Obtenemos datos del producto solicitado
        let datos_producto = data_productos.filter( element => element['id_producto'] == id_producto);

        //extraemos el nombre y codigo del producto
        let nombre_producto = `${datos_producto[0]['nombre_producto']} ${data_productos[0]['contenido_neto']}L (${datos_producto[0]['grabado_excento'] == 1 ? 'G' : 'E'})`;
        let cod_producto = datos_producto[0]['codigo_producto'];
        let grabado_excento = datos_producto[0]['grabado_excento'];

        //Creamos un objeto con todos los datos ingresados
        let producto_seleccionado = {
            id_producto,
            cod_producto,
            nombre_producto,
            cantidad,
            precio,
            grabado_excento
        }

        //Agregamos los datos al array de productos seleccionados
        productos_seleccionados.push(producto_seleccionado);
        d.querySelector('#codigo_producto').value = "";
        d.querySelector('#codigo_producto').parentElement.classList.remove("is-dirty");
        d.querySelector('#cantidad').value = "";
        d.querySelector('#cantidad').parentElement.classList.remove("is-dirty");
        d.querySelector('#precio').value = "";
        d.querySelector('#precio').parentElement.classList.remove("is-dirty");
        d.querySelector('#codigo_producto').focus();

    }

    //Agregamos el producto a la lista de productos seleccionados
    const addList = async() => {

        //Extraemos datos del formulario
        let codigo_producto =d.querySelector('#codigo_producto');
        let cantidad = d.querySelector('#cantidad');
        let precio =  d.querySelector('#precio');

        let arrayInputs = [codigo_producto,precio,cantidad];

        //validamos datos del formulario
        if(!validarCamposVacios(arrayInputs) || !validarNumeros(codigo_producto) || !validarNumeros(cantidad) || !validarNumeros(precio)){
            return;
        }

        //Buscamos el producto solicitado por medio del codigo del producto
        let producto_solicitado = buscarProducto(codigo_producto.value);

        //Verificamos si el producto fue encontrado
        if(producto_solicitado === null){
            // errorBuscarProducto = true;
            alerta({
                titulo: "Código de Producto No Se Encuentra Registrado",
                tipo_mensaje: "error",
            });
            codigo_producto.parentElement.classList.add("is-invalid");
            codigo_producto.focus();
            return;
        }

        //Verificamos si el array de productos seleccionados esta vacio
        if(productos_seleccionados.length === 0){
            //Si esta vacio, entonces agregamos el producto
            addProduct_in_array_product(producto_solicitado.id_producto,cantidad.value,precio.value);
        }else{

            let encontrado = false;

            //Si el array no esta Vacio, verificamos si el producto solicitado ya se encuentra dentro el array
            for (let i = 0; i < productos_seleccionados.length; i++) {
                if(productos_seleccionados[i]['id_producto'] == producto_solicitado.id_producto){
                    encontrado = true;
                    break;
                }
            }

            if(encontrado){
                alerta({
                    titulo: 'Producto ya se encuentra seleccionado',
                    tipo_mensaje: "warning"
                });
                return;
            }else{
                addProduct_in_array_product(producto_solicitado.id_producto,cantidad.value,precio.value);
            }
        }

        show_lista_productos(productos_seleccionados);
    }

    //Eliminamos el producto de la lista de productos seleccionados
    const quitar_producto_lista = (id) => {
        
        let posicion_producto = null;

        //Recuperamos la posicion del producto a borrar
        for (let i = 0; i < productos_seleccionados.length; i++) {
            if(productos_seleccionados[i]['cod_producto'] == id){
                posicion_producto = i;
                break;
            }
        }

        //Eliminamos el producto de la lista
        productos_seleccionados.splice(posicion_producto,1);

        //Mostramos la lista actualizada
        show_lista_productos(productos_seleccionados);
    }

    //Guardamos datos de la compra (Tabla compra_productos)
    const save_compra_productos = async(datos,url) => {

        const body = {
            method : 'POST',
            body : JSON.stringify(datos) 
        }

        try {
            const res = await fetch(`${url}?recibir_datos_compra_producto=1`,body);
            if(!res.ok) throw {status : res.status, statusText : res.statusText};
            const data = await res.json();
            console.log(data)
            if(data["success"] == 1){
                alerta({
                    titulo: "Registro de Compra Completado",
                    tipo_mensaje: "success",
                    callback : ()=>{
                      window.location = "lista_compra_disponible";  
                    },
                    bool: true
                });
            }else{
                //En caso de fallar la validacion en el backend se envia un alerta
                alerta({
                    titulo: "Error",
                    mensaje : data["success"][0],
                    tipo_mensaje: "error",
                    bool: true,
                    callback: () => {
                        d.querySelector(`#${data["success"][3]}`) !== "" ?d.querySelector(`#${data["success"][3]}`).focus():"";
                    }
                });
                if(data['success'][1] !== ""){
                    let tr = d.querySelectorAll('.id');
                    tr[data['success'][1]].focus();

                    productos_seleccionados.splice(data['success'][1],1);
                    show_lista_productos(productos_seleccionados);
                    console.log(data['success'][2]);
                    d.querySelector('#codigo_producto').value = data['success'][2].cod_producto;
                    d.querySelector('#codigo_producto').parentElement.classList.add("is-dirty");

                    d.querySelector('#cantidad').value = data['success'][2].cantidad;
                    d.querySelector('#cantidad').parentElement.classList.add("is-dirty");

                    d.querySelector('#precio').value = data['success'][2].precio;
                    d.querySelector('#precio').parentElement.classList.add("is-dirty");
                    d.querySelector(`#${data["success"][3]}`).focus();
                }
            }
        } catch (error) {   
            console.log(error);
            let titulo = error.status || "Error";
            let mensaje = error.statusText || "Ocurrio un error, Contacte al Administrador";
            alerta({
                titulo,
                mensaje,
                tipo_mensaje:"error"
            });
        }
    }

    //Buscamos producto solicitado
    const buscarProducto = (codigo_producto) => {

        if(codigo_producto === ""){
            return;
        }

        let producto_solicitado = null;
        //Recorremos el array de productos y devolvemos del producto solicitado
        for (let i = 0; i < data_productos.length; i++) {
            if(codigo_producto === data_productos[i].codigo_producto){
                producto_solicitado = data_productos[i];
                break;
            }
        }

        return producto_solicitado;
    }

    d.addEventListener("keydown", e =>{
        if(e.key ==="Enter"){
            e.preventDefault();
            e.stopPropagation();
        }
    });

    $btn_registrar_compra.addEventListener('click', async e=> {
         //Verficamos si han agregado productos a la lista

         if(productos_seleccionados.length === 0){
           
            e.preventDefault();
            e.stopPropagation();
           
            alerta({
                titulo : "Debe ingresar productos a la lista",
                tipo_mensaje : "warning"
            });
            return;
        }
        
        await save_compra_productos({
            id_compra: data_compra[0],
            productos_seleccionados
        },url_compra);
    })

    d.addEventListener('click', e => {
    
        //Agregamos productos a la lista
        if(e.target === $btn_add_list){
            addList();
        }

        //Eliminamos producos de la lista
        if(e.target.classList.contains('btn-danger')){
            quitar_producto_lista(e.target.dataset.id);
        }
    });

    d.addEventListener('DOMContentLoaded', async e => {
        //Extramos el id de la compra
        await extraer_id_compra(get_sessiones);
        //Extraemos los datos de los productos
        await extraerDatosProductos(url_productos);
    });
