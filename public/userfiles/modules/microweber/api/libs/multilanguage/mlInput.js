(function ($) {

    $.fn.mlInput = function (options) {
        var settings = $.extend({
            name: false,
            locales: [],
            currentLocale: false,
            defaultLocale: false,
            attributes: [],
            translations: [],
        }, options);



        this.each(function (index, obj) {
            var name = settings.name;
            var currentLocale = settings.currentLocale;
            var defaultLocale = settings.defaultLocale;
            var locales = settings.locales;
            var translations = settings.translations;
            var attributes = settings.attributes;
            var type = $(obj).attr('type');

            if (!name.length || !locales.length || !currentLocale.length){
                console.log('Please fill the name and locales.');
                return;
            } else if (type != 'text') {
                console.log('Multilanguage input type not supported with this setup.');
                return;
            }

           // $(obj).css('opacity', 0.2).css('display','none');
            $(obj).css('display','none');
            $(obj).attr('value', translations[currentLocale]);

            var plainName = name;

            // for multidimensional names
            if (name.match(/\[[^\]]*]/g)) {
                $.each($(obj).serializeAssoc(), function(key, values) {
                    plainName = key+ '-'+Object.keys(values)[0];
                });
                plainName = plainName.replace('_','-');
            } else {
                plainName = plainName.replace('.','-');
            }

            var outputHtml = '<div class="input-group js-input-group-'+plainName+'">';

                var mlInputLocaleIds = [];
                for (var i = 0; i < locales.length; i++) {

                    var mlInputLocaleId = 'ml-input-'+plainName+'-'+i;
                    var mlInputName = 'multilanguage['+name+']['+locales[i]+']';

                    // for multidimensional names
                    if (name.match(/\[[^\]]*]/g)) {

                        mlInputLocaleId = 'ml-input';
                        mlInputName = 'multilanguage'

                        $.each($(obj).serializeAssoc(), function(key, values) {
                            mlInputName += '['+key+']['+Object.keys(values)[0]+']';
                            mlInputLocaleId += '-'+key+ '-'+Object.keys(values)[0]+'-';
                        });

                        mlInputName += '['+locales[i]+']';
                        mlInputLocaleId += i;
                    }

                    var input  = $('<input type="text" class="form-control" value="'+translations[locales[i]]+'" id="'+mlInputLocaleId+'" name="'+mlInputName+'" dir="'+mw.admin.rtlDetect.getLangDir(locales[i])+'" lang="'+locales[i]+'"  />');

                    $.each(attributes, function(name, value) {
                        if (name !== 'value') {
                            if (!$(input).attr(name)) {
                                $(input).attr(name, value);
                            }
                        }
                    });

                    var inputHtml =  input[0].outerHTML;
                     outputHtml +=inputHtml;

                    // If ml input is changed change and the value attr
                    $('body').on('keyup change','#' + mlInputLocaleId, function () {
                        // if (currentLocale == $(this).attr('lang')) {
                        if (defaultLocale == $(this).attr('lang')) {
                            // Change original field to this current lang value
                            $(obj).attr('value', $(this).val());

                        }
                        $(this).attr('value', $(this).val());

                    });

                    mlInputLocaleIds[i] = mlInputLocaleId;
                }

                var mlInputLocaleChangeId = 'ml-input-'+plainName+'-change';
                outputHtml += '<div class="input-group-append">';
                    outputHtml += '<span>';
                        outputHtml += '<select id="'+mlInputLocaleChangeId+'" data-width="100%">';
                        for (var i = 0; i < locales.length; i++) {
                            var localeIcon = locales[i];
                            var localeIconSplit = localeIcon.split('_');

                            if (typeof localeIconSplit[1] !== 'undefined') {
                                localeIcon = localeIconSplit[1];
                                localeIcon = localeIcon.toLowerCase();
                            }

                            outputHtml += '<option  data-subtext="'+locales[i].toUpperCase()+'" data-icon="flag-icon flag-icon-'+localeIcon+'" value="'+locales[i]+'"></option>';
                        }
                        outputHtml += '</select>';
                    outputHtml += '</span>';
                outputHtml += '</div>';

            outputHtml += '</div>';
            $(obj).after(outputHtml);

            // Switch fields
            function switchInputFieldsToLanguage(language) {

                $('#' + mlInputLocaleChangeId).selectpicker("val", language);

                for (var i = 0; i < mlInputLocaleIds.length; i++) {
                    // If ml locale is changed hide all fields except current lang
                    if ($('#' + mlInputLocaleIds[i]).attr('lang') !== language) {
                        $('#' + mlInputLocaleIds[i]).hide();
                    } else {
                        $('#' + mlInputLocaleIds[i]).show();
                    }
                }
            }

            // Show for current lang
            var mlLangIsSupported = false;
            for (var i = 0; i < locales.length; i++) {
                if (locales[i] == currentLocale) {
                    mlLangIsSupported = true;
                }
            }
            if (mlLangIsSupported) {
                switchInputFieldsToLanguage(currentLocale);
            } else {
                switchInputFieldsToLanguage(locales[0]);
            }

            // If dropdown is changed
            $('body').on('change','#' + mlInputLocaleChangeId, function (){
                mw.trigger("mlChangedLanguage", $(this).val());
            });

            // Listen for events
            mw.on("mlChangedLanguage", function (e, mlCurrentLanguage) {
                switchInputFieldsToLanguage(mlCurrentLanguage);
            });

        });
        return this;
    };
}(jQuery));
