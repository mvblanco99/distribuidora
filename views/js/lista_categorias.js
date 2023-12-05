import { url } from "./urls.js";

const { url_categorias, url_sesiones } = url;

const d = document,
$body_categories = d.querySelector('#body_categories'),
$searchCategory = d.querySelector('#searchCategory'),
$fragment = d.createDocumentFragment();

const extraer_data_categorias = async (url) => {
    try {
        const res = await fetch(`${url}?extraer_categorias=1`);
        const data = await res.json();
        return data.success;
    } catch (error) {
        console.log(error);
    }
}

const show_data_categorias = (data) => {

    if(data.length > 0){

        //Enlazamos el template HTML
        const $item_categoria = d.querySelector('#item_categoria').content;

        data.forEach(element => {
            //Insertamos los datos en el template
            $item_categoria.querySelector('.name_categoria').textContent = element.nombre_categoria;
            $item_categoria.querySelector('.subtittle').textContent = `${element.descripcion_categoria.substring(0,30)}...`;
            $item_categoria.querySelector('.zmdi-more').dataset.id = element.id_categoria;
            // $item_categoria.querySelector('.mdl-list__item-secondary-action').dataset.id = element.id_categoria;
            let $clone = $item_categoria.cloneNode(true);
            //Guardamos el nodo en el fragment
            $fragment.append($clone);
        });

        //Limpiamos la lista de categorias
        $body_categories.innerHTML = "";
        //Insertamos el contenido al body
        $body_categories.append($fragment);

    }else{
        //Limpiamos la lista de categorias
        $body_categories.innerHTML = "";
    }

}

const generar_session = async (url,id_categoria) => {
    try {
        const res = await fetch(`${url}?id_categoria=${id_categoria}`);
        const data = await res.json();
        return data.data;
    } catch (error) {
        console.log(error);
    }
}

const buscar_registros = async (url,param) => {
    try {
        const res = await fetch(`${url}?buscar_registros=${param}`);
        const data = await res.json();
        return data.success;
    } catch (error) {
        console.log(error);
    }
}

$searchCategory.addEventListener('input', async e => {

    // Obtén el valor actual del campo de texto
    let searchText = $searchCategory.value;
  
    // Llama a tu función de búsqueda en la base de datos
    let resConsulta = await buscar_registros(url_categorias,searchText);
    console.log(resConsulta);

    show_data_categorias(resConsulta)

});

d.addEventListener('click', async e => {

    if(e.target.classList.contains('zmdi-more')){

        e.preventDefault();

        console.log(e.target.dataset.id);

        //Generamos la variable de sesion para guardar el id de la categoria
        let is_session_creada = await generar_session(url_sesiones,e.target.dataset.id);

        if(is_session_creada != ""){
            //Redireccionamos
            window.location = 'modificar_categoria';
        }
    }

});

d.addEventListener('DOMContentLoaded', async e => {
    let data_categorias = await extraer_data_categorias(url_categorias);
    console.log(data_categorias);
    show_data_categorias(data_categorias);
});