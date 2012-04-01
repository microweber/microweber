<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: system_info.php 187 2011-01-18 15:25:24Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2009
 * @author The eXtplorer project (http://sourceforge.net/projects/extplorer)
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
 */

function get_php_setting($val, $recommended=1) {
	$value = ini_get($val);
	$r = ( $value == $recommended ? 1 : 0);
	if( empty($value)) {
		$onoff = 1;
	}
	else {
		$onoff = 0;
	}
	return $r ? '<span style="color: green;">' . $GLOBALS['messages']['sionoff'][$onoff] . '</span>' : '<span style="color: red;">' . $GLOBALS['messages']['sionoff'][$onoff] . '</span>';
}

function get_server_software() {
	if (isset($_SERVER['SERVER_SOFTWARE'])) {
		return $_SERVER['SERVER_SOFTWARE'];
	} else if (($sf = getenv('SERVER_SOFTWARE'))) {
		return $sf;
	} else {
		return 'n/a';
	}
}

function system_info() {
	$version = $GLOBALS['ext_version'];
	//$tab = extGetParam( $_REQUEST, 'tab', 'tab1' );
	$width = 400;	// width of 100%

	switch( extGetParam($GLOBALS['__POST'],'action2', 'panel') ) {
		case 'systeminfo':
		?>
		<div class="body-wrap">
		  <table class="member-table">
			<tr>
				<td valign="top" width="250" style="font-weight:bold;">
				Logged in as:
				</td>
				<td><?php echo $_SESSION['credentials_extplorer']['username'] ?></td>
			<tr>
				<td valign="top" width="250" style="font-weight:bold;">
					<?php echo ext_lang::msg( 'sibuilton' ); ?>:
				</td>
				<td>
				<?php echo php_uname(); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" style="font-weight:bold;">
					<?php echo ext_lang::msg( 'siphpversion' ); ?>:
				</td>
				<td>
				<?php echo phpversion(); ?>
				&nbsp;
				<?php echo phpversion() >= '4.3' ? '' : $GLOBALS['messages']['siphpupdate']; ?>
				</td>
			</tr>
			<tr>
				<td style="font-weight:bold;">
					<?php echo ext_lang::msg( 'siwebserver' ); ?>:
				</td>
				<td>
				<?php echo get_server_software(); ?>
				</td>
			</tr>
			<tr>
				<td style="font-weight:bold;">
					<?php echo ext_lang::msg( 'siwebsphpif' ); ?>:
				</td>
				<td>
				<?php echo php_sapi_name(); ?>
				</td>
			</tr>
			<tr>
				<td style="font-weight:bold;">
					<?php echo ext_lang::msg( 'simamboversion' ); ?>:
				</td>
				<td>
				<?php echo $version; ?>
				</td>
			</tr>
			<tr>
				<td style="font-weight:bold;">
					<?php echo ext_lang::msg( 'siuseragent' ); ?>:
				</td>
				<td>
				<?php echo phpversion() <= "4.2.1" ? getenv( "HTTP_USER_AGENT" ) : $_SERVER['HTTP_USER_AGENT'];?>
				</td>
			</tr>
			<tr>
				<td valign="top" style="font-weight:bold;">
					<?php echo ext_lang::msg( 'sirelevantsettings' ); ?>:
				</td>
				<td>
					<table cellspacing="1" cellpadding="1" border="0">
					<tr>
						<td valign="top">
							<?php echo ext_lang::msg( 'sisafemode' ); ?>:
						</td>
						<td>
						<?php echo get_php_setting('safe_mode', 0); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo ext_lang::msg( 'sibasedir' ); ?>:
						</td>
						<td>
						<?php echo (($ob = ini_get('open_basedir')) ? $ob : 'none'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo ext_lang::msg( 'sidisplayerrors' ); ?>:
						</td>
						<td>
						<?php echo get_php_setting('display_errors', 0 ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo ext_lang::msg( 'sishortopentags' ); ?>:
						</td>
						<td>
						<?php echo get_php_setting('short_open_tag', 0 ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo ext_lang::msg( 'sifileuploads' ); ?>:
						</td>
						<td>
						<?php echo get_php_setting('file_uploads'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo ext_lang::msg( 'simagicquotes' ); ?>:
						</td>
						<td>
						<?php echo get_php_setting('magic_quotes_gpc'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo ext_lang::msg( 'siregglobals' ); ?>:
						</td>
						<td>
						<?php echo get_php_setting('register_globals', 0); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo ext_lang::msg( 'sioutputbuf' ); ?>:
						</td>
						<td>
						<?php echo get_php_setting('output_buffering', 0); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo ext_lang::msg( 'sisesssavepath' ); ?>:
						</td>
						<td>
						<?php echo (( $sp=ini_get( 'session.save_path' )) ? $sp : 'none' ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo ext_lang::msg( 'sisessautostart' ); ?>:
						</td>
						<td>
						<?php echo intval( ini_get( 'session.auto_start' ) ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo ext_lang::msg( 'sixmlenabled' ); ?>:
						</td>
						<td>
							<?php echo extension_loaded('xml') ? '<font style="color: green;">' . $GLOBALS['messages']['miscyesno'][0] . '</font>' : '<font style="color: red;">' . $GLOBALS['messages']['miscyesno'][1] . '</font>'; ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo ext_lang::msg( 'sizlibenabled' ); ?>:
						</td>
						<td>
						<?php echo extension_loaded('zlib') ? '<font style="color: green;">' . $GLOBALS['messages']['miscyesno'][0] . '</font>' : '<font style="color: red;">' . $GLOBALS['messages']['miscyesno'][1] . '</font>'; ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo ext_lang::msg( 'sidisabledfuncs' ); ?>:
						</td>
						<td>
						<?php echo (( $df=ini_get('disable_functions' )) ? $df : 'none' ); ?>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
		</div>
			<?php 
			break;
		case 'phpinfo':

			ob_start();
			phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
			$phpinfo = ob_get_contents();
			ob_end_clean();
			preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpinfo, $output);
			$output = preg_replace('#<table#', '<table class="member-table" align="center"', $output[1][0]);
			$output = '<div class="body-wrap">'.$output.'</div>';
			$output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
			$output = preg_replace('#border="0" cellpadding="3" width="600"#', 'border="0" cellspacing="1" cellpadding="4" width="95%"', $output);
			$output = preg_replace('#<hr />#', '', $output);
			echo $output;
					
			break;
			
		case 'about':
			show_about();
			break;
			
		default:
			?>
			{
				"xtype": "tabpanel",
				
				"height": 350,
				"activeTab": 0,
				"items": [{
					"title": "<?php echo ext_Lang::msg( 'aboutlink' ) ?>",
					"autoScroll": true,
					"autoLoad": { 
						"url": "<?php echo $GLOBALS['script_name'] ?>",
						"params": {
							"option": "com_extplorer",
							"action": "get_about",
							"action2": "about"
						}
					}
				},{
					"title": "<?php echo ext_Lang::msg( 'sisysteminfo' ) ?>",
					"autoScroll": true,
					"autoLoad": { 
						"url": "<?php echo $GLOBALS['script_name'] ?>",
						"params": {
							"option": "com_extplorer",
							"action": "get_about",
							"action2": "systeminfo"
						}
					}
				},{
					"title": "<?php echo ext_Lang::msg('siphpinfo' ); ?>",
					"autoScroll": true,
					"autoLoad": { 
						"url": "<?php echo $GLOBALS['script_name'] ?>",
						"params": {
							"option": "com_extplorer",
							"action": "get_about",
							"action2": "phpinfo"
						}
					}
				}]
			}
			<?php
	}
	
}
/**
 * 
 * Shows eXtplorer information
 */
function show_about() {			
	//$sess = print_r($_SESSION,true);
	//echo str_replace(array("\r", "\n"),array('',''),$sess);
	echo "\n<div id=\"ext_footer\" style=\"text-align:center;\">
	<img src=\""._EXT_URL."/images/eXtplorer_logo.png\" align=\"middle\" alt=\"eXtplorer Logo\" />
	<br />
	".ext_Lang::msg('your_version').": <a href=\"".$GLOBALS['ext_home']."\" target=\"_blank\">eXtplorer {$GLOBALS['ext_version']}</a>
	<br />
 (<a href=\"http://virtuemart.net/index2.php?option=com_versions&amp;catid=5&amp;myVersion=". $GLOBALS['ext_version'] ."\" onclick=\"javascript:void window.open('http://virtuemart.net/index2.php?option=com_versions&catid=5&myVersion=". $GLOBALS['ext_version'] ."', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=580,directories=no,location=no'); return false;\" title=\"".$GLOBALS["messages"]["check_version"]."\">".$GLOBALS["messages"]["check_version"]."</a>)

	";
	
	$size = disk_free_space($GLOBALS['home_dir']. $GLOBALS['separator']);
	$free=parse_file_size($size);

	echo '<br />'.$GLOBALS["messages"]["miscfree"].": ".$free." \n";
	if( extension_loaded( "posix" )) {
		$owner_info = '<br /><br />'.ext_Lang::msg('current_user').' ';
		if( ext_isFTPMode() ) {
			$my_user_info = posix_getpwnam( $_SESSION['ftp_login'] );
			$my_group_info = posix_getgrgid( $my_user_info['gid'] );
		} else {
			$my_user_info = posix_getpwuid( posix_geteuid() );
			$my_group_info = posix_getgrgid(posix_getegid() );
		}
		$owner_info .= $my_user_info['name'].' ('. $my_user_info['uid'].'), '. $my_group_info['name'].' ('. $my_group_info['gid'].')'; 

		echo $owner_info;
	}
	echo "
	</div>";
}
?>