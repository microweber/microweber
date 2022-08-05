<?php
namespace MicroweberPackages\Import\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\Import;
use MicroweberPackages\Post\Models\Post;


/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter Import
 */

class ImportTest extends TestCase
{

    public function testImportSampleCsvFile() {

        $sample = userfiles_path() . '/modules/admin/import_tool/samples/sample.csv';
        $sample = userfiles_path() . '/modules/admin/import_export_tool/samples/sample.csv';
        $sample = normalize_path($sample, false);

        $sessionId = SessionStepper::generateSessionId(1);

        $manager = new Import();
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

        $manager = new Import();
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

        $manager = new Import();
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

        $manager = new Import();
        $manager->setSessionId($sessionId);
        $manager->setFile('wrongfile.txt');
        $manager->setBatchImporting(false);

        $importStatus = $manager->start();

        $this->assertArrayHasKey('error', $importStatus);
    }

    public function testImportZipFile() {

        $sample = userfiles_path() . '/templates/new-world/mw_default_content.zip';
        $sample = normalize_path($sample, false);

        $sessionId = SessionStepper::generateSessionId(1);

        $manager = new Import();
        $manager->setSessionId($sessionId);
        $manager->setFile($sample);
        $manager->setBatchImporting(false);

        $importStatus = $manager->start();


        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['percentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

}

