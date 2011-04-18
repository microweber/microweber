<?php
/**
 * PHP 4.1.x Compatibility functions
 */

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

if (!function_exists( 'array_change_key_case' )) {
	if (!defined('CASE_LOWER')) {
		define('CASE_LOWER', 0);
	}
	if (!defined('CASE_UPPER')) {
		define('CASE_UPPER', 1);
	}
	function array_change_key_case( $input, $case=CASE_LOWER ) {
		if (!is_array( $input )) {
			return false;
		}
		$array = array();
		foreach ($input as $k=>$v) {
			if ($case) {
				$array[strtoupper( $k )] = $v;
			} else {
				$array[strtolower( $k )] = $v;
			}
		}
		return $array;
	}
}
/**
 * Add functionanlity of html_entity_decode() to PHP under 4.3
 *
 * @category	PHP
 * @package	 PHP_Compat
 * @link		http://php.net/function.html_entity_decode
 * @since		PHP 4.3
 * @require	 PHP 4.0.1 
 */
if (!function_exists('html_entity_decode')) {
	function html_entity_decode($string)
	{
   		// Replace numeric
   		$string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
   		$string = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $string);
   		
   		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
   		$trans_tbl = array_flip($trans_tbl);
   		return strtr($string, $trans_tbl);
	}
}
?>