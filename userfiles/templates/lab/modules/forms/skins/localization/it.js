// Italian localization by Pao6
// http://twitter.com/paosix

jQuery.fn.uniform.language = jQuery.extend(jQuery.fn.uniform.language, {
    required      : '%s è obbligatorio',
    req_radio     : 'Seleziona una scelta',
    req_checkbox  : 'Devi selezionare questa casella prima di procedere',
    minlength     : '%s dovrebbe essere lungo almeno %d caratteri',
    min           : '%s dovrebbe essere maggiore o uguale a %d ',
    maxlength     : '%s non dovrebbe essere più lungo di %d caratteri',
    max           : '%s dovrebbe essere minore o uguale a %d',
    same_as       : '%s deve essere uguale a %s',
    email         : '%s non è un indirizzo email valido',
    url           : '%s non è un URL valido',
    number        : '%s deve essere un numero',
    integer       : '%s deve essere un numero intero',
    alpha         : '%s dovrebbe contenere solo lettere (senza caratteri speciali o numeri)',
    alphanum      : '%s dovrebbe contenere solo lettere e numeri (senza caratteri speciali)',
    phrase        : '%s dovrebbe contenere solo lettere, numeri e i seguenti caratteri: . , - _ () * # :',
    phone         : '%s dovrebbe contenere un numero telefonico',
    date          : '%s dovrebbe essre una data (mm/gg/aaaa)',
    callback      : 'Fallita la convalida del campo %s. La funzione di convalida (%s) non è definita!',
    on_leave      : 'Sei sicuro di voler lasciare la pagina senza salvare questo modulo?',
    submit_msg    : 'Il modulo necessita delle correzioni',
    submit_help   : 'Perfavore controlla i campi segnati qui sotto.',
    submit_success: 'Grazie, il modulo è stato mandato.'
});
