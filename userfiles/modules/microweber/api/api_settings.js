(function () {

    if(window.mw) {
        console.log('%c !!! mw already defined !!! ', 'background: #009cff; color: #fff; font-size:16px;');
        return;
    }

    var mw = { };

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

        edit_area_placeholder: '<div class="empty-element-edit-area empty-element ui-state-highlight ui-sortable-placeholder"><span><?php _e("Please drag items here"); ?></span></div>',
        empty_column_placeholder: '<div id="_ID_" class="empty-element empty-element-column"><?php _e("Please drag items here"); ?></div>',
        handles: {
            item: "<div title='<?php _e("Click to select this item"); ?>.' class='mw_master_handle' id='items_handle'></div>"
        },
        sorthandle_delete_confirmation_text: "<?php _e("Are you sure you want to delete this element"); ?>?"
    }

    mw.settings.libs = {
            jqueryui:  [
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
                var v = mwd.querySelector('meta[name="viewport"]');
                if (v === null) {
                    v = mwd.createElement('meta');
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
                var v = mwd.querySelector('meta[name="viewport"]');
                if (v === null) {
                    v = mwd.createElement('meta');
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

                if(!bootstrap_enabled){
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
                mw.require(mw.settings.libs_url + 'bootstrap_tags' + '/bootstrap-tagsinput.css');
                mw.require(mw.settings.libs_url + 'bootstrap_tags' + '/bootstrap-tagsinput.js');
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
                var v = mwd.querySelector('meta[name="viewport"]');
                if (v === null) {
                    v = mwd.createElement('meta');
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
        nzzestedSortable: [
            'jquery.mjs.nestedSortable.js'
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
        get: function (name, done, error) {
            if (mw.lib._required.indexOf(name) !== -1) {
                if (typeof done === 'function') {
                    done.call();
                }
                return false;
            }

            if (typeof mw.settings.libs[name] === 'undefined') return false;
            if (mw.settings.libs[name].constructor !== [].constructor) return false;
            mw.lib._required.push(name);
            var path = mw.settings.libs_url + name + '/',
                arr = mw.settings.libs[name],
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

    mw.lang = function (key) {
        var camel = key.trim().replace(/(?:^\w|[A-Z]|\b\w)/g, function (letter, index) {
            return index == 0 ? letter.toLowerCase() : letter.toUpperCase();
        }).replace(/\s+/g, '');
        if (mw._lang[camel]) {
            return mw._lang[camel];
        }
        else {
            console.warn('"' + key + '" is not present.');
            return key;
        }
    };
    mw.msg = mw._lang = {
        uniqueVisitors: '<?php _e("Unique visitors"); ?>',
        allViews: '<?php _e("All views"); ?>',
        date: '<?php _e("Date"); ?>',
        weekDays: {
            regular: [
                '<?php _e("Sunday"); ?>',
                '<?php _e("Monday"); ?>',
                '<?php _e("Tuesday"); ?>',
                '<?php _e("Wednesday"); ?>',
                '<?php _e("Thursday"); ?>',
                '<?php _e("Friday"); ?>',
                '<?php _e("Saturday"); ?>'
            ],
            short: [
                '<?php _e("Sun"); ?>',
                '<?php _e("Mon"); ?>',
                '<?php _e("Tue"); ?>',
                '<?php _e("Wed"); ?>',
                '<?php _e("Thu"); ?>',
                '<?php _e("Fri"); ?>',
                '<?php _e("Sat"); ?>'
            ]
        },
        months: {
            regular: [
                '<?php _e("January") ?>',
                '<?php _e("February") ?>',
                '<?php _e("March") ?>',
                '<?php _e("April") ?>',
                '<?php _e("May") ?>',
                '<?php _e("June") ?>',
                '<?php _e("July") ?>',
                '<?php _e("August") ?>',
                '<?php _e("September") ?>',
                '<?php _e("October") ?>',
                '<?php _e("November") ?>',
                '<?php _e("December") ?>'
            ],
            short: [
                '<?php _e("Jan") ?>',
                '<?php _e("Feb") ?>',
                '<?php _e("Mar") ?>',
                '<?php _e("Apr") ?>',
                '<?php _e("May") ?>',
                '<?php _e("June") ?>',
                '<?php _e("July") ?>',
                '<?php _e("Aug") ?>',
                '<?php _e("Sept") ?>',
                '<?php _e("Oct") ?>',
                '<?php _e("Nov") ?>',
                '<?php _e("Dec") ?>'
            ]
        },
        ok: "<?php _e('OK');  ?>",
        category: "<?php _e('Category');  ?>",
        published: "<?php _e('Published');  ?>",
        unpublished: "<?php _e('Unpublished');  ?>",
        contentunpublished: "<?php _e("Content is unpublished"); ?>",
        contentpublished: "<?php _e("Content is published"); ?>",
        save: "<?php _e('Save');  ?>",
        saving: "<?php _e('Saving');  ?>",
        saved: "<?php _e('Saved');  ?>",
        settings: "<?php _e('Settings');  ?>",
        cancel: "<?php _e('Cancel');  ?>",
        remove: "<?php _e('Remove');  ?>",
        close: "<?php _e('Close');  ?>",
        to_delete_comment: "<?php _e('Are you sure you want to delete this comment'); ?>",
        del: "<?php _e('Are you sure you want to delete this?'); ?>",
        save_and_continue: "<?php _e('Save &amp; Continue'); ?>",
        before_leave: "<?php _e("Leave without saving"); ?>",
        session_expired: "<?php _e("Your session has expired"); ?>",
        login_to_continue: "<?php _e("Please login to continue"); ?>",
        more: "<?php _e("More"); ?>",
        templateSettingsHidden: "<?php _e("Template settings"); ?>",
        less: "<?php _e("Less"); ?>",
        product_added: "<?php _e("Your product is added to cart"); ?>",
        no_results_for: "<?php _e("No results for"); ?>",
        switch_to_modules: '<?php _e("Switch to Modules"); ?>',
        switch_to_layouts: '<?php _e("Switch to Layouts"); ?>',
        loading: '<?php _e("Loading"); ?>',
        edit: '<?php _e("Edit"); ?>',
        change: '<?php _e("Change"); ?>',
        submit: '<?php _e("Submit"); ?>',
        settingsSaved: '<?php _e("Settings are saved"); ?>',
        addImage: '<?php _e("Add new image"); ?>'
    };


    if(!window.mw) {
        window.mw = mw;
    }
})();


