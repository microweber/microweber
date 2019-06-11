<?php
namespace Microweber\Utils\Backup;

/**
 * Microweber - Backup Module Database Save
 * @namespace Microweber\Utils\Backup
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class DatabaseSave
{
	public static function save($table, $tableData) {
		
		$tableData['skip_cache'] = true;
		$tableData['allow_html'] = true;
		$tableData['allow_scripts'] = true;
		
		$tableData = self::_fixContentEncoding($tableData);
		
		return db_save($table, $tableData);
	}
	
		/**
	 * Fix wrong encoding on database
	 * @param array $item
	 * @return array
	 */
	private static function _fixContentEncoding($content) {
		
		// Fix content encoding
		array_walk_recursive($content, function (&$element) {
			if (is_string($element)) {
				$element = utf8_decode($element);
				$element = str_replace('Â ', ' ', $element);
				$element = str_replace("Â ", ' ', $element);
			}
		});
			
		return $content;
	}
}