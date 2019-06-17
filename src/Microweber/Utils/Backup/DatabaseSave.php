<?php
namespace Microweber\Utils\Backup;

/**
 * Microweber - Backup Module Database Save
 *
 * @namespace Microweber\Utils\Backup
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class DatabaseSave
{

	public static function save($table, $tableData)
	{
		$tableData['skip_cache'] = true;
		$tableData['allow_html'] = true;
		$tableData['allow_scripts'] = true;

		$tableData = self::_fixContentEncoding($tableData);

		return db_save($table, $tableData);
	}

	/**
	 * Fix wrong encoding on database
	 *
	 * @param array $item
	 * @return array
	 */
	private static function _fixContentEncoding($content)
	{
		// Fix content encoding
		array_walk_recursive($content, function (&$element) {
			if (is_string($element)) {
				$utf8Chars = explode(' ', 'À Á Â Ã Ä Å Æ Ç È É Ê Ë Ì Í Î Ï Ð Ñ Ò Ó Ô Õ Ö × Ø Ù Ú Û Ü Ý Þ ß à á â ã ä å æ ç è é ê ë ì í î ï ð ñ ò ó ô õ ö');
				foreach ($utf8Chars as $char) {
					$element = str_replace($char, '', $element);
				}
			}
		});
			
		return $content;
	}
}