<?php
//////////////////////////////////////////////////////////////
///  phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// See: phpthumb.changelog.txt for recent changes           //
// See: phpthumb.readme.txt for usage instructions          //
//                                                         ///
//////////////////////////////////////////////////////////////

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('magic_quotes_runtime', '0');
if (@ini_get('magic_quotes_runtime')) {
	die('"magic_quotes_runtime" is set in php.ini, cannot run phpThumb with this enabled');
}
$starttime = array_sum(explode(' ', microtime()));

// this script relies on the superglobal arrays, fake it here for old PHP versions
if (phpversion() < '4.1.0') {
	$_SERVER = $HTTP_SERVER_VARS;
	$_GET    = $HTTP_GET_VARS;
}

// instantiate a new phpThumb() object
ob_start();
if (!include_once(dirname(__FILE__).'/phpthumb.class.php')) {
	ob_end_flush();
	die('failed to include_once("'.realpath(dirname(__FILE__).'/phpthumb.class.php').'")');
}
ob_end_clean();

$phpThumb = new phpThumb();
$phpThumb->DebugTimingMessage('phpThumb.php start', __FILE__, __LINE__, $starttime);
$phpThumb->SetParameter('config_error_die_on_error', true);

if (!phpthumb_functions::FunctionIsDisabled('set_time_limit')) {
	set_time_limit(60);  // shouldn't take nearly this long in most cases, but with many filters and/or a slow server...
}

// phpThumbDebug[0] used to be here, but may reveal too much
// info when high_security_mode should be enabled (not set yet)

if (file_exists(dirname(__FILE__).'/phpThumb.config.php')) {
	ob_start();
	if (include_once(dirname(__FILE__).'/phpThumb.config.php')) {
		// great
	} else {
		ob_end_flush();
		$phpThumb->ErrorImage('failed to include_once('.dirname(__FILE__).'/phpThumb.config.php) - realpath="'.realpath(dirname(__FILE__).'/phpThumb.config.php').'"');
	}
	ob_end_clean();
} elseif (file_exists(dirname(__FILE__).'/phpThumb.config.php.default')) {
	$phpThumb->ErrorImage('Please rename "phpThumb.config.php.default" to "phpThumb.config.php"');
} else {
	$phpThumb->ErrorImage('failed to include_once('.dirname(__FILE__).'/phpThumb.config.php) - realpath="'.realpath(dirname(__FILE__).'/phpThumb.config.php').'"');
}

if (!@$PHPTHUMB_CONFIG['disable_pathinfo_parsing'] && (empty($_GET) || isset($_GET['phpThumbDebug'])) && !empty($_SERVER['PATH_INFO'])) {
	$_SERVER['PHP_SELF'] = str_replace($_SERVER['PATH_INFO'], '', @$_SERVER['PHP_SELF']);

	$args = explode(';', substr($_SERVER['PATH_INFO'], 1));
	$phpThumb->DebugMessage('PATH_INFO.$args set to ('.implode(')(', $args).')', __FILE__, __LINE__);
	if (!empty($args)) {
		$_GET['src'] = @$args[count($args) - 1];
		$phpThumb->DebugMessage('PATH_INFO."src" = "'.$_GET['src'].'"', __FILE__, __LINE__);
		if (eregi('^new\=([a-z0-9]+)', $_GET['src'], $matches)) {
			unset($_GET['src']);
			$_GET['new'] = $matches[1];
		}
	}
	if (eregi('^([0-9]*)x?([0-9]*)$', @$args[count($args) - 2], $matches)) {
		$_GET['w'] = $matches[1];
		$_GET['h'] = $matches[2];
		$phpThumb->DebugMessage('PATH_INFO."w"x"h" set to "'.$_GET['w'].'"x"'.$_GET['h'].'"', __FILE__, __LINE__);
	}
	for ($i = 0; $i < count($args) - 2; $i++) {
		@list($key, $value) = explode('=', @$args[$i]);
		if (substr($key, -2) == '[]') {
			$array_key_name = substr($key, 0, -2);
			$_GET[$array_key_name][] = $value;
			$phpThumb->DebugMessage('PATH_INFO."'.$array_key_name.'[]" = "'.$value.'"', __FILE__, __LINE__);
		} else {
			$_GET[$key] = $value;
			$phpThumb->DebugMessage('PATH_INFO."'.$key.'" = "'.$value.'"', __FILE__, __LINE__);
		}
	}
}

