(function ($) {
    $.fn.mlTextArea = function (options) {
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

            if (!name.length || !locales.length || !currentLocale.length) {
                console.log('Please fill the name and locales.');
                return;
            }

            $(obj).css('opacity', 0.2).css('display', 'none');
            $(obj).attr('value', translations[currentLocale]);
            $(obj).attr('lang', currentLocale);

            var outputHtml = '<div class="bs-component">';
                outputHtml += '<nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-1">';

                // tab buttons
                for (var i = 0; i < locales.length; i++) {
                    var mwBtnTabLocaleId = 'ml-tab-btn-'+name+'-'+i;
                    var mwBtnTabContentLocaleId = 'ml-tab-content-'+name+'-'+i;
                    var mwBtnTabLocaleClass = '';
                    if (currentLocale == locales[i]) {
                        mwBtnTabLocaleClass = 'active';
                    }
                    outputHtml += '<a class="btn btn-outline-secondary btn-sm justify-content-center '+mwBtnTabLocaleClass+'" id="'+mwBtnTabLocaleId+'" lang="'+locales[i]+'" data-toggle="tab" href="#'+mwBtnTabContentLocaleId+'">' + locales[i] + '</a>';

                    $('body').on('click','#' + mwBtnTabLocaleId, function (){
                        mw.trigger("mlChangedLanguage", $(this).attr('lang'));
                    });
                }
                outputHtml += '</nav>';

                // tab contents
                outputHtml += '<div id="" class="tab-content py-3">';
                for (var i = 0; i < locales.length; i++) {
                    var mwTabPaneLocaleId = 'ml-tab-content-'+name+'-'+i;
                    var mwTabPaneLocaleClass = '';
                    if (currentLocale == locales[i]) {
                        mwTabPaneLocaleClass = 'show active';
                    }
                    outputHtml += '<div class="tab-pane fade '+mwTabPaneLocaleClass+'" id="'+mwTabPaneLocaleId+'" lang="'+locales[i]+'">';
                    outputHtml += '<textarea class="form-control" name="multilanguage['+name+']['+locales[i]+']" lang="'+locales[i]+'">'+translations[locales[i]]+'</textarea>';
                    outputHtml += '</div>';

                    // If ml textarea is changed change and the value
                    $('body').on('keyup','#' + mwTabPaneLocaleId+' textarea', function () {
                        if (currentLocale == $(this).attr('lang')) {
                            // Change original field to this current lang value
                            $(obj).html($(this).val());
                        }
                        $(this).html($(this).val());
                    });
                }
                outputHtml += '</div>';
            outputHtml += '</div>';

            $(obj).after(outputHtml);

        });
        return this;
    };
}(jQuery));
