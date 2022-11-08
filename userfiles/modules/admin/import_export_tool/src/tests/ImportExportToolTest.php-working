<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ImportExportToolTest extends TestCase
{
    public function testDataExampleOneXml()
    {
        $zip = new \ZipArchive();
        $zip->open(__DIR__ . '/simple-data.zip');
        $content = $zip->getFromName('data-example-1.xml');
        $zip->close();

        $tempName = tempnam(storage_path(),'xml');
        file_put_contents($tempName, $content);

        $this->assertNotEmpty($content);

        $importFeed = new ImportFeed();
        $importFeed->name = rand(111, 999) . 'simple-feed';
        $importFeed->save();

        $importFeed->readContentFromFile($tempName, 'xml');

        $this->assertEquals($importFeed->source_content['document']['product'][0]['title'], 'Soflyy T-Shirt');
        $this->assertEquals($importFeed->count_of_contents, 6);
        $this->assertEquals($importFeed->content_tag, 'document.product');
        $this->assertEquals($importFeed->detected_content_tags, ['document.product' => []]);
    }



    public function testDataExampleOneCsv() {

        $zip = new \ZipArchive();
        $zip->open(__DIR__ . '/simple-data.zip');
        $content = $zip->getFromName('data-example-1.csv');
        $zip->close();

        $tempName = tempnam(storage_path(),'csv');
        file_put_contents($tempName, $content);

        $this->assertNotEmpty($content);

        $importFeed = new ImportFeed();
        $importFeed->name = rand(111, 999) . 'simple-csv-feed';
        $importFeed->save();

        $importFeed->readContentFromFile($tempName,'csv');

        $this->assertEquals($importFeed->source_content['Data'][0]['Product Title'], 'Soflyy T-Shirt');
        $this->assertEquals($importFeed->source_content['Data'][0]['SKU'], '999-X');
        $this->assertEquals($importFeed->source_content['Data'][0]['Description'], 'This shirt is awesome.');
        $this->assertEquals($importFeed->count_of_contents, 6);
        $this->assertEquals($importFeed->content_tag, 'Data');
        $this->assertEquals($importFeed->detected_content_tags, ['Data' => []]);
    }


    public function testXCsv()
    {


      /*  $feedMapToArray = new FeedMapToArray();
        $feedMapToArray->setImportFeedId($this->import_feed->id);
        $preparedData = $feedMapToArray->toArray();

        $dropdownMapping = new HtmlDropdownMappingRecursiveTable();
        $dropdownMapping->setContent(['Data'=>$content]);
        $dropdownMapping->setContentParentTags('Data');

        $html = $dropdownMapping->render();


        dd($html);*/


    }
}
