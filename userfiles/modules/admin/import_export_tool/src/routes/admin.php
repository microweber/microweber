<?php

Route::name('admin.import-export-tool.')
    ->prefix(ADMIN_PREFIX . '/import-export-tool')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin')
    ->group(function () {


       /* Route::get('waw', function () {

          //  $contentXml = file_get_contents('https://templates.microweber.com/import_test/example_feed_xml_rss.xml');
            //$contentXml = file_get_contents('https://raw.githubusercontent.com/bobimicroweber/laravel-dusk-screenshot-chrome-ext/main/example.xml');
           // $contentXml = file_get_contents('https://templates.microweber.com/import_test/example_feed_xml_rss2.xml');
           // $contentXml = file_get_contents('https://templates.microweber.com/import_test/xml_feed_2.xml');
            $contentXml = app()->http->url('https://detourcoffee.com/collections/tea.atom')->get();
            $newReader = new \MicroweberPackages\Import\ImportMapping\Readers\XmlToArray();
            $array = $newReader->readXml($contentXml);
            $data = $newReader->getArrayIterratableTargetKeys($array);
            $dot = Arr::dot($data);

            dd($data, $dot);

        });*/



        Route::get('/index', 'AdminController@index')->name('index');
        Route::get('/import/{id}', 'AdminController@import')->name('import');
        Route::get('/import-start/{id}', 'AdminController@importStart')->name('import-start');

    });
