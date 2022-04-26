<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\tests;

use MicroweberPackages\Core\tests\TestCase;

class ImportExportToolTest extends TestCase
{
    public function testBasic()
    {

        $zip = new \ZipArchive();
        $zip->open(__DIR__.'/simple-data.zip');
        $content = $zip->getFromName('data-example-1.csv');
        //$zip->close();

        dump($zip);

    }
}
