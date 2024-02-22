<?php
namespace MicroweberPackages\Export\tests;

use Faker\Factory;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Backup\BackupManager;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\Import;
use MicroweberPackages\Post\Models\Post;


/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter BackupV2Test
 */

class ExportTest extends TestCase
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
        $sessionId = SessionStepper::generateSessionId(20);

        $manager = new \MicroweberPackages\Export\Export();
        $manager->setSessionId($sessionId);
		$manager->setExportAllData(true);

		$i = 0;
		while (true) {

			$export = $manager->start();

			$exportBool = false;
			if (!empty($export)) {
				$exportBool = true;
			}

			$this->assertTrue($exportBool);

			if (isset($export['current_step'])) {
				$this->assertArrayHasKey('current_step', $export);
				$this->assertArrayHasKey('total_steps', $export);
				$this->assertArrayHasKey('percentage', $export);
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

        $zip = new \ZipArchive;
        $res = $zip->open(self::$_exportedFile);
        $this->assertTrue($res === TRUE);

        $jsonFileInsideZip = str_replace('.zip', '.json', self::$_exportedFile);
        $jsonFileInsideZip = basename($jsonFileInsideZip);
        $jsonFileContent = $zip->getFromName($jsonFileInsideZip);

        $jsonExportTest = json_decode($jsonFileContent,true);

        $this->assertTrue(!empty($jsonExportTest['content']));
        $this->assertEquals(count($jsonExportTest['content']),$contentCount);

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

        $sessionId = SessionStepper::generateSessionId(0);
		$manager = new Import();
        $manager->setSessionId($sessionId);
		$manager->setFile(self::$_exportedFile);
		$manager->setBatchImporting(false);

		$import = $manager->start();

		$importBool = false;
		if (!empty($import)) {
			$importBool = true;
		}

		$this->assertTrue($importBool);
 		$this->assertArrayHasKey('done', $import);
		$this->assertArrayHasKey('percentage', $import);
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


}
