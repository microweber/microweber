(function ($) {
    $.fn.mlTextArea = function (options) {
        var settings = $.extend({
            name: false,
            locales: [],
            currentLocale: false,
            translations: [],
            mwEditor: false,
        }, options);

        this.each(function (index, obj) {
            var name = settings.name;
            var currentLocale = settings.currentLocale;
            var locales = settings.locales;
            var translations = settings.translations;
            var mwEditor = settings.mwEditor;

            if (!name.length || !locales.length || !currentLocale.length) {
                console.log('Please fill the name and locales.');
                return;
            }

            $(obj).css('opacity', 0.2).css('display', 'none');
            $(obj).attr('value', translations[currentLocale]);
            $(obj).attr('lang', currentLocale);

            var outputHtml = '<div class="bs-component">';

                var mwNavLocaleId = 'ml-nav-'+name;
                outputHtml += '<nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-1" id="'+mwNavLocaleId+'">';

                // tab buttons
                for (var i = 0; i < locales.length; i++) {
                    var mwBtnTabLocaleId = 'ml-tab-btn-'+name+'-'+i;
                    var mwBtnTabContentLocaleId = 'ml-tab-content-'+name+'-'+i;

                    var localeIcon = locales[i];
                    var localeIconSplit = localeIcon.split('_');

                    if (typeof localeIconSplit[1] !== 'undefined') {
                        localeIcon = localeIconSplit[1];
                        localeIcon = localeIcon.toLowerCase();
                    }

                    var localeUppercase = locales[i].toUpperCase();

                    outputHtml += '<a class="btn btn-outline-secondary btn-sm justify-content-center js-ml-btn-tab-'+name+'" id="'+mwBtnTabLocaleId+'" lang="'+locales[i]+'" data-toggle="tab" href="javascript:;" x-href="#'+mwBtnTabContentLocaleId+'"><i class="flag-icon flag-icon-'+localeIcon+'"></i>'+localeUppercase+'</a>';

                    $('body').on('click','#' + mwBtnTabLocaleId, function (){
                        mw.trigger("mlChangedLanguage", $(this).attr('lang'));
                    });
                }
                outputHtml += '</nav>';

                // tab contents
                var mwTabContentLocaleId = 'ml-tab-content-'+name;
                outputHtml += '<div id="'+mwTabContentLocaleId+'" class="tab-content py-3">';
                for (var i = 0; i < locales.length; i++) {
                    var mwTabPaneLocaleId = 'ml-tab-content-'+name+'-'+i;

                    outputHtml += '<div class="tab-pane fade" id="'+mwTabPaneLocaleId+'" lang="'+locales[i]+'">';
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

            // Switch tabs
            function switchTabsToLanguage(language) {
                $('#'+mwNavLocaleId).find('.btn').removeClass('active');
                $('#'+mwNavLocaleId).find(`[lang='${language}']`).addClass('active');
                $('#'+mwTabContentLocaleId).find('.tab-pane').removeClass('active show');
                $('#'+mwTabContentLocaleId).find(`.tab-pane[lang='${language}']`).addClass('active show');
            }

            // Show for current lang
            var mlLangIsSupported = false;
            for (var i = 0; i < locales.length; i++) {
                if (locales[i] == currentLocale) {
                    mlLangIsSupported = true;
                }
            }
            if (mlLangIsSupported) {
                switchTabsToLanguage(currentLocale);
            } else {
                switchTabsToLanguage(locales[0]);
            }

            // Listen for events
            mw.on("mlChangedLanguage", function (e, mlCurrentLanguage) {
                switchTabsToLanguage(mlCurrentLanguage);
            });

            if (mwEditor) {
                $('#'+mwTabContentLocaleId).find('.tab-pane textarea').each(function () {
                    mw.Editor({
                        selector: $(this),
                        inputLanguage: this.lang,
                        mode: 'div',
                        smallEditor: false,
                        minHeight: 250,
                        maxHeight: '70vh',
                        controls: [
                            [
                                'undoRedo', '|', 'image', '|',
                                {
                                    group: {
                                        controller: 'bold',
                                        controls: ['italic', 'underline', 'strikeThrough']
                                    }
                                },
                                '|',
                                {
                                    group: {
                                        icon: 'mdi mdi-format-align-left',
                                        controls: ['align']
                                    }
                                },
                                '|', 'format',
                                {
                                    group: {
                                        icon: 'mdi mdi-format-list-bulleted-square',
                                        controls: ['ul', 'ol']
                                    }
                                },
                                '|', 'link', 'unlink', 'wordPaste', 'table', 'removeFormat', 'editSource'
                            ],
                        ]
                    });
                });
            }

        });
        return this;
    };
}(jQuery));
