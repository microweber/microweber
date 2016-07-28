(function($){
    $.fn.validationEngineLanguage = function(){
    };
    $.validationEngineLanguage = {
        newLang: function(){
            $.validationEngineLanguage.allRules = {
                "required": { // Add your regex rules here, you can take telephone as an example
                    "regex": "none",
                    "alertText": "* Ово поље је обавезно",
                    "alertTextCheckboxMultiple": "* Молимо изаберите опцију",
                    "alertTextCheckboxe": "* Ово поље за потврду је обавезно",
                    "alertTextDateRange": "* Оба поља за распон датума су обавезна"
                },
                "requiredInFunction": { 
                    "func": function(field, rules, i, options){
                        return (field.val() == "test") ? true : false;
                    },
                    "alertText": "* Поље мора садржати test"
                },
                "dateRange": {
                    "regex": "none",
                    "alertText": "* Грешка ",
                    "alertText2": "Опсег датума"
                },
                "dateTimeRange": {
                    "regex": "none",
                    "alertText": "* Грешка ",
                    "alertText2": "Опсег датума и времена"
                },
                "minSize": {
                    "regex": "none",
                    "alertText": "* Најмање ",
                    "alertText2": " знакова неопходно"
                },
                "maxSize": {
                    "regex": "none",
                    "alertText": "* Највише ",
                    "alertText2": " знакова дозвољено"
                },
		"groupRequired": {
                    "regex": "none",
                    "alertText": "* Морате попунити једно од следеђих поља",
                    "alertTextCheckboxMultiple": "* Молимо изаберите опцију",
                    "alertTextCheckboxe": "* Ово поље за потврду је обавезно"
                },
                "min": {
                    "regex": "none",
                    "alertText": "* Најмања вредност је "
                },
                "max": {
                    "regex": "none",
                    "alertText": "* Највећа вредност је "
                },
                "past": {
                    "regex": "none",
                    "alertText": "* Датум пре "
                },
                "future": {
                    "regex": "none",
                    "alertText": "* Датум после "
                },	
                "maxCheckbox": {
                    "regex": "none",
                    "alertText": "* Највише ",
                    "alertText2": " опција дозвољено"
                },
                "minCheckbox": {
                    "regex": "none",
                    "alertText": "* Молимо изаберите ",
                    "alertText2": " опција"
                },
                "equals": {
                    "regex": "none",
                    "alertText": "* Поља се не поклапају"
                },
                "creditCard": {
                    "regex": "none",
                    "alertText": "* Неисправан број кредитне картице"
                },
                "phone": {
                    // credit: jquery.h5validate.js / orefalo
                    "regex": /^([\+][0-9]{1,3}([ \.\-])?)?([\(][0-9]{1,6}[\)])?([0-9 \.\-]{1,32})(([A-Za-z \:]{1,11})?[0-9]{1,4}?)$/,
                    "alertText": "* Неисправан број телефона"
                },
                "email": {
                    // HTML5 compatible email regex ( http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#    e-mail-state-%28type=email%29 )
                    "regex": /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                    "alertText": "* Неисправна емаил адреса"
                },
                "fullname": {
                    "regex":/^([a-zA-Z]+[\'\,\.\-]?[a-zA-Z ]*)+[ ]([a-zA-Z]+[\'\,\.\-]?[a-zA-Z ]+)+$/,
                    "alertText":"* Мора бити име и презиме"
                },
                "zip": {
                    "regex":/^\d{5,6}$/,
                    "alertText":"* Неисправан поштански број"
                },
                "integer": {
                    "regex": /^[\-\+]?\d+$/,
                    "alertText": "* Број није исправан"
                },
                "number": {
                    // Number, including positive, negative, and floating decimal. credit: orefalo
                    "regex": /^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/,
                    "alertText": "* Децимални број није исправан"
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
			 "alertText": "* Неисправан датум, датум мора бити у YYYY-MM-DD формату"
                },
                "ipv4": {
                    "regex": /^((([01]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))[.]){3}(([0-1]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))$/,
                    "alertText": "* Неисправна IP адреса"
                },
                "url": {
                    "regex": /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,
                    "alertText": "* Неисправан URL"
                },
                "onlyNumberSp": {
                    "regex": /^[0-9\ ]+$/,
                    "alertText": "* Дозвољени само бројеви"
                },
                "onlyLetterSp": {
                    "regex": /^[a-zA-Z\ \']+$/,
                    "alertText": "* Дозвољена само слова"
                },
				"onlyLetterAccentSp":{
                    "regex": /^[a-z\u00C0-\u017F\ ]+$/i,
                    "alertText": "* Дозвољена само слова"
                },
                "onlyLetterNumber": {
                    "regex": /^[0-9a-zA-Z]+$/,
                    "alertText": "* Специјални знакови нису дозвољени"
                },
                // --- CUSTOM RULES -- Those are specific to the demos, they can be removed or changed to your likings
                "ajaxUserCall": {
                    "url": "ajaxValidateFieldUser",
                    // you may want to pass extra data on the ajax call
                    "extraData": "name=eric",
                    "alertText": "* Ово корисничко име је већ заузето",
                    "alertTextLoad": "* Провера података, молимо сачекајте"
                },
				"ajaxUserCallPhp": {
                    "url": "phpajax/ajaxValidateFieldUser.php",
                    // you may want to pass extra data on the ajax call
                    "extraData": "name=eric",
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertTextOk": "* Ово корисничко име је слободно",
                    "alertText": "* Ово корисничко име је већ заузето",
                    "alertTextLoad": "* Провера података, молимо сачекајте"
                },
                "ajaxNameCall": {
                    // remote json service location
                    "url": "ajaxValidateFieldName",
                    // error
                    "alertText": "* Ово корисничко име је већ заузето",
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertTextOk": "* Ово корисничко име је слободно",
                    // speaks by itself
                    "alertTextLoad": "* Провера података, молимо сачекајте"
                },
				 "ajaxNameCallPhp": {
	                    // remote json service location
	                    "url": "phpajax/ajaxValidateFieldName.php",
	                    // error
	                    "alertText": "* Ово корисничко име је већ заузето",
	                    // speaks by itself
	                    "alertTextLoad": "* Провера података, молимо сачекајте"
	                },
                "validate2fields": {
                    "alertText": "* Молимо унесите HELLO"
                },
	            //tls warning:homegrown not fielded 
                "dateFormat":{
                    "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$|^(?:(?:(?:0?[13578]|1[02])(\/|-)31)|(?:(?:0?[1,3-9]|1[0-2])(\/|-)(?:29|30)))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:0?[1-9]|1[0-2])(\/|-)(?:0?[1-9]|1\d|2[0-8]))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(0?2(\/|-)29)(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|[2468][048]|[13579][26]))$/,
                    "alertText": "* Неисправан датум"
                },
                //tls warning:homegrown not fielded 
				"dateTimeFormat": {
	                "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])\s+(0?[1-5]|[0-6][0-9]){1}:(1[012]|0?[1-9]){1}:(0?[0-6]|[0-6][0-9]){1}\s+$|^(?:(?:(?:0?[13578]|1[02])(\/|-)31)|(?:(?:0?[1,3-9]|1[0-2])(\/|-)(?:29|30)))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(0?[1-9]|[12][0-9]|3[01]){1}\/((1[012]|0?[1-9]){1}\/\d{2,4}\s+(0?[1-5]|[0-6][0-9]){1}:(1[012]|0?[1-9]){1}:(0?[0-6]|[0-6][0-9]){1}\s+)$/,
                    "alertText": "* Неисправан датум или формат датума",
                    "alertText2": "Очекивани формат: ",
                    "alertText3": "mm/dd/yyyy hh:mm:ss или ", 
                    "alertText4": "yyyy-mm-dd hh:mm:ss"
	            }
            };
            
        }
    };

    $.validationEngineLanguage.newLang();
    
})(jQuery);
