<?php
namespace MicroweberPackages\Import\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\HtmlDropdownMappingRecursiveTable;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\ItemMapReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\Readers\XmlToArray;


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
        $data = $newReader->readXml($googleProductsXml);

        $dropdownMapping = new HtmlDropdownMappingRecursiveTable();
        $dropdownMapping->setContent($data);
        $dropdownMapping->setContentParentTags('rss.channel.item');
        $dropdownMapping->render();
        $automated = $dropdownMapping->getAutomaticSelectedOptions();

        $this->assertEquals($automated['rss;channel;item;g:id'], 'content_data.external_id');
        $this->assertEquals($automated['rss;channel;item;title'], 'title');
        $this->assertEquals($automated['rss;channel;item;description'], 'content_body');
        $this->assertEquals($automated['rss;channel;item;g:image_link'], 'pictures');
        $this->assertEquals($automated['rss;channel;item;g:price'], 'price');
        $this->assertEquals($automated['rss;channel;item;g:shipping;g:price'], 'content_data.shipping_fixed_cost');

    }
}
