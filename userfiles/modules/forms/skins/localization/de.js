// German localization by Benjamin Steicke
// http://benjamin-steinke.de/

jQuery.fn.uniform.language = jQuery.extend(jQuery.fn.uniform.language, {
    required      : '%s ist ein Pflichtfeld',
    req_radio     : 'Bitte tätigen Sie eine Auswahl',
    req_checkbox  : 'Sie müssen dieses Ankreuzfeld auswählen um fortzufahren',
    minlength     : '%s muss mindestens %d Zeichen lang sein',
    min           : '%s muss größer oder gleich %d sein',
    maxlength     : '%s darf nicht länger als %d Zeichen sein',
    max           : '%s muss kleiner oder gleich %d sein',
    same_as       : '%s muss gleich %s sein',
    email         : '%s ist keine gültige E-Mail-Adresse',
    url           : '%s ist keine gültige URL',
    number        : '%s muss eine gültige Zahl sein',
    integer       : '%s muss eine Ganzzahl sein',
    alpha         : '%s darf nur Buchstaben enthalten (keine Sonderzeichen oder Zahlen)',
    alphanum      : '%s darf nur Zahlen und Buchstaben enthalten (keine Sonderzeichen)',
    phrase        : '%s darf nur Buchstaben, Zahlen, Leerzeichen und diese Zeichen enthalten: . , - _ () * # :',
    phone         : '%s muss eine Telefonnummer sein',
    date          : '%s muss ein Datum sein (mm/tt/jjjj)',
    callback      : 'Fehler beim Validieren von %s. Validator Funktion (%s) ist nicht definiert!',
    on_leave      : 'Sind Sie sicher, dass Sie diese Seite ohne Abschicken der Daten verlassen wollen?',
    submit_msg    : 'Entschuldigung, Ihre Eingaben müssen korrigiert werden.',
    submit_help   : 'Bitte sehen Sie nach den untenstehend markierten Elementen.',
    submit_success: 'Danke, dieses Formular wurde empfangen.'
});