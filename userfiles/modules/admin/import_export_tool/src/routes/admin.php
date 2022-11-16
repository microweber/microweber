<?php

use MicroweberPackages\Import\Formats\CsvReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportFeedToDatabase;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\FeedMapToArray;

Route::name('admin.import-export-tool.')
    ->prefix(ADMIN_PREFIX . '/import-export-tool')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin')
    ->group(function () {


     /*   Route::get('ddd', function () {

            for ($i = 1; $i <= 100; $i++) {
                $rand = rand(111, 999);
                $productTitleEn = 'Product title EN' . $rand;
                $productTitleAr = 'Product title AR' . $rand;
                $saved = \MicroweberPackages\Product\Models\Product::create([
                    'title' => $productTitleEn,
                    'description' => $productTitleEn,
                    'content_body' => $productTitleEn,
                    'content_meta_title' => $productTitleEn,
                    'content_meta_keywords' => $productTitleEn,
                    'price' => rand(111, 999),
                    'stock' => rand(0, 1),
                    'multilanguage' => [
                        'title' => [
                            'en_US' => $productTitleEn,
                            'ar_SA' => $productTitleAr
                        ],
                        'description' => [
                            'en_US' => $productTitleEn,
                            'ar_SA' => $productTitleAr
                        ],
                        'content_body' => [
                            'en_US' => $productTitleEn,
                            'ar_SA' => $productTitleAr
                        ],
                        'content_meta_title' => [
                            'en_US' => $productTitleEn,
                            'ar_SA' => $productTitleAr
                        ],
                        'content_meta_keywords' => [
                            'en_US' => $productTitleEn,
                            'ar_SA' => $productTitleAr
                        ]
                    ]
                ]);
                //dump($saved->toArray());
                echo 'Product saved:' . $i . '<br /><meta http-equiv="refresh" content="0;">';
            }
        });*/


    /*    Route::get('waw', function () {

            //  $contentXml = file_get_contents('https://templates.microweber.com/import_test/example_feed_xml_rss.xml');
            //$contentXml = file_get_contents('https://raw.githubusercontent.com/bobimicroweber/laravel-dusk-screenshot-chrome-ext/main/example.xml');
            // $contentXml = file_get_contents('https://templates.microweber.com/import_test/example_feed_xml_rss2.xml');
            // $contentXml = file_get_contents('https://templates.microweber.com/import_test/xml_feed_2.xml');
            //  $contentXml = app()->http->url('https://detourcoffee.com/collections/tea.atom')->get();
            //   $contentXml = app()->http->url('https://techcrunch.com/startups/feed/')->get();
            //$contentXml = app()->http->url('https://raw.githubusercontent.com/bobimicroweber/laravel-dusk-screenshot-chrome-ext/main/small_fasardi.php.xml')->get();
            // $newReader = new \MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray();
            //  $data = $newReader->readXml($contentXml);


            $filename = 'https://raw.githubusercontent.com/bobimicroweber/all-imports/main/data-example-2.csv';
            $reader = new CsvReader($filename);
            $data = $reader->readData();

            $dropdownMapping = new \MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\HtmlDropdownMappingRecursiveTable();
            $dropdownMapping->setContent([
                'Data' => $data
            ]);
            $dropdownMapping->setContentParentTags('Data');

            $html = $dropdownMapping->render();

            echo $html;
        });*/

        Route::get('/import-wizard', 'ImportWizardController@index')->name('import-wizard');

        Route::get('/export-wizard', 'ExportWizardController@index')->name('export-wizard');
        Route::get('/export-wizard/file/{id}', 'ExportWizardController@file')->name('export-wizard-file');
        Route::get('/delete-wizard/file/{id}', 'ExportWizardController@deleteFile')->name('delete-wizard-file');

        Route::get('/', 'AdminController@index')->name('index');
        Route::get('/index', 'AdminController@index')->name('index');
        Route::get('/import/{id}', 'AdminController@import')->name('import');
        Route::get('/import-start/{id}', 'AdminController@importStart')->name('import-start');
        Route::get('/import-delete/{id}', 'AdminController@importDelete')->name('import-delete');


        Route::get('/index-exports', 'AdminController@exports')->name('index-exports');




/*        Route::get('/fffff', function () {

            $feedMapToArray = new FeedMapToArray();
            $feedMapToArray->setImportFeedId(3);
            $preparedData = $feedMapToArray->toArray();

            dd($preparedData);

            $import = new ImportFeedToDatabase();
            $import->setImportFeedId(1);
            $import->setBatchStep(1);
            $import->setBatchImporting(true);

            $importStatus = $import->start();

            dd($importStatus);

        });*/

    });
