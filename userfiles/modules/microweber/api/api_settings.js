(function () {

    if (window.mw) {
        console.log('%c !!! mw already defined !!! ', 'background: #009cff; color: #fff; font-size:16px;');
        return;
    }

    var mw = {};

    mw.settings = {
        regions: false,
        liveEdit: false,
        debug: true,
        basic_mode: false,
        site_url: '<?php print site_url(); ?>',
        template_url: '<?php print TEMPLATE_URL; ?>',
        modules_url: '<?php print modules_url(); ?>',
        includes_url: '<?php   print( mw_includes_url());  ?>',
        upload_url: '<?php print site_url(); ?>api/upload/',
        api_url: '<?php print site_url(); ?>api/',
        libs_url: '<?php   print( mw_includes_url());  ?>api/libs/',
        api_html: '<?php print site_url(); ?>api_html/',
        editables_created: false,
        element_id: false,
        text_edit_started: false,
        sortables_created: false,
        drag_started: false,
        sorthandle_hover: false,
        resize_started: false,
        sorthandle_click: false,
        row_id: false,

        edit_area_placeholder: '<div class="empty-element-edit-area empty-element ui-state-highlight ui-sortable-placeholder"><span><?php _ejs("Please drag items here"); ?></span></div>',
        empty_column_placeholder: '<div id="_ID_" class="empty-element empty-element-column"><?php _ejs("Please drag items here"); ?></div>',
        handles: {
            item: "<div title='<?php _ejs("Click to select this item"); ?>.' class='mw_master_handle' id='items_handle'></div>"
        },
        sorthandle_delete_confirmation_text: "<?php _ejs("Are you sure you want to delete this element"); ?>?"
    }

    mw.settings.libs = {
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
                mwhead.appendChild(v);
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
                mwhead.appendChild(v);
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

        bootstrap5js: [
            function () {
                 mw.require(mw.settings.libs_url + 'bootstrap5' + '/js/bootstrap.bundle.min.js');
            }
        ],
        bootstrap5: [
            function () {
                mw.require(mw.settings.libs_url + 'bootstrap5' + '/css/bootstrap.' + (document.documentElement.dir==='rtl' ? 'rtl.' : '') + 'min.css');
                mw.lib.require('bootstrap5js')
             }
        ],
        microweber_ui: [
            function () {
                mw.require(mw.settings.libs_url + 'mw-ui' + '/grunt/plugins/ui/css/main.css');
                mw.require(mw.settings.libs_url + 'mw-ui' + '/assets/ui/plugins/css/plugins.min.css');
                mw.require(mw.settings.libs_url + 'mw-ui' + '/assets/ui/plugins/js/plugins.js');
            }


        ],
        mwui: [
            function () {
                // mw.require(mw.settings.libs_url + 'mw-ui' + '/grunt/plugins/ui/css/main.css');
                // mw.require(mw.settings.libs_url + 'mw-ui' + '/assets/ui/plugins/css/plugins.min.css');
                // mw.require(mw.settings.libs_url + 'mw-ui' + '/grunt/plugins/ui/css/mw.css');
                //The files above are added in default.css
                mw.require(mw.settings.libs_url + 'mw-ui' + '/assets/ui/plugins/js/plugins.js');
            }


        ],
        mwui_init: [
            function () {
                mw.require(mw.settings.libs_url + 'mw-ui' + '/grunt/plugins/ui/js/ui.js');
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
                mw.require(mw.settings.libs_url + 'bxSlider/jquery.bxslider.css', true);

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
                mw.require(mw.settings.libs_url + 'slick' + '/slick.css', true);
                mw.moduleCSS(mw.settings.libs_url + 'slick' + '/slick-theme.css');
                mw.require(mw.settings.libs_url + 'slick' + '/slick.min.js', true);
            }
        ],
        ion_range_slider: [
            function () {
                mw.require(mw.settings.libs_url + 'ion-range-slider' + '/css/ion.rangeSlider.min.css', true);
                mw.require(mw.settings.libs_url + 'ion-range-slider' + '/js/ion.rangeSlider.min.js', true);
            }
        ],
        air_datepicker: [
            function () {

                mw.require(mw.settings.libs_url + 'air-datepicker' + '/css/datepicker.min.css', true);
                mw.require(mw.settings.libs_url + 'air-datepicker' + '/js/datepicker.min.js', true);

                $.fn.datepicker.language['en'] = {
                    days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                    daysMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                    daysShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    today: 'Today',
                    clear: 'Clear',
                    dateFormat: 'yyyy-mm-dd',
                    firstDay: 0
                };

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
                mwhead.appendChild(v);
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
                mw.require(mw.settings.includes_url + 'api' + '/color.js');
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
                mw.require(mw.settings.modules_url + 'microweber/css/fonts/materialdesignicons/css/materialdesignicons.min.css');
            }
        ],
        mw_icons_mind: [
            function () {
                mw.require(mw.settings.modules_url + 'microweber/css/fonts/mw-icons-mind/line/style.css');
                mw.require(mw.settings.modules_url + 'microweber/css/fonts/mw-icons-mind/solid/style.css');
            }
        ],
        uppy: [
            'uppy.min.js',
            'uppy.min.css'
        ],
        apexcharts: [
            'apexcharts.min.js',
            'apexcharts.css'
        ],
        anchorific: [
            function () {
                mw.require(mw.settings.libs_url + 'anchorific' + '/anchorific.min.js', true);
            }
        ],
        multilanguage: [
            function () {
                mw.require(mw.settings.libs_url + 'multilanguage' + '/mlInput.js');
                mw.require(mw.settings.libs_url + 'multilanguage' + '/mlTextArea.js');
            }
        ],
        codemirror: [
            function () {
                mw.require(mw.settings.libs_url + 'codemirror' + '/codemirror.min.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/css.min.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/xml.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/javascript.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/css.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/csslint.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/css-lint-plugin.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/htmlmixed.min.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/php.min.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/autorefresh.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/selection-pointer.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/xml-fold.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/matchtags.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/beautify.min.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/beautify-css.min.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/beautify-html.min.js');
                mw.require(mw.settings.libs_url + 'codemirror' + '/style.css');
            }

        ]
    };

    mw.lib = {
        _required: [],
        require: function (name) {
            if (mw.lib._required.indexOf(name) !== -1) {
                return false;
            }
            mw.lib._required.push(name);
            if (typeof mw.settings.libs[name] === 'undefined') return false;
            if (mw.settings.libs[name].constructor !== [].constructor) return false;
            var path = mw.settings.libs_url + name + '/',
                arr = mw.settings.libs[name],
                l = arr.length,
                i = 0,
                c = 0;
            for (; i < l; i++) {
                (typeof arr[i] === 'string') ? mw.require(path + arr[i], true) : (typeof arr[i] === 'function') ? arr[i].call() : '';
            }
        },
    };

    mw.lang = function (key) {
        var camel = key.trim().replace(/(?:^\w|[A-Z]|\b\w)/g, function (letter, index) {
            return index == 0 ? letter.toLowerCase() : letter.toUpperCase();
        }).replace(/\s+/g, '');
        if (mw._lang[camel]) {
            return mw._lang[camel];
        }
        else {
            // console.warn('"' + key + '" is not present.');
            return key;
        }
    };
    mw.msg = mw._lang = {
        uniqueVisitors: '<?php _ejs("Unique visitors"); ?>',
        allViews: '<?php _ejs("All views"); ?>',
        date: '<?php _ejs("Date"); ?>',
        weekDays: {
            regular: [
                '<?php _ejs("Sunday"); ?>',
                '<?php _ejs("Monday"); ?>',
                '<?php _ejs("Tuesday"); ?>',
                '<?php _ejs("Wednesday"); ?>',
                '<?php _ejs("Thursday"); ?>',
                '<?php _ejs("Friday"); ?>',
                '<?php _ejs("Saturday"); ?>'
            ],
            short: [
                '<?php _ejs("Sun"); ?>',
                '<?php _ejs("Mon"); ?>',
                '<?php _ejs("Tue"); ?>',
                '<?php _ejs("Wed"); ?>',
                '<?php _ejs("Thu"); ?>',
                '<?php _ejs("Fri"); ?>',
                '<?php _ejs("Sat"); ?>'
            ]
        },
        months: {
            regular: [
                '<?php _ejs("January") ?>',
                '<?php _ejs("February") ?>',
                '<?php _ejs("March") ?>',
                '<?php _ejs("April") ?>',
                '<?php _ejs("May") ?>',
                '<?php _ejs("June") ?>',
                '<?php _ejs("July") ?>',
                '<?php _ejs("August") ?>',
                '<?php _ejs("September") ?>',
                '<?php _ejs("October") ?>',
                '<?php _ejs("November") ?>',
                '<?php _ejs("December") ?>'
            ],
            short: [
                '<?php _ejs("Jan") ?>',
                '<?php _ejs("Feb") ?>',
                '<?php _ejs("Mar") ?>',
                '<?php _ejs("Apr") ?>',
                '<?php _ejs("May") ?>',
                '<?php _ejs("June") ?>',
                '<?php _ejs("July") ?>',
                '<?php _ejs("Aug") ?>',
                '<?php _ejs("Sept") ?>',
                '<?php _ejs("Oct") ?>',
                '<?php _ejs("Nov") ?>',
                '<?php _ejs("Dec") ?>'
            ]
        },
        ok: "<?php _ejs('OK');  ?>",
        category: "<?php _ejs('Category');  ?>",
        published: "<?php _ejs('Published');  ?>",
        unpublished: "<?php _ejs('Unpublished');  ?>",
        contentunpublished: "<?php _ejs("Content is unpublished"); ?>",
        contentpublished: "<?php _ejs("Content is published"); ?>",
        save: "<?php _ejs('Save');  ?>",
        saving: "<?php _ejs('Saving');  ?>",
        saved: "<?php _ejs('Saved');  ?>",
        settings: "<?php _ejs('Settings');  ?>",
        cancel: "<?php _ejs('Cancel');  ?>",
        remove: "<?php _ejs('Remove');  ?>",
        close: "<?php _ejs('Close');  ?>",
        to_delete_comment: "<?php _ejs('Are you sure you want to delete this comment'); ?>",
        del: "<?php _ejs('Are you sure you want to delete this?'); ?>",
        save_and_continue: "<?php _ejs('Save &amp; Continue'); ?>",
        before_leave: "<?php _ejs("Leave without saving"); ?>",
        session_expired: "<?php _ejs("Your session has expired"); ?>",
        login_to_continue: "<?php _ejs("Please login to continue"); ?>",
        more: "<?php _ejs("More"); ?>",
        templateSettingsHidden: "<?php _ejs("Template settings"); ?>",
        less: "<?php _ejs("Less"); ?>",
        product_added: "<?php _ejs("Your product is added to cart"); ?>",
        no_results_for: "<?php _ejs("No results for"); ?>",
        switch_to_modules: '<?php _ejs("Switch to Modules"); ?>',
        switch_to_layouts: '<?php _ejs("Switch to Layouts"); ?>',
        loading: '<?php _ejs("Loading"); ?>',
        edit: '<?php _ejs("Edit"); ?>',
        change: '<?php _ejs("Change"); ?>',
        submit: '<?php _ejs("Submit"); ?>',
        settingsSaved: '<?php _ejs("Settings are saved"); ?>',
        addImage: '<?php _ejs("Add new image"); ?>'
    };


    if (!window.mw) {
        window.mw = mw;
    }
})();

<?php
if(isset($inline_scripts) and is_array($inline_scripts)){
    print implode($inline_scripts,"\n");
}

?>

