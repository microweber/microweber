<?php
namespace Modules\Restore\tests;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Backup\SessionStepper;
use Modules\Restore\Restore;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;


/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter RestoreTest
 */
#[RunTestsInSeparateProcesses]
class RestoreTest extends TestCase
{

    public function testImportSampleCsvFile() {

        $sample = userfiles_path() . '/modules/admin/import_export_tool/samples/sample.csv';
        $sample = normalize_path($sample, false);

        $sessionId = SessionStepper::generateSessionId(1);

        $manager = new Restore();
        $manager->setSessionId($sessionId);
        $manager->setFile($sample);
        $manager->setType('csv');
        $manager->setBatchImporting(false);

        $importStatus = $manager->start();

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['percentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

    public function testImportSampleJsonFile() {

        $sample = userfiles_path() . '/modules/admin/import_tool/samples/sample.json';
        $sample = userfiles_path() . '/modules/admin/import_export_tool/samples/sample.json';
        $sample = normalize_path($sample, false);

        $sessionId = SessionStepper::generateSessionId(1);

        $manager = new Restore();
        $manager->setSessionId($sessionId);
        $manager->setFile($sample);

        $manager->setBatchImporting(false);

        $importStatus = $manager->start();

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['percentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

    public function testImportSampleXlsxFile() {

        $sample = userfiles_path() . '/modules/admin/import_tool/samples/sample.xlsx';
        $sample = userfiles_path() . '/modules/admin/import_export_tool/samples/sample.xlsx';
        $sample = normalize_path($sample, false);

        $sessionId = SessionStepper::generateSessionId(1);

        $manager = new Restore();
        $manager->setSessionId($sessionId);
        $manager->setFile($sample);
        $manager->setBatchImporting(false);

        $importStatus = $manager->start();

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['percentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

    public function testImportWrongFile() {

        $sessionId = SessionStepper::generateSessionId(1);

        $manager = new Restore();
        $manager->setSessionId($sessionId);
        $manager->setFile('wrongfile.txt');
        $manager->setBatchImporting(false);

        $importStatus = $manager->start();

        $this->assertArrayHasKey('error', $importStatus);
    }

    public function testImportZipFile() {


        $template_folder = 'big';
        if(!is_dir(templates_dir(). $template_folder)){
            $template_folder = 'new-world';
        }

        $sample = userfiles_path() . '/templates/'.$template_folder.'/mw_default_content.zip';
        $sample = normalize_path($sample, false);


        if(!is_file($sample)){
            $this->markTestSkipped('File not found for template test: ' . $sample);
        }


        $sessionId = SessionStepper::generateSessionId(1);

        $manager = new Restore();
        $manager->setSessionId($sessionId);
        $manager->setFile($sample);
        $manager->setBatchImporting(false);

        $importStatus = $manager->start();

        $data = $importStatus['data'];
        $optionsCheck = [] ;
        foreach ($data as $itemObject){
            $this->assertNotNull($itemObject);

            $this->assertNotNull($itemObject['itemIdDatabase']);
            $this->assertNotNull($itemObject['item']);
            $item = $itemObject['item'];
            $this->assertNotNull($item['save_to_table']);

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
    }

}

