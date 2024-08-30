(function ($) {


    $.fn.mlTextArea = function (options) {
        var settings = $.extend({
            name: false,
            locales: [],
            currentLocale: false,
            defaultLocale: false,
            translations: [],
            attributes: [],
            mwEditor: false,
        }, options);

        this.each(function (index, obj) {
            var name = settings.name;
            var currentLocale = settings.currentLocale;
            var defaultLocale = settings.defaultLocale;
            var locales = settings.locales;
            var translations = settings.translations;
            var attributes = settings.attributes;
            var mwEditor = settings.mwEditor;

            if (!name.length || !locales.length || !currentLocale.length) {
                console.log('Please fill the name and locales.');
                return;
            }

            $(obj).css('opacity', 0.2).css('display', 'none');
            $(obj).attr('value', translations[currentLocale]);
            $(obj).attr('lang', currentLocale);

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

            var outputHtml = '<div class="bs-component">';

                var mwNavLocaleId = 'ml-nav-'+plainName;
                outputHtml += '<nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-1" id="'+mwNavLocaleId+'">';

                // tab buttons
                for (var i = 0; i < locales.length; i++) {
                    var mwBtnTabLocaleId = 'ml-tab-btn-'+plainName+'-'+i;
                    var mwBtnTabContentLocaleId = 'ml-tab-content-'+plainName+'-'+i;

                    var localeIcon = locales[i];
                    var localeIconSplit = localeIcon.split('_');

                    if (typeof localeIconSplit[1] !== 'undefined') {
                        localeIcon = localeIconSplit[1];
                        localeIcon = localeIcon.toLowerCase();
                    }

                    var localeUppercase = locales[i].toUpperCase();

                    var localeDisplay = localeUppercase.split('_').slice(1);

                    outputHtml += '<a class="btn btn-outline-dark border-0 flex-none shadow-none justify-content-center js-ml-btn-tab-'+plainName+'" id="'+mwBtnTabLocaleId+'" lang="'+locales[i]+'" data-toggle="tab" href="javascript:;" x-href="#'+mwBtnTabContentLocaleId+'"><i class="me-2 flag-icon flag-icon-'+localeIcon+'"></i>'+localeDisplay+'</a>';

                    $('body').on('click','#' + mwBtnTabLocaleId, function (){
                        mw.trigger("mlChangedLanguage", $(this).attr('lang'));
                    });
                }
                outputHtml += '</nav>';

                // tab contents
                var mwTabContentLocaleId = 'ml-tab-content-'+plainName;
                outputHtml += '<div id="'+mwTabContentLocaleId+'" class="tab-content py-3">';
                for (var i = 0; i < locales.length; i++) {
                    var mwTabPaneLocaleId = 'ml-tab-content-'+plainName+'-'+i;

                    var mlInputName = 'multilanguage['+name+']['+locales[i]+']';

                    // for multidimensional names
                    if (name.match(/\[[^\]]*]/g)) {
                        mlInputName = 'multilanguage'
                        $.each($(obj).serializeAssoc(), function(key, values) {
                            mlInputName += '['+key+']['+Object.keys(values)[0]+']';
                        });
                        mlInputName += '['+locales[i]+']';
                    }

                    var input  = $('<textarea class="form-control" name="'+mlInputName+'" lang="'+locales[i]+'">'+translations[locales[i]]+'</textarea>');

                    $.each(attributes, function(name, value) {
                        if(!$(input).attr(name)){
                            $(input).attr(name,value);
                        }
                    });

                    var inputHtml =  input[0].outerHTML;

                    outputHtml += '<div class="tab-pane fade" id="'+mwTabPaneLocaleId+'" lang="'+locales[i]+'">';
                    outputHtml += inputHtml;
                    outputHtml += '</div>';

                    // If ml textarea is changed change and the value
                    $('body').on('keyup change','#' + mwTabPaneLocaleId+' textarea', function () {
                         //if (currentLocale == $(this).attr('lang')) {
                        if (defaultLocale == $(this).attr('lang')) {
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
                    var editor = mw.Editor({
                        selector: $(this),
                        inputLanguage: this.lang,
                        mode: 'div',
                        smallEditor: false,
                        onSave: settings.onSave,
                        minHeight: 250,
                        maxHeight: 'none',
                        stickyBar: true,
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
                                '|', 'link', 'unlink', 'wordPaste', 'table', 'removeFormat' , 'editSource'
                            ],
                        ]
                    });
                });
            }

        });
        return this;
    };
}(jQuery));
