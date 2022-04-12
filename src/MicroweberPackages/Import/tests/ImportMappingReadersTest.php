<?php
namespace MicroweberPackages\Import\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Import\ImportMapping\Readers\ItemMapReader;
use MicroweberPackages\Import\ImportMapping\Readers\XmlToArray;


/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter Import
 */

class ImportMappingReadersTest extends TestCase
{
    public function testReadXmlGoogleFeedFile()
    {
        $zip = new \ZipArchive();
        $zip->open(__DIR__.DS.'import_test.zip');
        $googleProductsXml = $zip->getFromName('google_products.xml');
        $zip->close();

        $newReader = new XmlToArray();
        $data = $newReader->readContent($googleProductsXml);

        $this->assertEquals($data['map_fields']['g:id']['item_key'], 'g:id');
        $this->assertEquals($data['map_fields']['g:id']['internal_key'], 'external_id');
        $this->assertEquals($data['map_fields']['g:id']['item_type'], ItemMapReader::ITEM_TYPE_STRING);

        $this->assertEquals($data['map_fields']['g:image_link']['item_key'], 'g:image_link');
        $this->assertEquals($data['map_fields']['g:image_link']['internal_key'], 'image');
        $this->assertEquals($data['map_fields']['g:image_link']['item_type'], ItemMapReader::ITEM_TYPE_STRING);

        $this->assertEquals($data['map_fields']['description']['item_key'], 'description');
        $this->assertEquals($data['map_fields']['description']['internal_key'], 'content_body');
        $this->assertEquals($data['map_fields']['description']['item_type'], ItemMapReader::ITEM_TYPE_STRING);

        $this->assertEquals($data['map_fields']['g:google_product_category']['item_key'], 'g:google_product_category');
        $this->assertEquals($data['map_fields']['g:google_product_category']['internal_key'], 'category');
        $this->assertEquals($data['map_fields']['g:google_product_category']['item_type'], ItemMapReader::ITEM_TYPE_ARRAY);

        $this->assertNotEmpty($data['data']);
        $this->assertNotEmpty($data['map_fields']);
    }
}
