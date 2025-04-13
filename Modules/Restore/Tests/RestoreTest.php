<?php
namespace Modules\Restore\Tests;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Backup\SessionStepper;
use Modules\Restore\Restore;
use Modules\Restore\Formats\ZipReader;
use MicroweberPackages\Utils\Zip\ZipArchiveExtractor;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;


/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter RestoreTest
 */

class RestoreTest extends TestCase
{

    public function testImportSampleCsvFile() {

        $sample = __DIR__.'/../resources/samples/sample.csv';
        $sample = normalize_path($sample, false);

        $sessionId = SessionStepper::generateSessionId(1);

        $manager = new Restore();
        $manager->setSessionId($sessionId);
        $manager->setFile($sample);
        $manager->setType('csv');
        $manager->setBatchRestoring(false);

        $importStatus = $manager->start();

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['percentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

    public function testImportSampleJsonFile() {

        $sample = __DIR__.'/../resources/samples/sample.json';
        $sample = normalize_path($sample, false);

        $sessionId = SessionStepper::generateSessionId(1);

        $manager = new Restore();
        $manager->setSessionId($sessionId);
        $manager->setFile($sample);

        $manager->setBatchRestoring(false);

        $importStatus = $manager->start();

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['percentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

    public function testImportSampleXlsxFile() {

        $sample = __DIR__.'/../resources/samples/sample.xlsx';
        $sample = normalize_path($sample, false);

        $sessionId = SessionStepper::generateSessionId(1);

        $manager = new Restore();
        $manager->setSessionId($sessionId);
        $manager->setFile($sample);
        $manager->setBatchRestoring(false);

        $importStatus = $manager->start();

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['percentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

    public function testImportWrongFile() {

        $sessionId = SessionStepper::generateSessionId(1);

        $manager = new Restore();
        $manager->setSessionId($sessionId);

        try {
            $manager->setFile('wrongfile.txt');
            $manager->setBatchRestoring(false);
            $importStatus = $manager->start();
            $this->fail("Expected exception not thrown");
        } catch (\Exception $e) {
            $this->assertStringContainsString('Invalid file', $e->getMessage());
        }
    }
/*
    public function testImportZipFile() {


        $template_folder = 'Bootstrap';


        $sample = __DIR__.'/../resources/samples/other_cms.zip';
        $sample = normalize_path($sample, false);


        if(!is_file($sample)){
            $this->markTestSkipped('File not found for template test: ' . $sample);
        }


        $sessionId = SessionStepper::generateSessionId(1);

        $manager = new Restore();
        $manager->setSessionId($sessionId);
        $manager->setFile($sample);
        $manager->setBatchRestoring(false);

        $importStatus = $manager->start();
        $data = $importStatus['data'];

        // First check if zip contains valid content
        $zipReader = new ZipReader($sample);
        $extractor = new ZipArchiveExtractor($sample);
        try {
            $extractor->extractTo(backup_location() . 'temp_zip_check/');
            $fileList = scandir(backup_location() . 'temp_zip_check/');
            if (count($fileList) <= 2) { // ['.', '..']
                $this->markTestSkipped('Zip file contains no importable content');
                return;
            }
        } catch (\Exception $e) {
            $this->markTestSkipped('Invalid zip file: ' . $e->getMessage());
            return;
        }



        $optionsCheck = [];
        foreach ($data as $itemObject){
            $this->assertNotNull($itemObject);
            $this->assertIsArray($itemObject);

            $this->assertArrayHasKey('itemIdDatabase', $itemObject);
            $this->assertArrayHasKey('item', $itemObject);

            $item = $itemObject['item'];
            $this->assertArrayHasKey('save_to_table', $item);

            if ($item['save_to_table'] == 'options') {
                $optionsCheck[] = $item;
            }

        }

        $this->assertNotEmpty($optionsCheck);
        foreach ($optionsCheck as $option) {
            $this->assertNotNull($option['option_key']);
            $this->assertNotNull($option['option_value']);
            $this->assertNotNull($option['option_group']);
            $key = $option['option_key'];
            if($key == 'app_version'){
                continue;
            }
            $expectedValue =  app()->url_manager->replace_site_url_back($option['option_value']);
            $getValueFromDb = get_option($option['option_key'], $option['option_group']);
            $this->assertEquals($expectedValue, $getValueFromDb, 'Option key: ' . $option['option_key'] . ' Option group: ' . $option['option_group']);
        }

        $ensureTemplateIsSet = get_option('current_template', 'template');
        $this->assertEquals($template_folder, $ensureTemplateIsSet);

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['percentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }*/

}

