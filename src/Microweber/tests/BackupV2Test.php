<?php
namespace Microweber\tests;

use Microweber\Utils\Backup\BackupManager;

/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter BackupV2Test
 */

class BackupV2Test extends TestCase
{
	public function testExport() {
		
		$manager = new BackupManager();
		$manager->setExportType('zip');
		$manager->setExportData('tables', array('media'));
		$manager->setExportData('contentIds', array(1,2,3,4,5));
		
		$i = 0;
		while (true) {
			
			$exportStatus = $manager->startExport();
			 
			if (isset($exportStatus['current_step'])) {
				$this->assertArrayHasKey('current_step', $exportStatus);
				$this->assertArrayHasKey('total_steps', $exportStatus);
				$this->assertArrayHasKey('precentage', $exportStatus);
				$this->assertArrayHasKey('data', $exportStatus);
			}
			
			// The last exort step
			if (isset($exportStatus['success'])) {
				$this->assertArrayHasKey('data', $exportStatus);
				$this->assertArrayHasKey('download', $exportStatus['data']);
				$this->assertArrayHasKey('filepath', $exportStatus['data']);
				$this->assertArrayHasKey('filename', $exportStatus['data']);
				break;
			}
			
			if ($i > 10) { 
				break;
			}
			
			$i++;
		}
		
		
	}
	
	public function testImport() {


		$manager = new BackupManager();
		$manager->setImportFile(storage_path('backup_v2_test.json'));
		$manager->setImportType('json');
		$manager->setImportBatch(false);
		
		$importStatus = $manager->startImport();
		
		// var_dump($importStatus);
		
		$this->assertArrayHasKey('success', $importStatus);
	}
	
	public function testImportWrongFile() {
		
		$manager = new BackupManager();
		$manager->setImportFile('wrongfile.txt');
		$manager->setImportBatch(false);
		
		$importStatus = $manager->startImport();
		
		//var_dump($importStatus);
		
		$this->assertArrayHasKey('error', $importStatus);
	}
	
	public function testExportWithWrongFormat()
	{
		$export = new BackupManager();
		$export->setExportType('xmla');
		$exportStatus = $export->startExport();
		
		//var_dump($exportStatus);
		
		$this->assertArrayHasKey('error', $exportStatus);
	}
	
}