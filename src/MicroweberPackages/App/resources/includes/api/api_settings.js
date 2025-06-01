(function () {

    if(typeof mw === 'undefined'){
        mw = {};
    }

    const script = document.querySelector('script[data-public-url]');
    let baseURL, templateURL ;

    if(script) {
        baseURL = script.dataset.publicUrl;
        templateURL = script.dataset.templateUrl;
    } else {
        baseURL = '';
        templateURL = '';
    }


    const mwurl = path => {

        if(!path) {
            return baseURL;
        }
        if(path.indexOf("/") !== 0) {
            path = '/' + path;
        }
        let res = `${baseURL}${path || ''}`.replace(/([^:])(\/\/+)/g, '$1/');

        return res;
    };





     mw.settings = {
         strictMode: false,
         regions: false,
         liveEdit: false,
         debug: true,
         basic_mode: false,
         site_url: baseURL,
         template_url: templateURL,
         modules_url: mwurl('/userfiles/modules/'),
         includes_url: mwurl('/userfiles/modules/microweber/'),
         includes_url: mwurl('/vendor/microweber-packages/frontend-assets-libs/'),
         upload_url: mwurl('/api/upload/'),
         api_url: mwurl('/api/'),
         libs_url: mwurl('/vendor/microweber-packages/frontend-assets-libs/'),
         api_html: mwurl('/api_html/'),
         editables_created: false,
         element_id: false,
         text_edit_started: false,
         sortables_created: false,
         drag_started: false,
         sorthandle_hover: false,
         resize_started: false,
         sorthandle_click: false,
         row_id: false,

     };

     const assetsURL = mw.settings.site_url + "vendor/microweber-packages/frontend-assets";
     const assetsLibsURL = mw.settings.site_url + "vendor/microweber-packages/frontend-assets-libs";

     mw.settings.libs = {

        jqueryui: [
            function () {
                mw.require(mw.settings.libs_url + 'jqueryui' + '/jquery-ui.min.js');
                mw.require(mw.settings.libs_url + 'jqueryui' + '/jquery-ui.min.css');
            }
        ],

        rangy: ['rangy-core.js', 'rangy-classapplier.js', 'rangy-selectionsaverestore.js', 'rangy-serializer.js'],
        highlight: [

            function () {
                mw.require(mw.settings.libs_url + 'highlight-js/highlight.min.js');
                mw.require(mw.settings.libs_url + 'highlight-js/default.css');

            }

        ],

        flag_icons: [
            function () {
                mw.require(mw.settings.libs_url + 'flag-icons/css/flag-icons.css');

            }
        ],
        swiper: [
            function () {

                mw.require(mw.settings.libs_url + 'swiper/swiper-bundle.min.js');
                mw.require(mw.settings.libs_url + 'swiper/swiper-bundle.min.css');

            }
        ],
        font_awesome: [
            function () {
                mw.require(mw.settings.libs_url + 'font-awesome/font-awesome.css');

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
                mw.require(mw.settings.libs_url + 'collapse-nav/collapse-nav.js', true);
                mw.require(mw.settings.libs_url + 'collapse-nav/collapse-nav.css', true);

            }
        ],
        slick: [
            function () {
                mw.require(mw.settings.libs_url + 'slick/slick.css', true, undefined, true);
                mw.require(mw.settings.libs_url + 'slick/slick-theme.css', undefined, undefined, true);
                mw.require(mw.settings.libs_url + 'slick/slick.js', true);
                mw.require(mw.settings.libs_url + 'slick/mw-slick.js', true);
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


                var bootstrap_enabled = (typeof $ != 'undefined' && typeof $.fn != 'undefined' && typeof $.fn.emulateTransitionEnd != 'undefined');

                if (!bootstrap_enabled) {
                    mw.require(mw.settings.libs_url + 'bootstrap3' + '/js/bootstrap.min.js');

                    mw.require(mw.settings.libs_url + 'bootstrap3ns' + '/bootstrap.min.css');
                    mw.require(mw.settings.libs_url + 'font-awesome/font-awesome.css');
                }

            }
        ],
        bootstrap_select: [
            function () {

                mw.require(mw.settings.libs_url + 'bootstrap-select-1.13.12' + '/js/bootstrap-select.min.js');
                mw.require(mw.settings.libs_url + 'bootstrap-select-1.13.12' + '/css/bootstrap-select.min.css');

            }
        ],

        chosen: [
            function () {
                mw.require(mw.settings.libs_url + 'chosen' + '/chosen.jquery.min.js');
                mw.require(mw.settings.libs_url + 'chosen' + '/chosen.min.css', true);
            }
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
                mw.require(mw.settings.libs_url + 'material-icons/material-icons.css');
            }
        ],
        masonry: [
            function () {
                mw.require(mw.settings.libs_url + 'masonry/masonry.pkgd.js');
            }
        ],
        codemirror: [
            function () {
                mw.require(mw.settings.libs_url + 'codemirror/codemirror.js');
                mw.require(mw.settings.libs_url + 'codemirror/codemirror.css');
            }
        ],
        easymde: [
            function () {
                mw.require(mw.settings.libs_url + 'easymde/easymde.min.js');
                mw.require(mw.settings.libs_url + 'easymde/easymde.min.css');
            }
        ],

        nouislider: [
            function () {
                mw.require(mw.settings.libs_url + 'nouislider/nouislider.js');
                mw.require(mw.settings.libs_url + 'nouislider/nouislider.css');
            }
        ],



        "justified-gallery": [
            function () {
                mw.require(mw.settings.libs_url + 'justified-gallery/justified-gallery.js');
                mw.require(mw.settings.libs_url + 'justified-gallery/justified-gallery.css');
            }
        ],

        "async-alpine": [
            function () {
                mw.require(mw.settings.libs_url + 'async-alpine/async-alpine.script.js');

            }
        ],
        "bootstrap_datepicker": [
            function () {
                mw.require(mw.settings.libs_url + 'bootstrap_datepicker/bootstrap-datepicker.min.js');
                mw.require(mw.settings.libs_url + 'bootstrap_datepicker/bootstrap-datepicker.min.css');
            }
        ],
         "webfontloader": [
             function () {
                 mw.require(mw.settings.libs_url + 'webfontloader/webfontloader.js');
             }
         ],

     };
     mw.settings.xlibs = {};

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



 })();

 <?php
 if(isset($inline_scripts) and is_array($inline_scripts)){
     print implode("\n", $inline_scripts);
 }



 ?>

 mw.uploadGlobalSettings = {
     on: {
         beforeFileUpload: function (instance) {
             return new Promise(function (resolve){
                 var tokenFromCookie = mw.cookie.get("XSRF-TOKEN");
                 let xhr = new XMLHttpRequest()
                 xhr.open('POST', route('csrf-validate-token'), true)
                 xhr.setRequestHeader('Content-type', 'application/json; charset=UTF-8')
                 xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
                 xhr.setRequestHeader('X-XSRF-TOKEN', tokenFromCookie)
                 xhr.send('');
                 xhr.onload = function (res) {

                     if(xhr.status === 400) {
                         mw.cookie.delete("XSRF-TOKEN")
                         $.post(route('csrf'), function (res) {
                             var tokenFromCookie = mw.cookie.get("XSRF-TOKEN");
                             $.ajaxSetup({
                                 headers: {
                                     'X-XSRF-TOKEN': tokenFromCookie
                                 }
                             });
                             resolve();
                         });
                     } else {
                         resolve();
                     }
                 }
             });
         }
     }
 }

