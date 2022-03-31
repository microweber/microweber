<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/12/2020
 * Time: 2:36 PM
 */


use MicroweberPackages\Import\ImportMapping\Readers\XmlReader;

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
            $contentParentTag = explode('.', $request->get('content_parent_tag'));

            $googleProductsXml = file_get_contents('https://templates.microweber.com/import_test/example_feed_xml_rss.xml');


            $xmlParser = xml_parser_create('UTF-8'); // UTF-8 or ISO-8859-1
            xml_parser_set_option($xmlParser, XML_OPTION_CASE_FOLDING, 0);
            xml_parser_set_option($xmlParser, XML_OPTION_SKIP_WHITE, 1);
            xml_parse_into_struct($xmlParser, $googleProductsXml, $aryXML);
            xml_parser_free($xmlParser);


            return view('import::mapping_table_xml', [
                'content_parent_tag'=>$contentParentTag,
                'content_parent_tag_level'=>count($contentParentTag),
                'max_level_tag_render'=>(count($contentParentTag) + 1),
                'structure'=>$aryXML
            ]);

            return;

            $newReader = new XmlReader();
            $data = $newReader->readContent($googleProductsXml);

            dd($data);

        });

    });
