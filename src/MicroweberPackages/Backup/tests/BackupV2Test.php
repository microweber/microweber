<?php
namespace MicroweberPackages\Backup\tests;

use Faker\Factory;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Backup\BackupManager;
use MicroweberPackages\Post\Models\Post;


/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter BackupV2Test
 */

class BackupV2Test extends TestCase
{
	private static $_titles = array();
	private static $_exportedFile = '';

	public function testEncoding() {

		$locales = array('el_GR', 'bg_BG', 'en_EN','at_AT','ko_KR','kk_KZ','ja_JP','fi_FI','es_ES');

		foreach($locales as $locale) {

			$faker = Factory::create($locale);

			$inputTitle = $faker->name;

			if (empty($inputTitle)) {
				$this->assertTrue(false);
			} else {
				$this->assertTrue(true);

				$contentId=  save_content(array("title"=>$inputTitle));
				$outputContent = get_content("single=true&id=" . $contentId);

				$this->assertSame($outputContent['title'], $inputTitle);

				self::$_titles[] = array("id"=>$contentId, "title"=>$inputTitle, "url"=>$outputContent['full_url']);
			}
		}


	}

	public function testFullExport() {

		clearcache();

		$manager = new BackupManager();
		$manager->setExportAllData(true);

		$i = 0;
		while (true) {

			$export = $manager->startExport();

			$exportBool = false;
			if (!empty($export)) {
				$exportBool = true;
			}

			$this->assertTrue($exportBool);

			if (isset($export['current_step'])) {
				$this->assertArrayHasKey('current_step', $export);
				$this->assertArrayHasKey('total_steps', $export);
				$this->assertArrayHasKey('precentage', $export);
				$this->assertArrayHasKey('data', $export);
			}

			// The last exort step
			if (isset($export['success'])) {
				$this->assertArrayHasKey('data', $export);
				$this->assertArrayHasKey('download', $export['data']);
				$this->assertArrayHasKey('filepath', $export['data']);
				$this->assertArrayHasKey('filename', $export['data']);

				self::$_exportedFile = $export['data']['filepath'];

				break;
			}

			if ($i > 100) {
				break;
			}

			$i++;
		}


        $contentCount = get_content('count=1');
        $json_expor_test = json_decode(file_get_contents(self::$_exportedFile),true);
        $this->assertTrue(!empty($json_expor_test['content']));
        $this->assertEquals(count($json_expor_test['content']),$contentCount);

	}

	public function testImportZipFile() {

		foreach(get_content('no_limit=1&content_type=post') as $content) {
			//echo 'Delete content..' . PHP_EOL;
			$this->assertArrayHasKey(0, delete_content(array('id'=>$content['id'], 'forever'=>true)));
		}

		if (empty(self::$_exportedFile)) {
			$this->assertTrue(false);
			return;
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

	public function testImportedEncoding() {

		$urls = array();
		foreach (self::$_titles as $title) {
			$urls[$title['url']] = $title;
		}

		$posts = Post::all();

		if (empty($posts)) {
			$this->assertTrue(false);
			return;
		}

        $this->assertFalse(empty($posts));

		foreach($posts->toArray() as $post) {
			if (array_key_exists($post['url'], $urls)) {
				$this->assertSame($urls[$post['url']]['title'], $post['title']);
			}
		}
	}

	public function testImportSampleCsvFile() {

	    $sample = userfiles_path() . '/modules/admin/backup_v2/samples/sample.csv';
        $sample = normalize_path($sample, false);

        $manager = new BackupManager();
        $manager->setImportFile($sample);
        $manager->setImportBatch(false);

        $importStatus = $manager->startImport();

        $this->assertSame(true, $importStatus['done']);
        $this->assertSame(100, $importStatus['precentage']);
        $this->assertSame($importStatus['current_step'], $importStatus['total_steps']);
    }

    public function testImportSampleJsonFile() {

        $sample = userfiles_path() . '/modules/admin/backup_v2/samples/sample.json';
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

        $sample = userfiles_path() . '/modules/admin/backup_v2/samples/sample.xlsx';
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

	public function testExportWithWrongFormat()
	{
		$export = new BackupManager();
		$export->setExportType('xml_');
		$exportStatus = $export->startExport();

		$this->assertArrayHasKey('error', $exportStatus);
	}

}
