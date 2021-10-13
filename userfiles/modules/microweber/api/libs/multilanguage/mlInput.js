(function ($) {
    $.fn.mlInput = function (options) {
        var settings = $.extend({
            name: false,
            locales: [],
            currentLocale: false,
            translations: [],
        }, options);

        this.each(function (index, obj) {
            var name = settings.name;
            var currentLocale = settings.currentLocale;
            var locales = settings.locales;
            var translations = settings.translations;
            var type = $(obj).attr('type');

            if (!name.length || !locales.length || !currentLocale.length){
                console.log('Please fill the name and locales.');
                return;
            } else if (type != 'text') {
                console.log('Multilanguage input type not supported with this setup.');
                return;
            }

            $(obj).css('opacity', 0.2).css('display','none');

            var outputHtml = '<div class="input-group">';

                let mlInputLocaleChangeId = 'ml-input-'+name+'-change';

                let mlInputLocaleIds = [];
                for (let i = 0; i < locales.length; i++) {
                    let mlInputLocaleId = 'ml-input-'+name+'-'+i;
                    outputHtml +='<input type="text" class="form-control" value="'+translations[locales[i]]+'" id="'+mlInputLocaleId+'" name="multilanguage['+name+']['+locales[i]+']" lang="'+locales[i]+'" value="" />';

                    // If ml input is changed change and the value attr
                    $('body').on('keyup','#' + mlInputLocaleId, function (){
                        $(this).attr('value', $(this).val());
                    });

                    mlInputLocaleIds[i] = mlInputLocaleId;
                }

                outputHtml += '<div class="input-group-append">';
                    outputHtml += '<span>';
                        outputHtml += '<select class="selectpicker" id="'+mlInputLocaleChangeId+'" data-width="100%">';
                        for (let i = 0; i < locales.length; i++) {
                            outputHtml += '<option value="'+locales[i]+'">' + locales[i].toUpperCase() + '</option>';
                        }
                        outputHtml += '</select>';
                    outputHtml += '</span>';
                outputHtml += '</div>';

            outputHtml += '</div>';
            $(obj).after(outputHtml);

            $('body').on('change','#' + mlInputLocaleChangeId, function (){
                mw.trigger("mlChangedLanguage", $(this).val());
            });

            mw.on("mlChangedLanguage", function (e, mlCurrentLanguage) {
                $('#' + mlInputLocaleChangeId).selectpicker("val", mlCurrentLanguage);
                for (let i = 0; i < mlInputLocaleIds.length; i++) {
                    // If ml locale is changed hide all fields except current lang
                    if ($('#' + mlInputLocaleIds[i]).attr('lang') !== mlCurrentLanguage) {
                        $('#' + mlInputLocaleIds[i]).hide();
                    } else {
                        $('#' + mlInputLocaleIds[i]).show();
                    }
                }
            });

        });
        return this;
    };
}(jQuery));
