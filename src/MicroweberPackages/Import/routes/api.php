<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/12/2020
 * Time: 2:36 PM
 */


use MicroweberPackages\Import\ImportMapping\Readers\XmlToArray;

Route::name('admin.import.')
    ->prefix(ADMIN_PREFIX.'/import')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Backup\Http\Controllers\Admin')
    ->group(function () {

        Route::get('import', 'BackupController@import')->name('import');
        Route::get('export', 'BackupController@export')->name('export');
        Route::get('download', 'BackupController@download')->name('download');

        Route::post('upload', 'BackupController@upload')->name('upload');
        Route::post('delete', 'BackupController@delete')->name('delete');

        Route::post('/language/export', 'LanguageController@export')->name('language.export');
        Route::post('/language/upload', 'LanguageController@upload')->name('language.upload');

        Route::get('viz', function () {

            $request = request();
            $contentParentTags = $request->get('content_parent_tags');

           //$googleProductsXml = file_get_contents('https://templates.microweber.com/import_test/example_feed_xml_rss.xml');
          //$googleProductsXml = file_get_contents('https://raw.githubusercontent.com/bobimicroweber/laravel-dusk-screenshot-chrome-ext/main/example.xml');
          $googleProductsXml = file_get_contents('https://templates.microweber.com/import_test/wp.xml');
        //  $googleProductsXml = file_get_contents('file:///Users/bobi/Downloads/all.atom');


            $newReader = new XmlToArray();
            $data = $newReader->readXml($googleProductsXml);

            $dropdownMapping = new \MicroweberPackages\Import\ImportMapping\HtmlDropdownMappingPreview();
            $dropdownMapping->setContent($data);
            $dropdownMapping->setContentParentTags($contentParentTags);

            $data =  $dropdownMapping->render();

            return view('import::mapping_table_xml', [
                'map'=>$data,
                'content_parent_tag'=>$contentParentTags,
                'structure'=>[]
            ]);

            return;



        });

    });
