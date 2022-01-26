<?php
namespace MicroweberPackages\Import\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Import\Import;
use MicroweberPackages\Post\Models\Post;


/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter Import
 */

class ImportTest extends TestCase
{

    public function testImportZipFile() {

        $sample = userfiles_path() . '/templates/new-world/mw_default_content.zip';
        $sample = normalize_path($sample, false);

        $manager = new Import();
        $manager->setFile($sample);
        $manager->setBatchImporting(false);

        $importStatus = $manager->start();

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['precentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
	}

	public function testImportSampleCsvFile() {

	    $sample = userfiles_path() . '/modules/admin/import_tool/samples/sample.csv';
        $sample = normalize_path($sample, false);

        $manager = new Import();
        $manager->setFile($sample);
        $manager->setBatchImporting(false);

        $importStatus = $manager->start();

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['precentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

    public function testImportSampleJsonFile() {

        $sample = userfiles_path() . '/modules/admin/import_tool/samples/sample.json';
        $sample = normalize_path($sample, false);

        $manager = new Import();
        $manager->setFile($sample);
        $manager->setBatchImporting(false);

        $importStatus = $manager->start();

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['precentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

    public function testImportSampleXlsxFile() {

        $sample = userfiles_path() . '/modules/admin/import_tool/samples/sample.xlsx';
        $sample = normalize_path($sample, false);

        $manager = new Import();
        $manager->setFile($sample);
        $manager->setBatchImporting(false);

        $importStatus = $manager->start();

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['precentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

	public function testImportWrongFile() {

		$manager = new Import();
		$manager->setFile('wrongfile.txt');
		$manager->setBatchImporting(false);

		$importStatus = $manager->start();

		$this->assertArrayHasKey('error', $importStatus);
	}

}
