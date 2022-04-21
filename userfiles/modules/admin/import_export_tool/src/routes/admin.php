<?php

Route::name('admin.import-export-tool.')
    ->prefix(ADMIN_PREFIX . '/import-export-tool')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin')
    ->group(function () {


       Route::get('waw', function () {

          //  $contentXml = file_get_contents('https://templates.microweber.com/import_test/example_feed_xml_rss.xml');
            //$contentXml = file_get_contents('https://raw.githubusercontent.com/bobimicroweber/laravel-dusk-screenshot-chrome-ext/main/example.xml');
           // $contentXml = file_get_contents('https://templates.microweber.com/import_test/example_feed_xml_rss2.xml');
           // $contentXml = file_get_contents('https://templates.microweber.com/import_test/xml_feed_2.xml');
          //  $contentXml = app()->http->url('https://detourcoffee.com/collections/tea.atom')->get();
         //   $contentXml = app()->http->url('https://techcrunch.com/startups/feed/')->get();
            $contentXml = app()->http->url('https://raw.githubusercontent.com/bobimicroweber/laravel-dusk-screenshot-chrome-ext/main/small_fasardi.php.xml')->get();
            $newReader = new \MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray();
           $data = $newReader->readXml($contentXml);

           $dropdownMapping = new \MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\HtmlDropdownMappingRecursiveTable();
           $dropdownMapping->setContent($data);
           $dropdownMapping->setContentParentTags('SHOP.SHOPITEM');

            $html = $dropdownMapping->render();

            echo $html;
        });



        Route::get('/index', 'AdminController@index')->name('index');
        Route::get('/import/{id}', 'AdminController@import')->name('import');
        Route::get('/import-start/{id}', 'AdminController@importStart')->name('import-start');

    });
