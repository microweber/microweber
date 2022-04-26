<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Import\Import;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\FeedMapToArray;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\HtmlDropdownMappingRecursiveTable;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;

class ImportExportToolTest extends TestCase
{
    public function testBasic()
    {

        $zip = new \ZipArchive();
        $zip->open(__DIR__.'/simple-data.zip');
        $content = $zip->getFromName('data-example-1.csv');
        $zip->close();

        $this->assertNotEmpty($content);

        $importFeed = new ImportFeed();
        $importFeed->name = rand(111,999) . 'simple-feed';
        $importFeed->save();


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
