<?php
namespace MicroweberPackages\Import\tests;

use MicroweberPackages\Core\tests\TestCase;


/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter Import
 */

class ImportMappingReadersTest extends TestCase
{
    public function testReadXmlFile()
    {
        $zip = new \ZipArchive();
        $zip->open(__DIR__.'import_test.zip');
        $googleProductsXml = $zip->getFromName('google_products.xml');
        $zip->close();


        dd($googleProductsXml);

    }
}
