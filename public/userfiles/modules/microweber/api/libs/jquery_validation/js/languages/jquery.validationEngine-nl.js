(function($){
	$.fn.validationEngineLanguage = function(){
	};
	$.validationEngineLanguage = {
	    newLang: function(){
	        $.validationEngineLanguage.allRules = {
	            "required": { // Add your regex rules here, you can take telephone as an example
	                "regex": "geen",
	                "alertText": "* Dit veld is verplicht",
	                "alertTextCheckboxMultiple": "* Selecteer a.u.b. een optie",
	                "alertTextCheckboxe": "* Dit selectievakje is verplicht"
	            },
	            "requiredInFunction": {
                    "func": function(field, rules, i, options){
                        return (field.val() == "test") ? true : false;
                    },
                    "alertText": "* Velden moeten gelijk zijn"
                	    },
	            "minSize": {
	                "regex": "none",
	                "alertText": "* Minimaal ",
	                "alertText2": " karakters toegestaan"
	            },
	            "maxSize": {
	                "regex": "none",
	                "alertText": "* Maximaal ",
	                "alertText2": " karakters toegestaan"
	            },
				"groupRequired": {
                    "regex": "none",
                    "alertText": "* Vul één van de volgende velden in"
                },
	            "min": {
	                "regex": "none",
	                "alertText": "* Minimale waarde is "
	            },
	            "max": {
	                "regex": "none",
	                "alertText": "* Maximale waarde is "
	            },
	            "past": {
	                "regex": "none",
	                "alertText": "* Datum voorafgaand aan "
	            },
	            "future": {
	                "regex": "none",
	                "alertText": "* Datum na "
	            },
	            "maxCheckbox": {
	                "regex": "none",
	                "alertText": "* Toegestane aantal vinkjes overschreden"
	            },
	            "minCheckbox": {
	                "regex": "none",
	                "alertText": "* Selecteer a.u.b. ",
	                "alertText2": " opties"
	            },
	            "equals": {
	                "regex": "none",
	                "alertText": "* Velden komen niet overeen"
	            },
                "creditCard": {
                    "regex": "none",
                    "alertText": "* Ongeldige credit card nummer"
                },
	            "phone": {
	                // credit: jquery.h5validate.js / orefalo
	                "regex": /^([\+][0-9]{1,3}([ \.\-])?)?([\(][0-9]{1,6}[\)])?([0-9 \.\-]{1,32})(([A-Za-z \:]{1,11})?[0-9]{1,4}?)$/,
	                "alertText": "* Ongeldig telefoonnummer"
	            },
	            "email": {
	                // Shamelessly lifted from Scott Gonzalez via the Bassistance Validation plugin http://projects.scottsplayground.com/email_address_validation/
                    // Replaced incredible long regex with shorter and working one! :)
//	                "regex": /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,
	                "regex": /^[a-z0-9]+[a-z0-9._%+-]*@(?:[a-z0-9-]+\.)+[a-z]{2,6}$/i,
	                "alertText": "* Ongeldig emailadres"
	            },
	            "integer": {
	                "regex": /^[\-\+]?\d+$/,
	                "alertText": "* Ongeldig geheel getal"
	            },
	            "number": {
	                // Number, including positive, negative, and floating decimal. credit: orefalo
	                "regex": /^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/,
	                "alertText": "* Ongeldig komma getal"
	            },
	            "date": {
	                "regex": /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/,
	                "alertText": "* Ongeldige datum, formaat moet DD-MM-JJJJ zijn"
	            },
	            "ipv4": {
	            	"regex": /^((([01]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))[.]){3}(([0-1]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))$/,
	                "alertText": "* Ongeldig IP-adres"
	            },
	            "url": {
                    "regex": /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,
	                "alertText": "* Ongeldige URL"
	            },
	            "onlyNumberSp": {
	                "regex": /^[0-9\ ]+$/,
	                "alertText": "* Alleen cijfers"
	            },
	            "onlyLetterSp": {
	                "regex": /^[a-zA-Z\ \']+$/,
	                "alertText": "* Alleen leestekens"
	            },
				"onlyLetterAccentSp":{
                    "regex": /^[a-z\u00C0-\u017F\ ]+$/i,
	                "alertText": "* Alleen leestekens"
                },
	            "onlyLetterNumber": {
	                "regex": /^[0-9a-zA-Z]+$/,
	                "alertText": "* Geen vreemde tekens toegestaan"
	            },
	            // --- CUSTOM RULES -- Those are specific to the demos, they can be removed or changed to your likings
	            "ajaxUserCall": {
	                "url": "ajaxValidateFieldUser",
	                // you may want to pass extra data on the ajax call
	                "extraData": "name=eric",
	                "alertText": "* Deze gebruiker bestaat al",
	                "alertTextLoad": "* Bezig met valideren, even geduld aub"
	            },
	            "ajaxNameCall": {
	                // remote json service location
	                "url": "ajaxValidateFieldName",
	                // error
	                "alertText": "* Deze naam bestaat al",
	                // if you provide an "alertTextOk", it will show as a green prompt when the field validates
	                "alertTextOk": "* Deze naam is beschikbaar",
	                // speaks by itself
	                "alertTextLoad": "* Bezig met valideren, even geduld aub"
	            },
	            "validate2fields": {
	                "alertText": "* Voer aub HELLO in"
	            }
	        };

	    }
	};
	$.validationEngineLanguage.newLang();
})(jQuery);
