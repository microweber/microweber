<?php
namespace MicroweberPackages\Export\tests;

use Faker\Factory;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Backup\Backup;
use Modules\Backup\SessionStepper;
use Modules\Post\Models\Post;
use Modules\Restore\Restore;


/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter BackupV2Test
 */

class ZExportTest extends TestCase
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
