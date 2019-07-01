<?php
namespace Microweber\Utils\Backup;

/**
 * Microweber - Backup Module Database Save
 *
 * @namespace Microweber\Utils\Backup
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class EncodingFix
{
	
	/**
	 * Fix wrong encoding on database
	 *
	 * @param array $item
	 * @return array
	 */
	public static function runFix($content)
	{
		// Fix content encoding
		array_walk_recursive($content, function (&$item) {
			if (is_string($item)) {
				$item = utf8_decode($item);
			}
		});
		
		return $content;
	}
}