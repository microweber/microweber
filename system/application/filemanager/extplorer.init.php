<?php
/** ensure this file is being included by a parent file */
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );

$GLOBALS['ext_home'] = 'http://joomlacode.org/gf/project/joomlaxplorer';

define ( "_EXT_PATH", realpath( dirname(__FILE__).'/../../administrator/components/com_extplorer') );
define ( "_EXT_URL", $mosConfig_live_site."/administrator/components/com_extplorer" );

require _EXT_PATH."/application.php";
require _EXT_PATH."/libraries/File_Operations.php";
require _EXT_PATH."/include/functions.php";
require _EXT_PATH."/include/header.php";
require _EXT_PATH."/include/result.class.php";

if( !class_exists('InputFilter')) {
	require_once _EXT_PATH . '/libraries/inputfilter.php';
}
$GLOBALS['ERROR'] = '';

$GLOBALS['__GET']	=&$_GET;
$GLOBALS['__POST']	=&$_POST;
$GLOBALS['__SERVER']	=&$_SERVER;
$GLOBALS['__FILES']	=&$_FILES;

$default_lang = !empty( $GLOBALS['mosConfig_lang'] ) ? $GLOBALS['mosConfig_lang'] : ext_Lang::detect_lang();
echo $default_lang;
if( file_exists(_EXT_PATH."/languages/$default_lang.php"))
  require _EXT_PATH."/languages/$default_lang.php";
else
  require _EXT_PATH."/languages/english.php";
  
if( file_exists(_EXT_PATH."/languages/".$default_lang."_mimes.php"))
  require _EXT_PATH."/languages/".$default_lang."_mimes.php";
else
  require _EXT_PATH."/languages/english_mimes.php";
  
require _EXT_PATH."/config/mimes.php";
// the filename of the QuiXplorer script: (you rarely need to change this)
if($_SERVER['SERVER_PORT'] == 443 ) {
	$GLOBALS["script_name"] = "https://".$GLOBALS['__SERVER']['HTTP_HOST'].$GLOBALS['__SERVER']["PHP_SELF"];
}
else {
	$GLOBALS["script_name"] = "http://".$GLOBALS['__SERVER']['HTTP_HOST'].$GLOBALS['__SERVER']["PHP_SELF"];
}
@session_start();
if( !isset( $_REQUEST['dir'] )) {
	$dir = $GLOBALS['dir'] = extGetParam( $_SESSION,'ext_dir', '' );
}
else {
	$dir = $GLOBALS['dir'] = $_SESSION['ext_dir'] = extGetParam( $_REQUEST, "dir" );
}


if( strstr( $mosConfig_absolute_path, "/" )) {
	$GLOBALS["separator"] = "/";
}
else {
	$GLOBALS["separator"] = "\\";
}
// Get Sort
$GLOBALS["order"]=extGetParam( $_REQUEST, 'order', 'name');
// Get Sortorder
$GLOBALS["direction"]=extGetParam( $_REQUEST, 'direction', 'ASC');
  
// show hidden files in QuiXplorer: (hide files starting with '.', as in Linux/UNIX)
$GLOBALS["show_hidden"] = true;

// filenames not allowed to access: (uses PCRE regex syntax)
$GLOBALS["no_access"] = "^\.ht";

// user permissions bitfield: (1=modify, 2=password, 4=admin, add the numbers)
$GLOBALS["permissions"] = 1;

$GLOBALS['file_mode'] = 'file';

//------------------------------------------------------------------------------
$GLOBALS['ext_File'] = new ext_File();

$abs_dir=get_abs_dir($GLOBALS["dir"]);
if(!file_exists($GLOBALS["home_dir"])) {
  if(!file_exists($GLOBALS["home_dir"].$GLOBALS["separator"])) {
	if(!empty($GLOBALS["require_login"])) {
		$extra="<a href=\"".make_link("logout",NULL,NULL)."\">".
			$GLOBALS["messages"]["btnlogout"]."</A>";
	} 
	else {
		$extra=NULL;
	}
	$GLOBALS['ERROR'] = $GLOBALS["error_msg"]["home"];
  }
}
if(!down_home($abs_dir)) {
	ext_Result::sendResult('', false, $GLOBALS["dir"]." : ".$GLOBALS["error_msg"]["abovehome"]);
	$dir = $GLOBALS['dir'] = $_SESSION['ext_dir'] = '';
	return false;
}
if(!is_dir($abs_dir) && !is_dir($abs_dir.$GLOBALS["separator"])) {
	$GLOBALS['ERROR'] = $abs_dir." : ".$GLOBALS["error_msg"]["direxist"];
	$dir = $GLOBALS['dir'] = $_SESSION['ext_dir'] = '';
}
//------------------------------------------------------------------------------
