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
	private static $_exportedFile = '';
	
	public function testImport() {
		
		$tempFile = 'backup_v2_test.json';
		$json = '{
			"categories_items": [
		        {
		            "id": 1,
		            "parent_id": 5,
		            "rel_type": "content",
		            "rel_id": 8
		        },
		        {
		            "id": 2,
		            "parent_id": 5,
		            "rel_type": "content",
		            "rel_id": 9
		        }
			],
			"content": [
		        {
		            "id": 1,
		            "content_type": "page",
		            "subtype": "dynamic",
		            "url": "shop",
		            "title": "Shop",
		            "parent": 0,
		            "description": null,
		            "position": null,
		            "content": null,
		            "content_body": null,
		            "is_active": 1,
		            "subtype_value": null,
		            "custom_type": null,
		            "custom_type_value": null,
		            "active_site_template": "default",
		            "layout_file": "layouts\/shop.php",
		            "layout_name": null,
		            "layout_style": null,
		            "content_filename": null,
		            "original_link": null,
		            "is_home": 0,
		            "is_pinged": 0,
		            "is_shop": 1,
		            "is_deleted": 0,
		            "require_login": 0,
		            "status": null,
		            "content_meta_title": null,
		            "content_meta_keywords": null,
		            "session_id": "YzMeJdnpyiLc4t9ztX6xNRJvwIWZF3lMJ484ons8",
		            "updated_at": "2019-06-20 10:08:52",
		            "created_at": "2019-06-20 10:08:52",
		            "expires_at": null,
		            "created_by": 1,
		            "edited_by": 1,
		            "posted_at": null,
		            "draft_of": null,
		            "copy_of": null
		        }
			]
		}';
		
		file_put_contents(storage_path($tempFile), $json);
		
		$manager = new BackupManager();
		$manager->setImportFile(storage_path($tempFile));
		$manager->setImportType('json');
		$manager->setImportBatch(false);
		
		$importStatus = $manager->startImport();
		
		$this->assertArrayHasKey('done', $importStatus);
	}
	
	public function testFullExport() {
		
		$manager = new BackupManager();
		$manager->setExportAllData(true);
		
		$i = 0;
		while (true) {
			$exportStatus =	$manager->startExport();
			
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
				
				self::$_exportedFile = $exportStatus['data']['filepath'];
				
				break;
			}
			
			if ($i > 10) {
				break;
			}
			
			$i++;
		}
	}
	
	public function testImportZipFile() {
		
		$manager = new BackupManager();
		$manager->setImportFile(self::$_exportedFile);
		$manager->setImportBatch(false);
		
		$importStatus = $manager->startImport();
		
		$this->assertArrayHasKey('done', $importStatus);
		$this->assertArrayHasKey('precentage', $importStatus);
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