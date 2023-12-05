export const avatarSeleccionado = (avatar) => {
    return `views/assets/img/${avatar}`;
} 

export const alerta = ({titulo,mensaje,tipo_mensaje,callback,bool}) =>{
    swal({
        title : titulo,
        html : mensaje,
        type : tipo_mensaje,
    },
    (isConfirm) => {
        if (isConfirm) {
            if(bool){
                callback();
            }
        }
    })
}

export const loadDatos_user = () => {
    return localStorage.getItem('datos_user');
}

export const calcularTiempoDisponible = (fechaRegistro) => {
    const fechaActual = new Date(); // Obtiene la fecha y hora actual
    const registro = new Date(fechaRegistro); // Convierte la fecha de registro a un objeto Date
  
    const diferenciaEnMilisegundos = fechaActual - registro;
    const minutosRestantes = (24 * 60) - Math.floor(diferenciaEnMilisegundos / (1000 * 60));
    const horasRestantes = Math.floor(minutosRestantes / 60);
    const minutos = minutosRestantes % 60;
    
    return {horasRestantes, minutos};
  
}

export const formatted_time = (horasRestantes,minutos) => {
    const tiempoDisponible = `${horasRestantes} horas : ${minutos < 10 ? '0' : ''}${minutos} minutos`;
    return tiempoDisponible;
}

export function parseToDoubleWithTwoDecimals(number) {
    const parsedNumber = parseFloat(number);
    
    // Verificar si el número es un valor numérico válido
    if (isNaN(parsedNumber)) {
      return null;
    }
    
    // Verificar si el número es un entero
    if (Number.isInteger(parsedNumber)) {
      return parsedNumber.toFixed(2);
    }
    
    // Redondear el número a dos decimales
    return parsedNumber.toFixed(2);
}

//Formato DATE
export const extraer_fecha_actual = () => {
    // Paso 1: Crear una nueva instancia de Date
    const currentDate = new Date();

    // Paso 2: Formatear la fecha
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const day = String(currentDate.getDate()).padStart(2, '0');
    const formattedDate = `${year}-${month}-${day}`;

    return formattedDate;
}


//Extraer fecha actual en formato datetime
export const obtenerFechaActual_format_date_time = () => {
    const fecha = new Date();
    const year = fecha.getFullYear();
    const month = (fecha.getMonth() + 1).toString().padStart(2, '0');
    const day = fecha.getDate().toString().padStart(2, '0');
    const hours = fecha.getHours().toString().padStart(2, '0');
    const minutes = fecha.getMinutes().toString().padStart(2, '0');
    const seconds = fecha.getSeconds().toString().padStart(2, '0');
    const datetime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
  
    return datetime;
}