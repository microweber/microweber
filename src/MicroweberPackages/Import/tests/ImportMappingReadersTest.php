<?php
namespace MicroweberPackages\Import\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Import\ImportMapping\Readers\XmlReader;


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
        $zip->open(__DIR__.DS.'import_test.zip');
        $googleProductsXml = $zip->getFromName('google_products.xml');
        $zip->close();

        $newReader = new XmlReader();
        $data = $newReader->readContent($googleProductsXml);

        dd($data);

    }
}
