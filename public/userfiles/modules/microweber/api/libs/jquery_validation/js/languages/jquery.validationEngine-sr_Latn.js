(function($){
    $.fn.validationEngineLanguage = function(){
    };
    $.validationEngineLanguage = {
        newLang: function(){
            $.validationEngineLanguage.allRules = {
                "required": { // Add your regex rules here, you can take telephone as an example
                    "regex": "none",
                    "alertText": "* Ovo polje je obavezno",
                    "alertTextCheckboxMultiple": "* Molimo izaberite opciju",
                    "alertTextCheckboxe": "* Ovo polje za potvrdu je obavezno",
                    "alertTextDateRange": "* Oba polja za raspon datuma su obavezna"
                },
                "requiredInFunction": { 
                    "func": function(field, rules, i, options){
                        return (field.val() == "test") ? true : false;
                    },
                    "alertText": "* Polje mora sadržati test"
                },
                "dateRange": {
                    "regex": "none",
                    "alertText": "* Greška ",
                    "alertText2": "Opseg datuma"
                },
                "dateTimeRange": {
                    "regex": "none",
                    "alertText": "* Greška ",
                    "alertText2": "Opseg datuma i vremena"
                },
                "minSize": {
                    "regex": "none",
                    "alertText": "* Najmanje ",
                    "alertText2": " znakova neophodno"
                },
                "maxSize": {
                    "regex": "none",
                    "alertText": "* Najviše ",
                    "alertText2": " znakova dozvoljeno"
                },
		"groupRequired": {
                    "regex": "none",
                    "alertText": "* Morate popuniti jedno od sledećih polja",
                    "alertTextCheckboxMultiple": "* Molimo izaberite opciju",
                    "alertTextCheckboxe": "* Ovo polje za potvrdu je obavezno"
                },
                "min": {
                    "regex": "none",
                    "alertText": "* Najmanja vrednost je "
                },
                "max": {
                    "regex": "none",
                    "alertText": "* Najveća vrednost je "
                },
                "past": {
                    "regex": "none",
                    "alertText": "* Datum pre "
                },
                "future": {
                    "regex": "none",
                    "alertText": "* Datum posle "
                },	
                "maxCheckbox": {
                    "regex": "none",
                    "alertText": "* Najviše ",
                    "alertText2": " opcija dozvoljeno"
                },
                "minCheckbox": {
                    "regex": "none",
                    "alertText": "* Molimo izaberite ",
                    "alertText2": " opcija"
                },
                "equals": {
                    "regex": "none",
                    "alertText": "* Polja se ne poklapaju"
                },
                "creditCard": {
                    "regex": "none",
                    "alertText": "* Neispravan broj kreditne kartice"
                },
                "phone": {
                    // credit: jquery.h5validate.js / orefalo
                    "regex": /^([\+][0-9]{1,3}([ \.\-])?)?([\(][0-9]{1,6}[\)])?([0-9 \.\-]{1,32})(([A-Za-z \:]{1,11})?[0-9]{1,4}?)$/,
                    "alertText": "* Neispravan broj telefona"
                },
                "email": {
                    // HTML5 compatible email regex ( http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#    e-mail-state-%28type=email%29 )
                    "regex": /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                    "alertText": "* Neispravna email adresa"
                },
                "fullname": {
                    "regex":/^([a-zA-Z]+[\'\,\.\-]?[a-zA-Z ]*)+[ ]([a-zA-Z]+[\'\,\.\-]?[a-zA-Z ]+)+$/,
                    "alertText":"* Mora biti ime i prezime"
                },
                "zip": {
                    "regex":/^\d{5,6}$/,
                    "alertText":"* Neispravan poštanski broj"
                },
                "integer": {
                    "regex": /^[\-\+]?\d+$/,
                    "alertText": "* Broj nije ispravan"
                },
                "number": {
                    // Number, including positive, negative, and floating decimal. credit: orefalo
                    "regex": /^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/,
                    "alertText": "* Decimalni broj nije ispravan"
                },
                "date": {                    
                    //	Check if date is valid by leap year
			"func": function (field) {
					var pattern = new RegExp(/^(\d{4})[\/\-\.](0?[1-9]|1[012])[\/\-\.](0?[1-9]|[12][0-9]|3[01])$/);
					var match = pattern.exec(field.val());
					if (match == null)
					   return false;
	
					var year = match[1];
					var month = match[2]*1;
					var day = match[3]*1;					
					var date = new Date(year, month - 1, day); // because months starts from 0.
	
					return (date.getFullYear() == year && date.getMonth() == (month - 1) && date.getDate() == day);
				},                		
			 "alertText": "* Neispravan datum, datum mora biti u YYYY-MM-DD formatu"
                },
                "ipv4": {
                    "regex": /^((([01]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))[.]){3}(([0-1]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))$/,
                    "alertText": "* Neispravna IP adresa"
                },
                "url": {
                    "regex": /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,
                    "alertText": "* Neispravan URL"
                },
                "onlyNumberSp": {
                    "regex": /^[0-9\ ]+$/,
                    "alertText": "* Dozvoljeni samo brojevi"
                },
                "onlyLetterSp": {
                    "regex": /^[a-zA-Z\ \']+$/,
                    "alertText": "* Dozvoljena samo slova"
                },
				"onlyLetterAccentSp":{
                    "regex": /^[a-z\u00C0-\u017F\ ]+$/i,
                    "alertText": "* Dozvoljena samo slova"
                },
                "onlyLetterNumber": {
                    "regex": /^[0-9a-zA-Z]+$/,
                    "alertText": "* Specijalni znakovi nisu dozvoljeni"
                },
                // --- CUSTOM RULES -- Those are specific to the demos, they can be removed or changed to your likings
                "ajaxUserCall": {
                    "url": "ajaxValidateFieldUser",
                    // you may want to pass extra data on the ajax call
                    "extraData": "name=eric",
                    "alertText": "* Ovo korisničko ime je već zauzeto",
                    "alertTextLoad": "* Provera podataka, molimo sačekajte"
                },
				"ajaxUserCallPhp": {
                    "url": "phpajax/ajaxValidateFieldUser.php",
                    // you may want to pass extra data on the ajax call
                    "extraData": "name=eric",
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertTextOk": "* Ovo korisničko ime je dostupno",
                    "alertText": "* Ovo korisničko ime je već zauzeto",
                    "alertTextLoad": "* Provera podataka, molimo sačekajte"
                },
                "ajaxNameCall": {
                    // remote json service location
                    "url": "ajaxValidateFieldName",
                    // error
                    "alertText": "* Ovo korisničko ime je već zauzeto",
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertTextOk": "* Ovo korisničko ime je dostupno",
                    // speaks by itself
                    "alertTextLoad": "* Provera podataka, molimo sačekajte"
                },
				 "ajaxNameCallPhp": {
	                    // remote json service location
	                    "url": "phpajax/ajaxValidateFieldName.php",
	                    // error
	                    "alertText": "* Ovo korisničko ime je dostupno",
	                    // speaks by itself
	                    "alertTextLoad": "* Provera podataka, molimo sačekajte"
	                },
                "validate2fields": {
                    "alertText": "* Molimo unesite HELLO"
                },
	            //tls warning:homegrown not fielded 
                "dateFormat":{
                    "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$|^(?:(?:(?:0?[13578]|1[02])(\/|-)31)|(?:(?:0?[1,3-9]|1[0-2])(\/|-)(?:29|30)))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:0?[1-9]|1[0-2])(\/|-)(?:0?[1-9]|1\d|2[0-8]))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(0?2(\/|-)29)(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|[2468][048]|[13579][26]))$/,
                    "alertText": "* Neispravan datum"
                },
                //tls warning:homegrown not fielded 
				"dateTimeFormat": {
	                "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])\s+(0?[1-5]|[0-6][0-9]){1}:(1[012]|0?[1-9]){1}:(0?[0-6]|[0-6][0-9]){1}\s+$|^(?:(?:(?:0?[13578]|1[02])(\/|-)31)|(?:(?:0?[1,3-9]|1[0-2])(\/|-)(?:29|30)))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(0?[1-9]|[12][0-9]|3[01]){1}\/((1[012]|0?[1-9]){1}\/\d{2,4}\s+(0?[1-5]|[0-6][0-9]){1}:(1[012]|0?[1-9]){1}:(0?[0-6]|[0-6][0-9]){1}\s+)$/,
                    "alertText": "* Neispravan datum ili format datuma",
                    "alertText2": "Očekivani format: ",
                    "alertText3": "dd/mm/yyyy hh:mm:ss ili ", 
                    "alertText4": "yyyy-mm-dd hh:mm:ss"
	            }
            };
            
        }
    };

    $.validationEngineLanguage.newLang();
    
})(jQuery);
