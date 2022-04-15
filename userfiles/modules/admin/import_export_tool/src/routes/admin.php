<?php

Route::name('admin.import-export-tool.')
    ->prefix(ADMIN_PREFIX . '/import-export-tool')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin')
    ->group(function () {

        Route::get('waw', function () {

            $contentXml = file_get_contents('https://templates.microweber.com/import_test/example_feed_xml_rss.xml');
            $newReader = new \MicroweberPackages\Import\ImportMapping\Readers\XmlToArray();
            $data = $newReader->getTargetTags($contentXml);

            dd($data);

        });


        Route::get('/index', 'AdminController@index')->name('index');
        Route::get('/import/{id}', 'AdminController@import')->name('import');

    });
