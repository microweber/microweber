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
				$item = self::fixLetters($item);
			}
		});
		
		//var_dump($content);
		
		return $content;
	}
	
	public static function fixLetters($string) {
		
		$readyString = '';
		
		$letters = self::utf8Split($string);
		
		if (is_array($letters)) {
			foreach($letters as $letter) {
				
				//$letter = utf8_encode($letter);
				//$letter = mb_substr($letter, 0, 1);
				
				//$letter = str_replace('Â ', ' ', $letter);
				//$letter = str_replace("Â ", ' ', $letter);
				
				$readyString .= $letter;
			}
		}
		
		return $readyString;
	}
	
	public static function utf8Split($str, $len = 1)
	{
		$arr = array();
		$strLen = mb_strlen($str);
		for ($i = 0; $i < $strLen; $i ++) {
			$arr[] = mb_substr($str, $i, $len);
		}
		return $arr;
	}
}