<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @package eXtplorer
 * @version $Id: application.php 174 2010-08-20 09:27:45Z soeren $
 * @copyright soeren 2007-2010
 * @author The eXtplorer project (http://sourceforge.net/projects/extplorer)
 * @license
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License',
 * 
 * Alternatively, the contents of this file may be used under the terms
 * of the GNU General Public License Version 2 or later (the "GPL"), in
 * which case the provisions of the GPL are applicable instead of
 * those above. If you wish to allow use of your version of this file only
 * under the terms of the GPL and not to allow others to use
 * your version of this file under the MPL, indicate your decision by
 * deleting  the provisions above and replace  them with the notice and
 * other provisions required by the GPL.  If you do not delete
 * the provisions above, a recipient may use your version of this file
 * under either the MPL or the GPL."
 * 
*/
/**
 * Abstract Action Class
 * @abstract 
 */
class ext_Action {

	/**
	* This function executes the action
	*
	* @param string $dir
	* @param string $item
	*/
	function execAction( $dir, $item ) {
		// to be overridden by the child class
	}

}
/**
 * Wrapper Class for the Global Language Array
 * @since 2.0.0
 * @author soeren
 *
 */
class ext_Lang {
	/**
	* Returns a string from $GLOBALS['messages']
	*
	* @param string $msg
	* @param boolean $make_javascript_safe
	* @return string
	*/
	function msg( $msg, $make_javascript_safe=false ) {
		$str = ext_Lang::_get('messages', $msg );
		if( $make_javascript_safe ) {
			return ext_Lang::escape_for_javascript( $str );
		} else {
			return $str;
		}
	}
	/**
	* Returns a string from $GLOBALS['error_msg']
	*
	* @param string $err
	* @param boolean $make_javascript_safe
	* @return string
	*/
	function err( $err, $make_javascript_safe=false ) {
		$str = ext_Lang::_get('error_msg', $err );
		if( $make_javascript_safe ) {
			return ext_Lang::escape_for_javascript( $str );
		} else {
			return $str;
		}
	}
	function mime( $mime, $make_javascript_safe=false ) {
		$str = ext_Lang::_get('mimes', $mime );
		if( $make_javascript_safe ) {
			return ext_Lang::escape_for_javascript( $str );
		} else {
			return $str;
		}
	}
	/**
	* Gets the string from the array
	*
	* @param string $array_index
	* @param string $message
	* @return string
	* @access private
	*/
	function _get( $array_index, $message ) {
		if( is_array( $message )) {
			return @$GLOBALS[$array_index][key($message)][current($message)];
		}
		return isset($GLOBALS[$array_index][$message]) ? $GLOBALS[$array_index][$message] : $message;
	}

	function escape_for_javascript( $string ) {
		return str_replace(Array("\r", "\n" ), Array('\r', '\n' ) , addslashes($string));
	}
	function detect_lang() {
		$default = 'english';
		if( empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) return $default;

		$_AL=strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$_UA=strtolower($_SERVER['HTTP_USER_AGENT']);

		// Try to detect Primary language if several languages are accepted',
		foreach($GLOBALS['_LANG'] as $K => $lang) {
		if(strpos($_AL, $K)===0)
		return file_exists( _EXT_PATH.'/languages/'.$lang.'.php' ) ? $lang : $default;
		}

		// Try to detect any language if not yet detected',
		foreach($GLOBALS['_LANG'] as $K => $lang) {
		if(strpos($_AL, $K)!==false)
		return file_exists( _EXT_PATH.'/languages/'.$lang.'.php' ) ? $lang : $default;
		}
		foreach($GLOBALS['_LANG'] as $K => $lang) {
		if(preg_match("/[\[\( ]{$K}[;,_\-\)]/",$_UA))
		return file_exists( _EXT_PATH.'/languages/'.$lang.'.php' ) ? $lang : $default;
		}

		// Return default language if language is not yet detected',
		return $default;
	}
}
// Define all available languages',
// WARNING: uncomment all available languages

$GLOBALS['_LANG'] = array(
'af' => 'afrikaans',
'ar' => 'arabic',
'bg' => 'bulgarian',
'ca' => 'catalan',
'cs' => 'czech',
'da' => 'danish',
'de' => 'german',
'el' => 'greek',
'en' => 'english',
'es' => 'spanish',
'et' => 'estonian',
'fi' => 'finnish',
'fr' => 'french',
'gl' => 'galician',
'he' => 'hebrew',
'hi' => 'hindi',
'hr' => 'croatian',
'hu' => 'hungarian',
'id' => 'indonesian',
'it' => 'italian',
'ja' => 'japanese',
'ko' => 'korean',
'ka' => 'georgian',
'lt' => 'lithuanian',
'lv' => 'latvian',
'ms' => 'malay',
'nl' => 'dutch',
'no' => 'norwegian',
'pl' => 'polish',
'pt' => 'portuguese',
'ro' => 'romanian',
'ru' => 'russian',
'sk' => 'slovak',
'sl' => 'slovenian',
'sq' => 'albanian',
'sr' => 'serbian',
'sv' => 'swedish',
'th' => 'thai',
'tr' => 'turkish',
'uk' => 'ukrainian',
'zh-tw' => 'traditional_chinese',
'zh-cn' => 'simplified_chinese'
);
