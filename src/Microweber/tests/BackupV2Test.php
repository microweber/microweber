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

	public function testImportWrongFile() {
		
		$manager = new BackupManager();
		$manager->setImportFile('wrongfile.txt');
		$manager->setImportBatch(false);
		
		$importStatus = $manager->startImport();
		
		var_dump($importStatus);
		
		$this->assertArrayHasKey('error', $importStatus);  
	}
	
	public function testExportWithWrongFormat()
	{
		$export = new BackupManager();
		$export->setExportType('xmla');
		$exportStatus = $export->startExport();
		
		var_dump($exportStatus);
		
		$this->assertArrayHasKey('error', $exportStatus);
	}
	
	public function testExport() {
		
		$manager = new BackupManager();
		$manager->setExportType('zip');
		$manager->setExportData('tables', array('media'));
		$manager->setExportData('contentIds', array(1,2,3,4,5));
		
		$i = 0;
		while (true) {
			
			$exportStatus = $manager->startExport();
			
			$this->assertArrayHasKey('current_step', $exportStatus);
			$this->assertArrayHasKey('total_steps', $exportStatus);
			
			// The last exort step
			if ($exportStatus['current_step'] == $exportStatus['total_steps']) {
				$this->assertArrayHasKey('download', $exportStatus);
				$this->assertArrayHasKey('success', $exportStatus);
			}
			
			var_dump($exportStatus);
			
			if ($i >= $exportStatus['total_steps']) {
				break;
			}
			
			$i++;
		}
		
		
	}
}