<?php
namespace MicroweberPackages\Import;

use function get_content;
use function save_content;

/**
 * Microweber - Backup Module Database Save
 *
 * @namespace MicroweberPackages\Backup
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class DatabaseSaveContent
{

	public static function save($table, $tableData)
	{
		$tableData['skip_cache'] = true;
		$tableData['allow_html'] = true;
		$tableData['allow_scripts'] = true;

		$tableData['extended_save'] = true;

		if (!isset($tableData['parent'])) {
			if (isset($tableData['custom_field_price'])) {
				$tableData['parent'] = self::_getParentPageId('shop');
			} else {
				$tableData['parent'] = self::_getParentPageId('blog');
			}
		}

		return save_content($tableData);
	}

	private static function _getParentPageId($type = "blog")
	{
		$params = '';
		$title = 'Blog';

		if ($type == 'shop') {
			$title = 'Shop';
			$params = '&is_shop=1';
		}

		$pages = get_content('no_cache=true&content_type=page&subtype=dynamic&limit=1000' . $params);

		if (empty($pages)) {

			$saveContent = array(
				'title' => $title,
				'content_type' => 'page',
				'subtype' => 'dynamic',
				'is_active' => 1
			);

			if ($type == 'shop') {
				$saveContent['is_shop'] = 1;
			}

			// Saving
			return save_content($saveContent);
		} else {
			return $pages[0]['id'];
		}
	}
}
