jQuery.fn.uniform.language = jQuery.extend(jQuery.fn.uniform.language, {
    required  : '%s es obligatorio.',
    minlength : '%s debe ser al menos %d caracteres de largo',
    min       : '%s debe ser mayor o igual a %d',
    maxlength : '%s no debe ser superior a %d caracteres',
    max       : '%s debe ser menor o igual a %d',
    same_as   : '%s se espera que sea igual que %s',
    email     : '%s no es una dirección válida de correo electrónico',
    url       : '%s no es una URL válida',
    number    : '%s debe ser un número',
    integer   : '%s debe ser un número entero',
    alpha     : '%s debe contener sólo letras (sin caracteres especiales o números)',
    alphanum  : '%s debe contener sólo números y letras (sin caracteres especiales)',
    phrase    : '%s sólo debe contener caracteres alfabéticos, números, espacios, y después de la: . , - _ () * # :',
    phone     : '%s debe ser un número de teléfono',
    date      : '%s debe ser una fecha (mm/dd/yyyy)',
    callback  : 'No se ha podido validar el campo %s. La función de validación (%s) no está definida!',
    on_leave  : '¿Está seguro que desea salir de esta página sin guardar este formulario?'
});