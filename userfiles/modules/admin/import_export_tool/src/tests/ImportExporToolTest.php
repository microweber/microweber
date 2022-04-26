<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTooltests;

use MicroweberPackages\Core\tests\TestCase;

class ImportExporToolTest extends TestCase
{
    public function testBasic()
    {

        echo __DIR__;
        return;

        $zip = new \ZipArchive();
        $zip->open(__DIR__.'/../../Helper/tests/misc/xss-test-files.zip');
        $xssList = $zip->getFromName('xss-payload-list.txt');
        $zip->close();

    }
}
