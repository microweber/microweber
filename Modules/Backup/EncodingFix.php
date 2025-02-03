<?php
namespace MicroweberPackages\Backup;

/**
 * Microweber - Backup Module Database Save
 *
 * @namespace MicroweberPackages\Backup
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class EncodingFix
{

	/**
	 *
	 * @param array $item
	 * @return array
	 */
	public static function decode($content)
	{
        $canDecode = function_exists('utf8_decode');

		if (!empty($content)) {
			array_walk_recursive($content, function (&$item) use ($canDecode) {
				if (is_string($item)) {
                    if($canDecode){
                        $item = utf8_decode($item);
                    }
 				}
			});
		}

		return $content;
	}

	/**
	 *
	 * @param array $item
	 * @return array
	 */
	public static function encode($content)
	{
        $canEncode = function_exists('utf8_encode');

        if (!empty($content)) {
			array_walk_recursive($content, function (&$item) use ($canEncode) {
				if (is_string($item)) {
                    if($canEncode) {
                        $item = utf8_encode($item);
                    }
				}
			});
		}

		return $content;
	}
}