if (@$PHPTHUMB_CONFIG['high_security_enabled']) {
	if (!@$_GET['hash']) {
		$phpThumb->ErrorImage('ERROR: missing hash');
	} elseif (strlen($PHPTHUMB_CONFIG['high_security_password']) < 5) {
		$phpThumb->ErrorImage('ERROR: strlen($PHPTHUMB_CONFIG[high_security_password]) < 5');
	} elseif ($_GET['hash'] != md5(str_replace('&hash='.$_GET['hash'], '', $_SERVER['QUERY_STRING']).$PHPTHUMB_CONFIG['high_security_password'])) {
		$phpThumb->ErrorImage('ERROR: invalid hash');
	}
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$phpThumb->DebugTimingMessage('phpThumbDebug[0]', __FILE__, __LINE__);
if (@$_GET['phpThumbDebug'] == '0') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

// returned the fixed string if the evil "magic_quotes_gpc" setting is on
if (get_magic_quotes_gpc()) {
	// deprecated: 'err', 'file', 'goto',
	$RequestVarsToStripSlashes = array('src', 'wmf', 'down');
	foreach ($RequestVarsToStripSlashes as $key) {
		if (isset($_GET[$key])) {
			if (is_string($_GET[$key])) {
				$_GET[$key] = stripslashes($_GET[$key]);
			} else {
				unset($_GET[$key]);
			}
		}
	}
}

if (!@$_SERVER['PATH_INFO'] && !@$_SERVER['QUERY_STRING']) {
	$phpThumb->ErrorImage('phpThumb() v'.$phpThumb->phpthumb_version.'<br><a href="http://phpthumb.sourceforge.net">http://phpthumb.sourceforge.net</a><br><br>ERROR: no parameters specified');
}

if (@$_GET['src'] && isset($_GET['md5s']) && empty($_GET['md5s'])) {
	if (eregi('^(f|ht)tps?://', $_GET['src'])) {
		if ($rawImageData = phpthumb_functions::SafeURLread($_GET['src'], $error, $phpThumb->config_http_fopen_timeout, $phpThumb->config_http_follow_redirect)) {
			$md5s = md5($rawImageData);
		}
	} else {
		$SourceFilename = $phpThumb->ResolveFilenameToAbsolute($_GET['src']);
		if (is_readable($SourceFilename)) {
			$md5s = phpthumb_functions::md5_file_safe($SourceFilename);
		} else {
			$phpThumb->ErrorImage('ERROR: "'.$SourceFilename.'" cannot be read');
		}
	}
	if (@$_SERVER['HTTP_REFERER']) {
		$phpThumb->ErrorImage('&md5s='.$md5s);
	} else {
		die('&md5s='.$md5s);
	}
}

if (!empty($PHPTHUMB_CONFIG)) {
	foreach ($PHPTHUMB_CONFIG as $key => $value) {
		$keyname = 'config_'.$key;
		$phpThumb->setParameter($keyname, $value);
		if (!eregi('password|mysql', $key)) {
			$phpThumb->DebugMessage('setParameter('.$keyname.', '.$phpThumb->phpThumbDebugVarDump($value).')', __FILE__, __LINE__);
		}
	}
} else {
	$phpThumb->DebugMessage('$PHPTHUMB_CONFIG is empty', __FILE__, __LINE__);
}

if (@$_GET['src'] && !@$PHPTHUMB_CONFIG['allow_local_http_src'] && eregi('^http://'.@$_SERVER['HTTP_HOST'].'(.+)', @$_GET['src'], $matches)) {
	$phpThumb->ErrorImage('It is MUCH better to specify the "src" parameter as "'.$matches[1].'" instead of "'.$matches[0].'".'."\n\n".'If you really must do it this way, enable "allow_local_http_src" in phpThumb.config.php');
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$phpThumb->DebugTimingMessage('phpThumbDebug[1]', __FILE__, __LINE__);
if (@$_GET['phpThumbDebug'] == '1') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

$parsed_url_referer = phpthumb_functions::ParseURLbetter(@$_SERVER['HTTP_REFERER']);
if ($phpThumb->config_nooffsitelink_require_refer && !in_array(@$parsed_url_referer['host'], $phpThumb->config_nohotlink_valid_domains)) {
	$phpThumb->ErrorImage('config_nooffsitelink_require_refer enabled and '.(@$parsed_url_referer['host'] ? '"'.$parsed_url_referer['host'].'" is not an allowed referer' : 'no HTTP_REFERER exists'));
}
$parsed_url_src = phpthumb_functions::ParseURLbetter(@$_GET['src']);
if ($phpThumb->config_nohotlink_enabled && $phpThumb->config_nohotlink_erase_image && eregi('^(f|ht)tps?://', @$_GET['src']) && !in_array(@$parsed_url_src['host'], $phpThumb->config_nohotlink_valid_domains)) {
	$phpThumb->ErrorImage($phpThumb->config_nohotlink_text_message);
}

if ($phpThumb->config_mysql_query) {
	if ($cid = @mysql_connect($phpThumb->config_mysql_hostname, $phpThumb->config_mysql_username, $phpThumb->config_mysql_password)) {
		if (@mysql_select_db($phpThumb->config_mysql_database, $cid)) {
			if ($result = @mysql_query($phpThumb->config_mysql_query, $cid)) {
				if ($row = @mysql_fetch_array($result)) {

					mysql_free_result($result);
					mysql_close($cid);
					$phpThumb->setSourceData($row[0]);
					unset($row);

				} else {
					mysql_free_result($result);
					mysql_close($cid);
					$phpThumb->ErrorImage('no matching data in database.');
				}
			} else {
				mysql_close($cid);
				$phpThumb->ErrorImage('Error in MySQL query: "'.mysql_error($cid).'"');
			}
		} else {
			mysql_close($cid);
			$phpThumb->ErrorImage('cannot select MySQL database: "'.mysql_error($cid).'"');
		}
	} else {
		$phpThumb->ErrorImage('cannot connect to MySQL server');
	}
	unset($_GET['id']);
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$phpThumb->DebugTimingMessage('phpThumbDebug[2]', __FILE__, __LINE__);
if (@$_GET['phpThumbDebug'] == '2') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

$PHPTHUMB_DEFAULTS_DISABLEGETPARAMS = (bool) (@$PHPTHUMB_CONFIG['cache_default_only_suffix'] && (strpos($PHPTHUMB_CONFIG['cache_default_only_suffix'], '*') !== false));

if (!empty($PHPTHUMB_DEFAULTS) && is_array($PHPTHUMB_DEFAULTS)) {
	$phpThumb->DebugMessage('setting $PHPTHUMB_DEFAULTS['.implode(';', array_keys($PHPTHUMB_DEFAULTS)).']', __FILE__, __LINE__);
	foreach ($PHPTHUMB_DEFAULTS as $key => $value) {
		if ($PHPTHUMB_DEFAULTS_GETSTRINGOVERRIDE || !isset($_GET[$key])) {
			$_GET[$key] = $value;
			$phpThumb->DebugMessage('PHPTHUMB_DEFAULTS assigning ('.$value.') to $_GET['.$key.']', __FILE__, __LINE__);
		}
	}
}

// deprecated: 'err', 'file', 'goto',
$allowedGETparameters = array('src', 'new', 'w', 'h', 'wp', 'hp', 'wl', 'hl', 'ws', 'hs', 'f', 'q', 'sx', 'sy', 'sw', 'sh', 'zc', 'bc', 'bg', 'bgt', 'fltr', 'xto', 'ra', 'ar', 'aoe', 'far', 'iar', 'maxb', 'down', 'phpThumbDebug', 'hash', 'md5s', 'sfn', 'dpi', 'sia', 'nocache');
foreach ($_GET as $key => $value) {
	if (@$PHPTHUMB_DEFAULTS_DISABLEGETPARAMS && ($key != 'src')) {
		// disabled, do not set parameter
		$phpThumb->DebugMessage('ignoring $_GET['.$key.'] because of $PHPTHUMB_DEFAULTS_DISABLEGETPARAMS', __FILE__, __LINE__);
	} elseif (in_array($key, $allowedGETparameters)) {
		$phpThumb->DebugMessage('setParameter('.$key.', '.$phpThumb->phpThumbDebugVarDump($value).')', __FILE__, __LINE__);
		$phpThumb->setParameter($key, $value);
	} else {
		$phpThumb->ErrorImage('Forbidden parameter: '.$key);
	}
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$phpThumb->DebugTimingMessage('phpThumbDebug[3]', __FILE__, __LINE__);
if (@$_GET['phpThumbDebug'] == '3') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

//if (!@$_GET['phpThumbDebug'] && !is_file($phpThumb->sourceFilename) && !phpthumb_functions::gd_version()) {
//	if (!headers_sent()) {
//		// base64-encoded error image in GIF format
//		$ERROR_NOGD = 'R0lGODlhIAAgALMAAAAAABQUFCQkJDY2NkZGRldXV2ZmZnJycoaGhpSUlKWlpbe3t8XFxdXV1eTk5P7+/iwAAAAAIAAgAAAE/vDJSau9WILtTAACUinDNijZtAHfCojS4W5H+qxD8xibIDE9h0OwWaRWDIljJSkUJYsN4bihMB8th3IToAKs1VtYM75cyV8sZ8vygtOE5yMKmGbO4jRdICQCjHdlZzwzNW4qZSQmKDaNjhUMBX4BBAlmMywFSRWEmAI6b5gAlhNxokGhooAIK5o/pi9vEw4Lfj4OLTAUpj6IabMtCwlSFw0DCKBoFqwAB04AjI54PyZ+yY3TD0ss2YcVmN/gvpcu4TOyFivWqYJlbAHPpOntvxNAACcmGHjZzAZqzSzcq5fNjxFmAFw9iFRunD1epU6tsIPmFCAJnWYE0FURk7wJDA0MTKpEzoWAAskiAAA7';
//		header('Content-Type: image/gif');
//		echo base64_decode($ERROR_NOGD);
//	} else {
//		echo '*** ERROR: No PHP-GD support available ***';
//	}
//	exit;
//}

// check to see if file can be output from source with no processing or caching
$CanPassThroughDirectly = true;
if ($phpThumb->rawImageData) {
	// data from SQL, should be fine
} elseif (eregi('^http\://.+\.(jpe?g|gif|png)$', $phpThumb->src)) {
	// assume is ok to passthru if no other parameters specified
} elseif (!@is_file($phpThumb->sourceFilename)) {
	$phpThumb->DebugMessage('$CanPassThroughDirectly=false because !@is_file('.$phpThumb->sourceFilename.')', __FILE__, __LINE__);
	$CanPassThroughDirectly = false;
} elseif (!@is_readable($phpThumb->sourceFilename)) {
	$phpThumb->DebugMessage('$CanPassThroughDirectly=false because !@is_readable('.$phpThumb->sourceFilename.')', __FILE__, __LINE__);
	$CanPassThroughDirectly = false;
}
foreach ($_GET as $key => $value) {
	switch ($key) {
		case 'src':
			// allowed
			break;

		case 'w':
		case 'h':
			// might be OK if exactly matches original
			if (eregi('^http\://.+\.(jpe?g|gif|png)$', $phpThumb->src)) {
				// assume it is not ok for direct-passthru of remote image
				$CanPassThroughDirectly = false;
			}
			break;

		case 'phpThumbDebug':
			// handled in direct-passthru code
			break;

		default:
			// all other parameters will cause some processing,
			// therefore cannot pass through original image unmodified
			$CanPassThroughDirectly = false;
			$UnAllowedGET[] = $key;
			break;
	}
}
if (!empty($UnAllowedGET)) {
	$phpThumb->DebugMessage('$CanPassThroughDirectly=false because $_GET['.implode(';', array_unique($UnAllowedGET)).'] are set', __FILE__, __LINE__);
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$phpThumb->DebugTimingMessage('phpThumbDebug[4]', __FILE__, __LINE__);
if (@$_GET['phpThumbDebug'] == '4') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

function SendSaveAsFileHeaderIfNeeded() {
	if (headers_sent()) {
		return false;
	}
	global $phpThumb;
	$downloadfilename = phpthumb_functions::SanitizeFilename(@$_GET['sia'] ? $_GET['sia'] : (@$_GET['down'] ? $_GET['down'] : 'phpThumb_generated_thumbnail'.(@$_GET['f'] ? $_GET['f'] : 'jpg')));
	if (@$downloadfilename) {
		$phpThumb->DebugMessage('SendSaveAsFileHeaderIfNeeded() sending header: Content-Disposition: '.(@$_GET['down'] ? 'attachment' : 'inline').'; filename="'.$downloadfilename.'"', __FILE__, __LINE__);
		header('Content-Disposition: '.(@$_GET['down'] ? 'attachment' : 'inline').'; filename="'.$downloadfilename.'"');
	}
	return true;
}

$phpThumb->DebugMessage('$CanPassThroughDirectly="'.intval($CanPassThroughDirectly).'" && $phpThumb->src="'.$phpThumb->src.'"', __FILE__, __LINE__);
while ($CanPassThroughDirectly && $phpThumb->src) {
	// no parameters set, passthru

	if (eregi('^http\://.+\.(jpe?g|gif|png)$', $phpThumb->src)) {
		$phpThumb->DebugMessage('Passing HTTP source through directly as Location: redirect ('.$phpThumb->src.')', __FILE__, __LINE__);
		header('Location: '.$phpThumb->src);
		exit;
	}

	$SourceFilename = $phpThumb->ResolveFilenameToAbsolute($phpThumb->src);

	// security and size checks
	if ($phpThumb->getimagesizeinfo = @GetImageSize($SourceFilename)) {
		$phpThumb->DebugMessage('Direct passthru GetImageSize() returned [w='.$phpThumb->getimagesizeinfo[0].';h='.$phpThumb->getimagesizeinfo[1].';t='.$phpThumb->getimagesizeinfo[2].']', __FILE__, __LINE__);

		if (!@$_GET['w'] && !@$_GET['wp'] && !@$_GET['wl'] && !@$_GET['ws'] && !@$_GET['h'] && !@$_GET['hp'] && !@$_GET['hl'] && !@$_GET['hs']) {
			// no resizing needed
			$phpThumb->DebugMessage('Passing "'.$SourceFilename.'" through directly, no resizing required ("'.$phpThumb->getimagesizeinfo[0].'"x"'.$phpThumb->getimagesizeinfo[1].'")', __FILE__, __LINE__);
		} elseif ((($phpThumb->getimagesizeinfo[0] <= @$_GET['w']) || ($phpThumb->getimagesizeinfo[1] <= @$_GET['h'])) && ((@$_GET['w'] == $phpThumb->getimagesizeinfo[0]) || (@$_GET['h'] == $phpThumb->getimagesizeinfo[1]))) {
			// image fits into 'w'x'h' box, and at least one dimension matches exactly, therefore no resizing needed
			$phpThumb->DebugMessage('Passing "'.$SourceFilename.'" through directly, no resizing required ("'.$phpThumb->getimagesizeinfo[0].'"x"'.$phpThumb->getimagesizeinfo[1].'" fits inside "'.@$_GET['w'].'"x"'.@$_GET['h'].'")', __FILE__, __LINE__);
		} else {
			$phpThumb->DebugMessage('Not passing "'.$SourceFilename.'" through directly because resizing required (from "'.$phpThumb->getimagesizeinfo[0].'"x"'.$phpThumb->getimagesizeinfo[1].'" to "'.@$_GET['w'].'"x"'.@$_GET['h'].'")', __FILE__, __LINE__);
			break;
		}
		switch ($phpThumb->getimagesizeinfo[2]) {
			case 1: // GIF
			case 2: // JPG
			case 3: // PNG
				// great, let it through
				break;
			default:
				// browser probably can't handle format, remangle it to JPEG/PNG/GIF
				$phpThumb->DebugMessage('Not passing "'.$SourceFilename.'" through directly because $phpThumb->getimagesizeinfo[2] = "'.$phpThumb->getimagesizeinfo[2].'"', __FILE__, __LINE__);
				break 2;
		}

		$ImageCreateFunctions = array(1=>'ImageCreateFromGIF', 2=>'ImageCreateFromJPEG', 3=>'ImageCreateFromPNG');
		$theImageCreateFunction = @$ImageCreateFunctions[$phpThumb->getimagesizeinfo[2]];
		if ($phpThumb->config_disable_onlycreateable_passthru || (function_exists($theImageCreateFunction) && ($dummyImage = @$theImageCreateFunction($SourceFilename)))) {

			// great
			if (@is_resource($dummyImage)) {
				unset($dummyImage);
			}

			if (headers_sent()) {
				$phpThumb->ErrorImage('Headers already sent ('.basename(__FILE__).' line '.__LINE__.')');
				exit;
			}
			if (@$_GET['phpThumbDebug']) {
				$phpThumb->DebugTimingMessage('skipped direct $SourceFilename passthru', __FILE__, __LINE__);
				$phpThumb->DebugMessage('Would have passed "'.$SourceFilename.'" through directly, but skipping due to phpThumbDebug', __FILE__, __LINE__);
				break;
			}

			SendSaveAsFileHeaderIfNeeded();
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', @filemtime($SourceFilename)).' GMT');
			if ($contentType = phpthumb_functions::ImageTypeToMIMEtype(@$phpThumb->getimagesizeinfo[2])) {
				header('Content-Type: '.$contentType);
			}
			@readfile($SourceFilename);
			exit;

		} else {
			$phpThumb->DebugMessage('Not passing "'.$SourceFilename.'" through directly because ($phpThumb->config_disable_onlycreateable_passthru = "'.$phpThumb->config_disable_onlycreateable_passthru.'") and '.$theImageCreateFunction.'() failed', __FILE__, __LINE__);
			break;
		}

	} else {
		$phpThumb->DebugMessage('Not passing "'.$SourceFilename.'" through directly because GetImageSize() failed', __FILE__, __LINE__);
		break;
	}
	break;
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$phpThumb->DebugTimingMessage('phpThumbDebug[5]', __FILE__, __LINE__);
if (@$_GET['phpThumbDebug'] == '5') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

function RedirectToCachedFile() {
	global $phpThumb, $PHPTHUMB_CONFIG;

	$nice_cachefile = str_replace(DIRECTORY_SEPARATOR, '/', $phpThumb->cache_filename);
	$nice_docroot   = str_replace(DIRECTORY_SEPARATOR, '/', rtrim($PHPTHUMB_CONFIG['document_root'], '/\\'));

	$parsed_url = phpthumb_functions::ParseURLbetter(@$_SERVER['HTTP_REFERER']);

	$nModified  = filemtime($phpThumb->cache_filename);

	if ($phpThumb->config_nooffsitelink_enabled && @$_SERVER['HTTP_REFERER'] && !in_array(@$parsed_url['host'], $phpThumb->config_nooffsitelink_valid_domains)) {

		$phpThumb->DebugMessage('Would have used cached (image/'.$phpThumb->thumbnailFormat.') file "'.$phpThumb->cache_filename.'" (Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT), but skipping because $_SERVER[HTTP_REFERER] ('.@$_SERVER['HTTP_REFERER'].') is not in $phpThumb->config_nooffsitelink_valid_domains ('.implode(';', $phpThumb->config_nooffsitelink_valid_domains).')', __FILE__, __LINE__);

	} elseif ($phpThumb->phpThumbDebug) {

		$phpThumb->DebugTimingMessage('skipped using cached image', __FILE__, __LINE__);
		$phpThumb->DebugMessage('Would have used cached file, but skipping due to phpThumbDebug', __FILE__, __LINE__);
		$phpThumb->DebugMessage('* Would have sent headers (1): Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT', __FILE__, __LINE__);
		if ($getimagesize = @GetImageSize($phpThumb->cache_filename)) {
			$phpThumb->DebugMessage('* Would have sent headers (2): Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($getimagesize[2]), __FILE__, __LINE__);
		}
		if (ereg('^'.preg_quote($nice_docroot).'(.*)$', $nice_cachefile, $matches)) {
			$phpThumb->DebugMessage('* Would have sent headers (3): Location: '.dirname($matches[1]).'/'.urlencode(basename($matches[1])), __FILE__, __LINE__);
		} else {
			$phpThumb->DebugMessage('* Would have sent data: readfile('.$phpThumb->cache_filename.')', __FILE__, __LINE__);
		}

	} else {

		if (headers_sent()) {
			$phpThumb->ErrorImage('Headers already sent ('.basename(__FILE__).' line '.__LINE__.')');
			exit;
		}
		SendSaveAsFileHeaderIfNeeded();

		header('Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT');
		if (@$_SERVER['HTTP_IF_MODIFIED_SINCE'] && ($nModified == strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) && @$_SERVER['SERVER_PROTOCOL']) {
			header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified');
			exit;
		}

		if ($getimagesize = @GetImageSize($phpThumb->cache_filename)) {
			header('Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($getimagesize[2]));
		} elseif (eregi('\.ico$', $phpThumb->cache_filename)) {
			header('Content-Type: image/x-icon');
		}
		if (!@$PHPTHUMB_CONFIG['cache_force_passthru'] && ereg('^'.preg_quote($nice_docroot).'(.*)$', $nice_cachefile, $matches)) {
			header('Location: '.dirname($matches[1]).'/'.urlencode(basename($matches[1])));
		} else {
			@readfile($phpThumb->cache_filename);
		}
		exit;

	}
	return true;
}

// check to see if file already exists in cache, and output it with no processing if it does
$phpThumb->SetCacheFilename();
if (@is_file($phpThumb->cache_filename)) {
	RedirectToCachedFile();
} else {
	$phpThumb->DebugMessage('Cached file "'.$phpThumb->cache_filename.'" does not exist, processing as normal', __FILE__, __LINE__);
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$phpThumb->DebugTimingMessage('phpThumbDebug[6]', __FILE__, __LINE__);
if (@$_GET['phpThumbDebug'] == '6') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

if ($phpThumb->rawImageData) {

	// great

} elseif (@$_GET['new']) {

	// generate a blank image resource of the specified size/background color/opacity
	if (($phpThumb->w <= 0) || ($phpThumb->h <= 0)) {
		$phpThumb->ErrorImage('"w" and "h" parameters required for "new"');
	}
	@list($bghexcolor, $opacity) = explode('|', $_GET['new']);
	if (!phpthumb_functions::IsHexColor($bghexcolor)) {
		$phpThumb->ErrorImage('BGcolor parameter for "new" is not valid');
	}
	$opacity = (strlen($opacity) ? $opacity : 100);
	if ($phpThumb->gdimg_source = phpthumb_functions::ImageCreateFunction($phpThumb->w, $phpThumb->h)) {
		$alpha = (100 - min(100, max(0, $opacity))) * 1.27;
		if ($alpha) {
			$phpThumb->setParameter('is_alpha', true);
			ImageAlphaBlending($phpThumb->gdimg_source, false);
			ImageSaveAlpha($phpThumb->gdimg_source, true);
		}
		$new_background_color = phpthumb_functions::ImageHexColorAllocate($phpThumb->gdimg_source, $bghexcolor, false, $alpha);
		ImageFilledRectangle($phpThumb->gdimg_source, 0, 0, $phpThumb->w, $phpThumb->h, $new_background_color);
	} else {
		$phpThumb->ErrorImage('failed to create "new" image ('.$phpThumb->w.'x'.$phpThumb->h.')');
	}

} elseif (!$phpThumb->src) {

	$phpThumb->ErrorImage('Usage: '.$_SERVER['PHP_SELF'].'?src=/path/and/filename.jpg'."\n".'read Usage comments for details');

} elseif (eregi('^(f|ht)tp\://', $phpThumb->src)) {

	$phpThumb->DebugMessage('$phpThumb->src ('.$phpThumb->src.') is remote image, attempting to download', __FILE__, __LINE__);
	if ($phpThumb->config_http_user_agent) {
		$phpThumb->DebugMessage('Setting "user_agent" to "'.$phpThumb->config_http_user_agent.'"', __FILE__, __LINE__);
		ini_set('user_agent', $phpThumb->config_http_user_agent);
	}
	$cleanedupurl = phpthumb_functions::CleanUpURLencoding($phpThumb->src);
	$phpThumb->DebugMessage('CleanUpURLencoding('.$phpThumb->src.') returned "'.$cleanedupurl.'"', __FILE__, __LINE__);
	$phpThumb->src = $cleanedupurl;
	unset($cleanedupurl);
	if ($rawImageData = phpthumb_functions::SafeURLread($phpThumb->src, $error, $phpThumb->config_http_fopen_timeout, $phpThumb->config_http_follow_redirect)) {
		$phpThumb->DebugMessage('SafeURLread('.$phpThumb->src.') succeeded'.($error ? ' with messsages: "'.$error.'"' : ''), __FILE__, __LINE__);
		$phpThumb->DebugMessage('Setting source data from URL "'.$phpThumb->src.'"', __FILE__, __LINE__);
		$phpThumb->setSourceData($rawImageData, urlencode($phpThumb->src));
	} else {
		$phpThumb->ErrorImage($error);
	}
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$phpThumb->DebugTimingMessage('phpThumbDebug[7]', __FILE__, __LINE__);
if (@$_GET['phpThumbDebug'] == '7') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

$phpThumb->GenerateThumbnail();

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$phpThumb->DebugTimingMessage('phpThumbDebug[8]', __FILE__, __LINE__);
if (@$_GET['phpThumbDebug'] == '8') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

if ($phpThumb->config_allow_parameter_file && $phpThumb->file) {

	$phpThumb->RenderToFile($phpThumb->ResolveFilenameToAbsolute($phpThumb->file));
	if ($phpThumb->config_allow_parameter_goto && $phpThumb->goto && eregi('^(f|ht)tps?://', $phpThumb->goto)) {
		// redirect to another URL after image has been rendered to file
		header('Location: '.$phpThumb->goto);
		exit;
	}

} elseif (@$PHPTHUMB_CONFIG['high_security_enabled'] && @$_GET['nocache']) {

	// cache disabled, don't write cachefile

} else {

	phpthumb_functions::EnsureDirectoryExists(dirname($phpThumb->cache_filename));
	if ((file_exists($phpThumb->cache_filename) && is_writable($phpThumb->cache_filename)) || is_writable(dirname($phpThumb->cache_filename))) {

		$phpThumb->CleanUpCacheDirectory();
		if ($phpThumb->RenderToFile($phpThumb->cache_filename) && is_readable($phpThumb->cache_filename)) {
			chmod($phpThumb->cache_filename, 0644);
			RedirectToCachedFile();
		} else {
			$phpThumb->DebugMessage('Failed: RenderToFile('.$phpThumb->cache_filename.')', __FILE__, __LINE__);
		}

	} else {

		$phpThumb->DebugMessage('Cannot write to $phpThumb->cache_filename ('.$phpThumb->cache_filename.') because that directory ('.dirname($phpThumb->cache_filename).') is not writable', __FILE__, __LINE__);

	}

}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$phpThumb->DebugTimingMessage('phpThumbDebug[9]', __FILE__, __LINE__);
if (@$_GET['phpThumbDebug'] == '9') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

if (!$phpThumb->OutputThumbnail()) {
	$phpThumb->ErrorImage('Error in OutputThumbnail():'."\n".$phpThumb->debugmessages[(count($phpThumb->debugmessages) - 1)]);
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
$phpThumb->DebugTimingMessage('phpThumbDebug[10]', __FILE__, __LINE__);
if (@$_GET['phpThumbDebug'] == '10') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

?>