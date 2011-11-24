<?php
/**
 * @version $Id: index.php 176 2010-11-06 08:44:55Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2010
 * @author The eXtplorer project (http://joomlacode.org/gf/project/joomlaxplorer/)
 * @author The	The QuiX project (http://quixplorer.sourceforge.net)
 * 
 * @license
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
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
 * Main File for the standalone version
 */
// When eXtplorer is running as a component in Joomla! or Mambo, we deny access to this standalone version
if( stristr( $_SERVER['SCRIPT_NAME'], 'administrator/components/com_extplorer')) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://'.$_SERVER['HTTP_HOST']);
	exit;
}

// Set flag that this is a parent file
define( '_VALID_MOS', 1 );
define( '_VALID_EXT', 1 );

require_once( dirname(__FILE__).'/libraries/standalone.php');
ob_start();
include( dirname(__FILE__).'/admin.extplorer.php' );
$mainbody = ob_get_contents();
ob_end_clean();

extInitGzip();
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );

echo '<?xml version="1.0" encoding="'. $GLOBALS["charset"].'">';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $mainframe->getHead(); ?>
		<link rel="shortcut icon" href="<?php echo _EXT_URL ?>/eXtplorer.ico" />
 		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $GLOBALS["charset"]; ?>" />
		<meta name="robots" content="noindex, nofollow" />
	</head>
	<body>
		<?php echo $mainbody; ?>
	</body>
</html>
<?php
extDoGzip();

?>