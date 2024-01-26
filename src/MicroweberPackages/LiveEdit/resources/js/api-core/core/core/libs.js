(function (){
    var libs = {
        jqueryui: [
            function () {
                mw.require(mw.settings.libs_url + 'jqueryui' + '/jquery-ui.min.js');
                mw.require(mw.settings.libs_url + 'jqueryui' + '/jquery-ui.min.css');
            }
        ],
        morris: ['morris.css', 'raphael.js', 'morris.js'],
        rangy: ['rangy-core.js', 'rangy-cssclassapplier.js', 'rangy-selectionsaverestore.js', 'rangy-serializer.js'],
        highlight: [

            'highlight.min.js',
            'highlight.min.css'

        ],
        bootstrap2: [
            function () {
                var v = document.querySelector('meta[name="viewport"]');
                if (v === null) {
                    v = document.createElement('meta');
                    v.name = "viewport";
                }
                v.content = "width=device-width, initial-scale=1.0";
                mw.head.appendChild(v);
            },
            'css/bootstrap.min.css',
            'css/bootstrap-responsive.min.css',
            'js/bootstrap.min.js'
        ],
        bootstrap3: [
            function () {
                mw.require(mw.settings.libs_url + 'fontawesome-4.7.0' + '/css/font-awesome.min.css');
                var v = document.querySelector('meta[name="viewport"]');
                if (v === null) {
                    v = document.createElement('meta');
                    v.name = "viewport";
                }
                v.content = "width=device-width, initial-scale=1.0";
                mw.head.appendChild(v);
            },
            'css/bootstrap.min.css',
            'js/bootstrap.min.js'
        ],
        bootstrap4: [
            function () {
                mw.require(mw.settings.libs_url + 'bootstrap-4.3.1' + '/css/bootstrap.min.css');
                mw.require(mw.settings.libs_url + 'bootstrap-4.3.1' + '/js/popper.min.js');
                mw.require(mw.settings.libs_url + 'bootstrap-4.3.1' + '/js/bootstrap.min.js');
                mw.require(mw.settings.libs_url + 'fontawesome-free-5.12.0' + '/css/all.min.css');
            }
        ],
        microweber_ui: [
            function () {
                mw.require(mw.settings.libs_url + 'mw-ui' + '/grunt/plugins/ui/css/main.css');
                mw.require(mw.settings.libs_url + 'mw-ui' + '/assets/ui/plugins/css/plugins.min.css');
                mw.require(mw.settings.libs_url + 'mw-ui' + '/assets/ui/plugins/js/plugins.js');
            }


        ],

        flag_icons: [
            function () {
                mw.require(mw.settings.libs_url + 'flag-icon-css' + '/css/flag-icon.min.css');

            }
        ],
        font_awesome: [
            function () {
                mw.require(mw.settings.libs_url + 'fontawesome-4.7.0' + '/css/font-awesome.min.css');

            }
        ],
        font_awesome5: [
            function () {
                mw.require(mw.settings.libs_url + 'fontawesome-free-5.12.0' + '/css/all.min.css');

            }
        ],
        bxslider: [
            function () {
                mw.require(mw.settings.libs_url + 'bxSlider/jquery.bxslider.min.js', true);
                mw.require(mw.settings.libs_url + 'bxSlider/jquery.bxslider.css', true, undefined, true);

            }
        ],
        collapse_nav: [
            function () {
                mw.require(mw.settings.libs_url + 'collapse-nav/dist/collapseNav.js', true);
                mw.require(mw.settings.libs_url + 'collapse-nav/dist/collapseNav.css', true);

            }
        ],
        slick: [
            function () {
                mw.require(mw.settings.libs_url + 'slick' + '/slick.css', true, undefined, true);
                mw.require(mw.settings.libs_url + 'slick' + '/slick-theme.css', undefined, undefined, true);
                mw.require(mw.settings.libs_url + 'slick' + '/slick.min.js', true);
            }
        ],
        bootstrap_datepicker: [
            function () {
                mw.require(mw.settings.libs_url + 'bootstrap-datepicker' + '/css/bootstrap-datepicker3.css', true);
                mw.require(mw.settings.libs_url + 'bootstrap-datepicker' + '/js/bootstrap-datepicker.js', true);
            }
        ],
        bootstrap_datetimepicker: [
            function () {
                mw.require(mw.settings.libs_url + 'bootstrap-datetimepicker' + '/css/bootstrap-datetimepicker.min.css', true);
                mw.require(mw.settings.libs_url + 'bootstrap-datetimepicker' + '/js/bootstrap-datetimepicker.min.js', true);
            }
        ],
        bootstrap3ns: [
            function () {

                //var bootstrap_enabled = (typeof $().modal == 'function');
                var bootstrap_enabled = (typeof $ != 'undefined' && typeof $.fn != 'undefined' && typeof $.fn.emulateTransitionEnd != 'undefined');

                if (!bootstrap_enabled) {
                    mw.require(mw.settings.libs_url + 'bootstrap3' + '/js/bootstrap.min.js');
                    //var bootstrap_enabled = (typeof $().modal == 'function');
                    //if (bootstrap_enabled == false) {
                    mw.require(mw.settings.libs_url + 'bootstrap3ns' + '/bootstrap.min.css');
                    mw.require(mw.settings.libs_url + 'fontawesome-4.7.0' + '/css/font-awesome.min.css');
                }
                // }
            }
        ],
        bootstrap_select: [
            function () {
                //var bootstrap_enabled = (typeof $().modal == 'function');
                //if (!bootstrap_enabled == false) {
                mw.require(mw.settings.libs_url + 'bootstrap-select-1.13.12' + '/js/bootstrap-select.min.js');
                mw.require(mw.settings.libs_url + 'bootstrap-select-1.13.12' + '/css/bootstrap-select.min.css');
                //}
            }
        ],
        bootstrap_tags: [
            function () {

                // var bootstrap_enabled = (typeof $().modal == 'function');
                //if (!bootstrap_enabled == false) {
                mw.require(mw.settings.libs_url + 'typeahead' + '/typeahead.jquery.js');
                mw.require(mw.settings.libs_url + 'typeahead' + '/typeahead.bundle.min.js');
                mw.require(mw.settings.libs_url + 'typeahead' + '/bloodhound.js');
                mw.require(mw.settings.libs_url + 'mw-ui/grunt/plugins/tags' + '/bootstrap-tagsinput.css');
                mw.require(mw.settings.libs_url + 'mw-ui/grunt/plugins/tags' + '/bootstrap-tagsinput.js');
                //} else {
                //mw.log("You must load bootstrap to use bootstrap_tags");
                //}

            }
        ],
        chosen: [
            function () {
                mw.require(mw.settings.libs_url + 'chosen' + '/chosen.jquery.min.js');
                mw.require(mw.settings.libs_url + 'chosen' + '/chosen.min.css', true);
            }
        ],
        validation: [
            function () {
                mw.require(mw.settings.libs_url + 'jquery_validation' + '/js/jquery.validationEngine.js');
                mw.require(mw.settings.libs_url + 'jquery_validation' + '/js/languages/jquery.validationEngine-en.js');
                mw.require(mw.settings.libs_url + 'jquery_validation' + '/css/validationEngine.jquery.css');
            }
        ],

        fitty: [
            function () {
                mw.require(mw.settings.libs_url + 'fitty' + '/dist/fitty.min.js');
                /*$(document).ready(function () {
                 fitty('.fitty-element');
                 });*/
            }
        ],


        flatstrap3: [
            function () {
                var v = document.querySelector('meta[name="viewport"]');
                if (v === null) {
                    v = document.createElement('meta');
                    v.name = "viewport";
                }
                v.content = "width=device-width, initial-scale=1.0";
                mw.head.appendChild(v);
            },
            'css/bootstrap.min.css',
            'js/bootstrap.min.js'
        ],
        datepicker: [
            'datepicker.min.js',
            'datepicker.min.css'
        ],
        datetimepicker: [
            'jquery.datetimepicker.full.min.js',
            'jquery.datetimepicker.min.css'
        ],

        nestedSortable: [
            function () {
                mw.require(mw.settings.libs_url + 'nestedsortable' + '/jquery.mjs.nestedSortable.js');
            }
        ],
        colorpicker: [
            function () {
                 mw.require(mw.settings.libs_url + 'acolorpicker' + '/acolorpicker.js');
            }
        ],
        material_icons: [
            function () {
                mw.require(mw.settings.libs_url + 'material_icons' + '/material_icons.css');
            }
        ],
        materialDesignIcons: [
            function () {
                mw.require('css/fonts/materialdesignicons/css/materialdesignicons.min.css');
            }
        ],
        mw_icons_mind: [
            function () {
                mw.require('fonts/mw-icons-mind/line/style.css');
                mw.require('fonts/mw-icons-mind/solid/style.css');
            }
        ],
        uppy: [
            'uppy.min.js',
            'uppy.min.css'
        ],
        apexcharts: [
            'apexcharts.min.js',
            'apexcharts.css'
        ]
    };


    mw.lib = {
        _required: [],
        require: function (name) {
            if (mw.lib._required.indexOf(name) !== -1) {
                return false;
            }
            mw.lib._required.push(name);
            if (typeof libs[name] === 'undefined') return false;
            if (libs[name].constructor !== [].constructor) return false;
            var path = mw.settings.libs_url + name + '/',
                arr = libs[name],
                l = arr.length,
                i = 0,
                c = 0;
            for (; i < l; i++) {
                (typeof arr[i] === 'string') ? mw.require(path + arr[i], true) : (typeof arr[i] === 'function') ? arr[i].call() : '';
            }
        },
        get: function (name, done, error) {
            if (mw.lib._required.indexOf(name) !== -1) {
                if (typeof done === 'function') {
                    done.call();
                }
                return false;
            }

            if (typeof libs[name] === 'undefined') return false;
            if (libs[name].constructor !== [].constructor) return false;
            mw.lib._required.push(name);
            var path = mw.settings.libs_url + name + '/',
                arr = libs[name],
                l = arr.length,
                i = 0,
                c = 1;
            for (; i < l; i++) {
                var xhr = $.cachedScript(path + arr[i]);
                xhr.done(function () {
                    c++;
                    if (c === l) {
                        if (typeof done === 'function') {
                            done.call();
                        }
                    }
                });
                xhr.fail(function (jqxhr, settings, exception) {

                    if (typeof error === 'function') {
                        error.call(jqxhr, settings, exception);
                    }

                });
            }
        }
    };

})();
