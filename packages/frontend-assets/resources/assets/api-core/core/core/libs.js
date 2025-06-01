(function (){



    var libs = {
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
                mw.require(mw.settings.libs_url + 'flag-icons' + '/css/flag-icon.min.css');

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
                mw.require(mw.settings.libs_url + 'font-awesome/css/font-awesome.css');

            }
        ],
        font_awesome5: [
            function () {
                mw.require(mw.settings.libs_url + 'fontawesome-free-5.12.0' + '/css/all.min.css');

            }
        ],
        bxslider: [
            function () {
                console.log(888, mw.settings.libs_url)
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
        "async-alpine": [
            function () {
                mw.require(mw.settings.libs_url + 'async-alpine/async-alpine.script.js');

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
    }


    };

})();
