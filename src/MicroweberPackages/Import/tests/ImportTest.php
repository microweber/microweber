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
	/*public function testImportZipFile() {

		foreach(get_content('no_limit=1&content_type=post') as $content) {
			$this->assertArrayHasKey(0, delete_content(array('id'=>$content['id'], 'forever'=>true)));
		}

		$manager = new BackupManager();
		$manager->setImportFile(self::$_exportedFile);
		$manager->setImportBatch(false);

		$import = $manager->startImport();

		$importBool = false;
		if (!empty($import)) {
			$importBool = true;
		}

		$this->assertTrue($importBool);
 		$this->assertArrayHasKey('done', $import);
		$this->assertArrayHasKey('precentage', $import);
	}
    */

	public function testImportSampleCsvFile() {

	    $sample = userfiles_path() . '/modules/admin/import_tool/samples/sample.csv';
        $sample = normalize_path($sample, false);

        $manager = new Import();
        $manager->setFile($sample);
        $manager->setImportBatch(false);

        $importStatus = $manager->start();

        dd($importStatus);

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['precentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

    public function testImportSampleJsonFile() {

        $sample = userfiles_path() . '/modules/admin/import_tool/samples/sample.json';
        $sample = normalize_path($sample, false);

        $manager = new BackupManager();
        $manager->setImportFile($sample);
        $manager->setImportBatch(false);

        $importStatus = $manager->startImport();

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['precentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

    public function testImportSampleXlsxFile() {

        $sample = userfiles_path() . '/modules/admin/import_tool/samples/sample.xlsx';
        $sample = normalize_path($sample, false);

        $manager = new BackupManager();
        $manager->setImportFile($sample);
        $manager->setImportBatch(false);

        $importStatus = $manager->startImport();


        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['precentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

	public function testImportWrongFile() {

		$manager = new BackupManager();
		$manager->setImportFile('wrongfile.txt');
		$manager->setImportBatch(false);

		$importStatus = $manager->startImport();

		$this->assertArrayHasKey('error', $importStatus);
	}

}
