<?php
/**
 * laravel-assets: asset management for Laravel 5
 *
 * Copyright (c) 2017 Greg Roach
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

return [
    // --------------------------------------------------------------------------
    // Enable the pipeline and filter functions.
    // --------------------------------------------------------------------------
    // You may want to disable this for development, to make debugging easier.
    'enabled' => 0,
    'use_cdn' => 0,

    // --------------------------------------------------------------------------
    // Where do we find the application's original assets?
    // --------------------------------------------------------------------------
    // This is a relative path from the root of the public folder.
    'css_source' => 'css',
    'js_source' => 'js',

    // --------------------------------------------------------------------------
    // Where do we store our generated (e.g. filtered and pipelined) assets?
    // --------------------------------------------------------------------------
    // This is a relative path from the root of the public folder.
    // It is also used as the default URL when generating links to assets.
    // IMPORTANT - you must have write permission to this folder.
    'destination' => 'min',

    // --------------------------------------------------------------------------
    // Absolute URL to our generated (e.g. filtered and pipelined) assets?
    // --------------------------------------------------------------------------
    // If you want to fetch assets from a cookie-free domain or a CDN, specify
    // specify the URL here.  e.g. "http://some-other-domain.com/min".
    // If left blank, a URL will be generated using the "destination" setting.
    'destination_url' => '',

    // --------------------------------------------------------------------------
    // What tools do we use to filter our CSS files?
    // --------------------------------------------------------------------------
    // The default set of tools minify the assets to improve performance
    // Filters must be instances of Fisharebest\LaravelAssets\FilterInterface
    'css_filters' => [
        //	new Fisharebest\LaravelAssets\Filters\RewriteCssUrls,
        //	new Fisharebest\LaravelAssets\Filters\MinifyCss,
    ],

    // --------------------------------------------------------------------------
    // What tools do we use to filter our JS files?
    // --------------------------------------------------------------------------
    // Before we can concatenate JS files we must ensure they end with a newline.
    // Filters must be instances of Fisharebest\LaravelAssets\FilterInterface
    'js_filters' => [
        //	new Fisharebest\LaravelAssets\Filters\FinalNewline,
        //	new Fisharebest\LaravelAssets\Filters\MinifyJs,
    ],

    // --------------------------------------------------------------------------
    // How do we load external files?
    // --------------------------------------------------------------------------
    // By default, we load files using file_get_contents().  If your server is
    // behind a proxy server or requires authentication, you can write your own.
    // Loaders must be instances of Fisharebest\LaravelAssets\LoaderInterface
    'loader' => new Fisharebest\LaravelAssets\Loaders\FileGetContents,

    // --------------------------------------------------------------------------
    // What should we do after we create an asset file?
    // --------------------------------------------------------------------------
    // You could use this opportunity to upload the asset file to your CDN.
    // Notifiers must be instances of Fisharebest\LaravelAssets\NotifierInterface
    'notifiers' => [],

    // --------------------------------------------------------------------------
    // Generate compressed versions of generated assets?
    // --------------------------------------------------------------------------
    // If set to an integer between 1 (fast) and 9 (best), a compressed ".gz"
    // version of each file will be generated.  6 is a good value to use.
    // See http://nginx.org/en/docs/http/ngx_http_gzip_static_module.html
    'gzip_static' => 0,

    // --------------------------------------------------------------------------
    // Should we inline small asset files?
    // --------------------------------------------------------------------------
    // Asset files smaller than this number of bytes will be rendered inline,
    // instead of being fetched in a separate HTTP request.
    // Typical values might be between 1024 and 2048.
    'inline_threshold' => 0,

    // --------------------------------------------------------------------------
    // A list of predefined resources.
    // --------------------------------------------------------------------------
    // Predefined groups of resources.  Note that the order of files determines
    // the order in which they are loaded.  Hence each collection should specify
    // its dependencies before its own files.
    'collections' => [
        'apijs' => [
            'js' => [function () {
                return app()->template->get_apijs_combined_url();
            }]
        ],
        'bootstrap' => 'bootstrap4',
        'bootstrap3' => [
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js'
        ],
        'bootstrap4' => [
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js'
        ],
        'datatables' => [
            'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.9/css/jquery.dataTables.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.9/js/jquery.dataTables.min.js',
        ],
        'datatables-bootstrap' => [
            'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.9/css/dataTables.bootstrap.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.9/js/jquery.dataTables.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.9/js/dataTables.bootstrap.min.js',
        ],
        'font-awesome' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css',
        'google-maps-api' => 'https://maps.googleapis.com/maps/api/js#.js',
        'smalot-bootstrap-datetimepicker' => [
            'https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.4/css/bootstrap-datetimepicker.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.4/js/bootstrap-datetimepicker.min.js',
        ],


        'jquery' => 'jquery-local',
        'jquery-local' => asset('userfiles/modules/microweber/api/libs/mw-ui/assets/ui/plugins/js/jquery-3.4.1.min.js'),


        'jquery-ui' => [
            'jquery',

            asset('userfiles/modules/microweber/api/libs/jqueryui/jquery-ui.js'),
            asset('userfiles/modules/microweber/api/libs/jqueryui/jquery-ui.css'),
        ],

        'admin' => [
            'apijs',
            'mw-ui',
            'jquery-ui',
            asset('userfiles/modules/microweber/api/dist/admin-libs.js'),
            asset('userfiles/modules/microweber/api/dist/admin.js'),
            asset('userfiles/modules/microweber/api/dist/editor.js'),
            asset('userfiles/modules/microweber/css/wysiwyg.css'),
            asset('userfiles/modules/microweber/css/admin.css'),

        ],
        'mw-ui-settings' => 'jquery',
        'mw-ui' => [
            //  'mw-ui-settings',
            'jquery',

            asset('userfiles/modules/microweber/api/libs/mw-ui/assets/ui/plugins/css/plugins.min.css'),
            asset('userfiles/modules/microweber/api/libs/mw-ui/grunt/plugins/ui/css/main_with_mw.css'),
            // asset('userfiles/modules/microweber/api/libs/mw-ui/grunt/plugins/ui/js/ui.js'),
            asset('userfiles/modules/microweber/default.css'),
            asset('userfiles/modules/microweber/css/ui.css'),
        ],
        'site' => [
            'jquery',
            asset('userfiles/modules/microweber/default.css'),

            asset('userfiles/modules/microweber/api/dist/site-libs.js'),
            asset('userfiles/modules/microweber/api/dist/site.js'),
        ],
        'live-edit' => [
            'jquery',
            'jquery-ui',
            //    'mw-ui',
            asset('userfiles/modules/microweber/default.css'),

            asset('userfiles/modules/microweber/api/dist/liveedit-libs.js'),
            asset('userfiles/modules/microweber/api/dist/liveedit.js'),
            asset('userfiles/modules/microweber/css/wysiwyg.css'),
            asset('userfiles/modules/microweber/css/liveedit.css'),
            //   asset('userfiles/modules/microweber/css/liveedit.rtl.css'),
        ],
    ],
];
