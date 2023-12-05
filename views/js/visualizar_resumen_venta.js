import { url } from './urls.js';
import { parseToDoubleWithTwoDecimals } from './utils.js';

const { get_sessiones, url_resumen_ventas } = url;

const d =  document,
$lista_visualizar_resumen_ventas = d.querySelector('#lista_visualizar_resumen_ventas'),
$fragment = d.createDocumentFragment();

const extraer_datos_venta = async (id_venta, url) => {
    try {
        const res = await fetch(`${url}?extraerDatosVenta=${id_venta}`);
        const data = await res.json();
        return data.success;
    } catch (error) {
        console.log(error);
    }
}

const extraer_id_venta = async(url) => {

    try {
        const res = await fetch(`${url}?extraer_id_resumen_venta=1`);
        const data = await res.json();
        return data;
    } catch (error) {
        console.log(error);
    }

}

const show_all_data_venta = (data) => {

    console.log(data)
    
    d.querySelector('#numero_factura').value = `${data.idVenta}-${data.fechaVenta.substring(0,10)}`;
    d.querySelector('#numero_factura').parentElement.classList.add('is-dirty');
    
    
    d.querySelector('#venta').value = data.fechaVenta;
    d.querySelector('#venta').parentElement.classList.add('is-dirty');
    
    d.querySelector('#monto_venta').value = `$${data.montoVenta}`;
    d.querySelector('#monto_venta').parentElement.classList.add('is-dirty');
    
    
    //enlazamos el template creado en el HTML
    const $template_items_productos_resumen_venta = d.querySelector('#template_items_productos_resumen_venta').content;

    if(data.productos.length > 0){

        data.productos.forEach(element => {
            //Insertamos los datos en el template
            $template_items_productos_resumen_venta.querySelector('.nombre').textContent = `${element.nombre_producto} ${element.contenido_neto}${element.nombre_presentacion} (${element.grabado_excento === 1 ? 'G' : 'E'})`;
            $template_items_productos_resumen_venta.querySelector('.cantidad').textContent = element.cantidad;
            $template_items_productos_resumen_venta.querySelector('.precio').textContent = `$${element.precio}`;

            let iva = element['grabado_excento'] == 1 ? parseToDoubleWithTwoDecimals((Number(element['precio']) * Number(element['cantidad'])) * 0.16) : 0;

            $template_items_productos_resumen_venta.querySelector('.iva').textContent = `$${iva}`;

            let subtotal = parseToDoubleWithTwoDecimals(Number(element['precio'] * Number(element['cantidad'])));

            $template_items_productos_resumen_venta.querySelector('.sub_total').textContent = `$${subtotal}`;

            let total = parseToDoubleWithTwoDecimals( Number(iva) + Number(subtotal));

            $template_items_productos_resumen_venta.querySelector('.total').textContent = `$${total}`;

            let $node = d.importNode($template_items_productos_resumen_venta,true);
            //Guardamos el nodo en el fragment
            $fragment.append($node);
        }); 
        //Limpiamos la lista
        $lista_visualizar_resumen_ventas.innerHTML= "";
         //Insertamos el fragment en la lista
        $lista_visualizar_resumen_ventas.append($fragment);
    }else{
         //Limpiamos la lista
         $lista_visualizar_resumen_ventas.innerHTML= "";
    }
}

d.addEventListener('DOMContentLoaded', async e => {

    let id_venta = await extraer_id_venta(get_sessiones);
    let data_venta = await extraer_datos_venta(id_venta, url_resumen_ventas);
    show_all_data_venta(data_venta[0]);
})