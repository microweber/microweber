<?php
//////////////////////////////////////////////////////////////
///  phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// See: phpthumb.readme.txt for usage instructions          //
//      NOTE: THIS FILE HAS NO EFFECT IN OBJECT MODE!       //
//            THIS CONFIG FILE ONLY APPLIES TO phpThumb.php //
//                                                         ///
//////////////////////////////////////////////////////////////

ob_start();
if (!file_exists(dirname(__FILE__).'/phpthumb.functions.php') || !include_once(dirname(__FILE__).'/phpthumb.functions.php')) {
	ob_end_flush();
	die('failed to include_once(phpthumb.functions.php) - realpath="'.realpath(dirname(__FILE__).'/phpthumb.functions.php').'"');
}
ob_end_clean();

// START USER CONFIGURATION SECTION:

// * DocumentRoot configuration
// phpThumb() depends on $_SERVER['DOCUMENT_ROOT'] to resolve path/filenames. This value is usually correct,
// but has been known to be broken on some servers. This value allows you to override the default value.
// Do not modify from the auto-detect default value unless you are having problems.
//$PHPTHUMB_CONFIG['document_root'] = '/home/httpd/httpdocs';
//$PHPTHUMB_CONFIG['document_root'] = 'c:\\webroot\\example.com\\www';
//$PHPTHUMB_CONFIG['document_root'] = $_SERVER['DOCUMENT_ROOT'];
//$PHPTHUMB_CONFIG['document_root'] = realpath((@$_SERVER['DOCUMENT_ROOT'] && file_exists(@$_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])) ? $_SERVER['DOCUMENT_ROOT'] : str_replace(dirname(@$_SERVER['PHP_SELF']), '', str_replace(DIRECTORY_SEPARATOR, '/', realpath('.'))));
$PHPTHUMB_CONFIG['document_root'] = realpath((getenv('DOCUMENT_ROOT') && ereg('^'.preg_quote(realpath(getenv('DOCUMENT_ROOT'))), realpath(__FILE__))) ? getenv('DOCUMENT_ROOT') : str_replace(dirname(@$_SERVER['PHP_SELF']), '', str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__))));

// * Cache directory configuration (choose only one of these - leave the other lines commented-out):
// Note: this directory must be writable (usually chmod 777 is neccesary) for caching to work.
// If the directory is not writable no error will be generated but caching will be disabled.
$PHPTHUMB_CONFIG['cache_directory'] = dirname(__FILE__).'/../cache/';                            // set the cache directory relative to the phpThumb() installation
//$PHPTHUMB_CONFIG['cache_directory'] = $PHPTHUMB_CONFIG['document_root'].'/phpthumb/cache/'; // set the cache directory to an absolute directory for all source images
//$PHPTHUMB_CONFIG['cache_directory'] = './cache/';                                           // set the cache directory relative to the source image - must start with '.' (will not work to cache URL- or database-sourced images, please use an absolute directory name)
//$PHPTHUMB_CONFIG['cache_directory'] = null;                                                 // disable thumbnail caching (not recommended)
//if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
//	$PHPTHUMB_CONFIG['cache_directory'] = dirname(__FILE__).'/cache/'; // set the cache directory to an absolute directory for all source images
//} else {
//	$PHPTHUMB_CONFIG['cache_directory'] = '/tmp/persistent/phpthumb/cache/';
//}

$PHPTHUMB_CONFIG['cache_disable_warning'] = false; // If [cache_directory] is non-existant or not writable, and [cache_disable_warning] is false, an error image will be generated warning to either set the cache directory or disable the warning (to avoid people not knowing about the cache)

$PHPTHUMB_CONFIG['cache_directory_depth'] = 4; // If this larger than zero, cache structure will be broken into a broad directory structure based on cache filename. For example "cache_src012345..." will be stored in "/0/01/012/0123/cache_src012345..." when (cache_directory_depth = 4)


// * Cache culling: phpThumb can automatically limit the contents of the cache directory
//   based on last-access date and/or number of files and/or total filesize.

//$PHPTHUMB_CONFIG['cache_maxage'] = null;            // never delete cached thumbnails based on last-access time
$PHPTHUMB_CONFIG['cache_maxage'] = 86400 * 1;        // delete cached thumbnails that haven't been accessed in more than [30 days] (value is maximum time since last access in seconds to avoid deletion)

//$PHPTHUMB_CONFIG['cache_maxsize'] = null;           // never delete cached thumbnails based on byte size of cache directory
$PHPTHUMB_CONFIG['cache_maxsize'] = 3 * 1024 * 1024; // delete least-recently-accessed cached thumbnails when more than [10MB] of cached files are present (value is maximum bytesize of all cached files)

//$PHPTHUMB_CONFIG['cache_maxfiles'] = null;          // never delete cached thumbnails based on number of cached files
$PHPTHUMB_CONFIG['cache_maxfiles'] = 100;             // delete least-recently-accessed cached thumbnails when more than [200] cached files are present (value is maximum number of cached files to keep)


// * Source image cache configuration
$PHPTHUMB_CONFIG['cache_source_enabled']   = false;                               // if true, source images obtained via HTTP are cached to $PHPTHUMB_CONFIG['cache_source_directory']
$PHPTHUMB_CONFIG['cache_source_directory'] = dirname(__FILE__).'/../cache/source/';  // set the cache directory for unprocessed source images

// * cache source modification date configuration
$PHPTHUMB_CONFIG['cache_source_filemtime_ignore_local']  = false; // if true, local source images will not be checked for modification date and cached image will be used if available, even if source image is changed or removed
$PHPTHUMB_CONFIG['cache_source_filemtime_ignore_remote'] = true;  // if true, remote source images will not be checked for modification date and cached image will be used if available, even if source image is changed or removed. WARNING: cached performance MUCH slower if this is set to false.


// * Simplified cache filename configuration
// Instead of creating unique cache filenames for all parameter combinations, create "simple" cache files (eg: "pic_thumb.jpg")
// If cache_default_only_suffix is non-empty, GETstring parameters (except 'src') are ignored and only $PHPTHUMB_DEFAULTS
// parameters (set at the bottom of phpThumb.config.php) are used for processing.
// The '*' character MUST be used to represent the source image name
$PHPTHUMB_CONFIG['cache_default_only_suffix'] = '';           // cached in normal phpThumb manner
//$PHPTHUMB_CONFIG['cache_default_only_suffix'] = '*_thumb';  // cache 'pic.jpg' becomes 'pic_thumb.jpg' (or 'pic_thumb.png' if PNG output is selected, etc)
//$PHPTHUMB_CONFIG['cache_default_only_suffix'] = 'small-*';  // cache 'pic.jpg' becomes 'small-pic.jpg' (or 'small-pic.png' if PNG output is selected, etc)

$PHPTHUMB_CONFIG['cache_prefix'] = 'phpThumb_cache_'.str_replace('www.', '', @$_SERVER['SERVER_NAME']);
//$PHPTHUMB_CONFIG['cache_prefix'] = 'phpThumb_cache';                         // allow phpThumb to share 1 set of cached files even if accessed under different servername/domains on same server

$PHPTHUMB_CONFIG['cache_force_passthru'] = true;  // if true, cached image data will always be passed to browser; if false, HTTP redirect will be used instead



// * Temp directory configuration
// phpThumb() may need to create temp files. Usually the system temp dir is writable and can be used.
// Leave this value as NULL in most cases. If you get errors about "failed to open <filename> for writing"
// you should change this to a full pathname to a directory you do have write access to.
//$PHPTHUMB_CONFIG['temp_directory'] = null;                               // attempt to auto-detect
//$PHPTHUMB_CONFIG['temp_directory'] = '/tmp/persistent/phpthumb/cache/';  // set to absolute path
$PHPTHUMB_CONFIG['temp_directory'] = $PHPTHUMB_CONFIG['cache_directory'];  // set to same as cache directory


// NOTE: "max_source_pixels" only affects GD-resized thumbnails. If you have ImageMagick
//       installed it will bypass most of these limits
// maximum number of pixels in source image to attempt to process entire image in GD mode.
// If this is zero then no limit on source image dimensions.
// If this is nonzero then this is the maximum number of pixels the source image
// can have to be processed normally, otherwise the embedded EXIF thumbnail will
// be used (if available) or an "image too large" notice will be displayed.
// This is to be used for large source images (> 1600x1200) and low PHP memory
// limits. If PHP runs out of memory the script will usually just die with no output.
// To calculate this number, multiply the dimensions of the largest image
// you can process with your memory limitation (e.g. 1600 * 1200 = 1920000)
// As a general guideline, this number will be about 20% of your PHP memory
// configuration, so 8M = 1,677,722; 16M = 3,355,443; 32M = 6,710,886; etc.
if (phpthumb_functions::version_compare_replacement(phpversion(), '4.3.2', '>=') && !defined('memory_get_usage') && !@ini_get('memory_limit')) {
	// memory_get_usage() will only be defined if your PHP is compiled with the --enable-memory-limit configuration option.
	$PHPTHUMB_CONFIG['max_source_pixels'] = 0;         // no memory limit
} else {
	// calculate default max_source_pixels as 1/6 of memory limit configuration
	$PHPTHUMB_CONFIG['max_source_pixels'] = round(max(intval(ini_get('memory_limit')), intval(get_cfg_var('memory_limit'))) * 1048576 / 6);
	//$PHPTHUMB_CONFIG['max_source_pixels'] = 0;       // no memory limit
	//$PHPTHUMB_CONFIG['max_source_pixels'] = 1920000; // allow 1600x1200 images (2Mpx), no larger (about 12MB memory required)
	//$PHPTHUMB_CONFIG['max_source_pixels'] = 2795000; // 16MB memory limit
	//$PHPTHUMB_CONFIG['max_source_pixels'] = 3871488; // allow 2272x1704 images (4Mpx), no larger (about 24MB memory required)
}


// ImageMagick configuration
$PHPTHUMB_CONFIG['prefer_imagemagick']        = true;  // If true, use ImageMagick to resize thumbnails if possible, since it is usually faster than GD functions; if false only use ImageMagick if PHP memory limit is too low.
$PHPTHUMB_CONFIG['imagemagick_use_thumbnail'] = true;  // If true, use ImageMagick's "-thumbnail" resizing parameter (if available) which removes extra non-image metadata (profiles, EXIF info, etc) resulting in much smaller filesize; if false, use "-resize" paramter which retains this info
if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
	// Windows: set absolute pathname
	$PHPTHUMB_CONFIG['imagemagick_path'] = 'C:/ImageMagick/convert.exe';
} else {
	// *nix: set absolute pathname to "convert", or leave as null if "convert" is in the path (location detected with `which`)
	//$PHPTHUMB_CONFIG['imagemagick_path'] = '/usr/local/bin/convert';
	$PHPTHUMB_CONFIG['imagemagick_path'] = null;
}



// * Default output configuration:
$PHPTHUMB_CONFIG['output_format']    = 'png'; // default output format ('jpeg', 'png' or 'gif') - thumbnail will be output in this format (if available in your version of GD or ImageMagick). This is only used if the "f" parameter is not specified, and if the thumbnail can't be output in the input format.
$PHPTHUMB_CONFIG['output_maxwidth']  = 0;      // default maximum thumbnail width.  If this is zero then default width  is the width  of the source image. This is always overridden by ?w=___ GETstring parameter
$PHPTHUMB_CONFIG['output_maxheight'] = 0;      // default maximum thumbnail height. If this is zero then default height is the height of the source image. This is always overridden by ?h=___ GETstring parameter
$PHPTHUMB_CONFIG['output_interlace'] = true;   // if true: interlaced output for GIF/PNG, progressive output for JPEG; if false: non-interlaced for GIF/PNG, baseline for JPEG.

// * Error message configuration
$PHPTHUMB_CONFIG['error_image_width']           = 400;      // default width for error images
$PHPTHUMB_CONFIG['error_image_height']          = 100;      // default height for error images
$PHPTHUMB_CONFIG['error_message_image_default'] = '';       // Set this to the name of a generic error image (e.g. '/images/error.png') that you want displayed in place of any error message that may occur. This setting is overridden by the 'err' parameter, which does the same thing.
$PHPTHUMB_CONFIG['error_bgcolor']               = 'CCCCFF'; // background color of error message images
$PHPTHUMB_CONFIG['error_textcolor']             = 'FF0000'; // color of text in error messages
$PHPTHUMB_CONFIG['error_fontsize']              = 1;        // size of text in error messages, from 1 (smallest) to 5 (largest)
$PHPTHUMB_CONFIG['error_die_on_error']          = true;     // die with error message on any fatal error (recommended with standalone phpThumb.php)
$PHPTHUMB_CONFIG['error_silent_die_on_error']   = false;    // simply die with no output of any kind on fatal errors (not recommended)
$PHPTHUMB_CONFIG['error_die_on_source_failure'] = true;     // die with error message if source image cannot be processed by phpThumb() (usually because source image is corrupt in some way). If false the source image will be passed through unprocessed, if true (default) an error message will be displayed.

// * Off-server Thumbnailing Configuration:
$PHPTHUMB_CONFIG['nohotlink_enabled']           = false;                                    // If false will allow thumbnailing from any source domain
$PHPTHUMB_CONFIG['nohotlink_valid_domains']     = array(@$_SERVER['HTTP_HOST']);            // This is the list of domains for which thumbnails are allowed to be created. Note: domain only, do not include port numbers. The default value of the current domain should be fine in most cases, but if neccesary you can add more domains in here, in the format "www.example.com"
$PHPTHUMB_CONFIG['nohotlink_erase_image']       = true;                                     // if true thumbnail is covered up with $PHPTHUMB_CONFIG['nohotlink_fill_color'] before text is applied, if false text is written over top of thumbnail
$PHPTHUMB_CONFIG['nohotlink_text_message']      = 'Off-server thumbnailing is not allowed'; // text of error message

// * Off-server Linking Configuration:
$PHPTHUMB_CONFIG['nooffsitelink_enabled']       = true;                                       // If false will allow thumbnails to be linked to from any domain, if true only domains listed below in 'nooffsitelink_valid_domains' will be allowed.
$PHPTHUMB_CONFIG['nooffsitelink_valid_domains'] = array(@$_SERVER['HTTP_HOST']);              // This is the list of domains for which thumbnails are allowed to be created. The default value of the current domain should be fine in most cases, but if neccesary you can add more domains in here, in the format 'www.example.com'
$PHPTHUMB_CONFIG['nooffsitelink_require_refer'] = false;                                      // If false will allow standalone calls to phpThumb(). If true then only requests with a $_SERVER['HTTP_REFERER'] value in 'nooffsitelink_valid_domains' are allowed.
$PHPTHUMB_CONFIG['nooffsitelink_erase_image']   = false;                                      // if true thumbnail is covered up with $PHPTHUMB_CONFIG['nohotlink_fill_color'] before text is applied, if false text is written over top of thumbnail
$PHPTHUMB_CONFIG['nooffsitelink_watermark_src'] = '/demo/images/watermark.png';                // webroot-relative image to overlay on hotlinked images
$PHPTHUMB_CONFIG['nooffsitelink_text_message']  = 'Image taken from '.@$_SERVER['HTTP_HOST']; // text of error message (used if [nooffsitelink_watermark_src] is not a valid image)


// * Border & Background default colors
$PHPTHUMB_CONFIG['border_hexcolor']     = '000000'; // Default border color - usual HTML-style hex color notation (overidden with 'bc' parameter)
$PHPTHUMB_CONFIG['background_hexcolor'] = 'FFFFFF'; // Default background color when thumbnail aspect ratio does not match fixed-dimension box - usual HTML-style hex color notation (overridden with 'bg' parameter)

// * Watermark configuration
$PHPTHUMB_CONFIG['ttf_directory'] = dirname(__FILE__).'/fonts'; // Base directory for TTF font files
//$PHPTHUMB_CONFIG['ttf_directory'] = 'c:/windows/fonts';


// * MySQL configuration
// You may want to pull data from a database rather than a physical file
// If so, modify the $PHPTHUMB_CONFIG['mysql_query'] line to suit your database structure
// Note: the data retrieved must be the actual binary data of the image, not a URL or filename

$PHPTHUMB_CONFIG['mysql_query'] = '';
//$PHPTHUMB_CONFIG['mysql_query'] = 'SELECT `picture` FROM `products` WHERE (`id` = \''.mysql_escape_string(@$_GET['id']).'\')';

// These 4 values must be modified if $PHPTHUMB_CONFIG['mysql_query'] is not empty, but may be ignored if $PHPTHUMB_CONFIG['mysql_query'] is blank.
$PHPTHUMB_CONFIG['mysql_hostname'] = 'localhost';
$PHPTHUMB_CONFIG['mysql_username'] = '';
$PHPTHUMB_CONFIG['mysql_password'] = '';
$PHPTHUMB_CONFIG['mysql_database'] = '';


// * Security configuration
$PHPTHUMB_CONFIG['high_security_enabled']    = false;  // if enabled, requires 'high_security_password' set to at least 5 characters, and requires the use of phpThumbURL() function (at the bottom of phpThumb.config.php) to generate hashed URLs
$PHPTHUMB_CONFIG['high_security_password']   = '';     // required if 'high_security_enabled' is true, must be at least 5 characters long
$PHPTHUMB_CONFIG['disable_debug']            = false;  // prevent phpThumb from displaying any information about your system. If true, phpThumbDebug and error messages will be disabled
$PHPTHUMB_CONFIG['allow_src_above_docroot']  = false;  // if true, allow src to be anywhere in filesystem; if false (default) only allow src within document_root
$PHPTHUMB_CONFIG['allow_src_above_phpthumb'] = true;   // if true (default), allow src to be anywhere in filesystem; if false only allow src within sub-directory of phpThumb installation
$PHPTHUMB_CONFIG['allow_parameter_file']     = false;  // if true, allow use of 'file' parameter; if false (default) the 'file' parameter is disabled/ignored
$PHPTHUMB_CONFIG['allow_parameter_goto']     = false;  // if true, allow use of 'goto' parameter; if false (default) the 'goto' parameter is disabled/ignored


// * HTTP UserAgent configuration
//$PHPTHUMB_CONFIG['http_user_agent'] = '';                                                                                      // PHP default: none
//$PHPTHUMB_CONFIG['http_user_agent'] = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';                                    // Windows XP, Internet Explorer
$PHPTHUMB_CONFIG['http_user_agent'] = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7'; // Windows XP, Firefox


// * Compatability settings
$PHPTHUMB_CONFIG['disable_pathinfo_parsing']        = false;  // if true, $_SERVER[PATH_INFO] is not parsed. May be needed on some server configurations to allow normal behavior.
$PHPTHUMB_CONFIG['disable_imagecopyresampled']      = false;  // if true, ImageCopyResampled is replaced with ImageCopyResampleBicubic. May be needed for buggy versions of PHP-GD.
$PHPTHUMB_CONFIG['disable_onlycreateable_passthru'] = true;   // if true, any image that can be parsed by GetImageSize() can be passed through; if false, only images that can be converted to GD by ImageCreateFrom(JPEG|GIF|PNG) functions are allowed


// * HTTP remote file opening settings
$PHPTHUMB_CONFIG['http_fopen_timeout']              = 10;   // timeout (in seconds) for fopen / curl / fsockopen
$PHPTHUMB_CONFIG['http_follow_redirect']            = true; // if true (default), follow "302 Found" redirects to new URL; if false, return error message


// * Speed optimizations configuration
$PHPTHUMB_CONFIG['use_exif_thumbnail_for_speed'] = false; // If true, and EXIF thumbnail is available, and is larger or equal to output image dimensions, use EXIF thumbnail rather than actual source image for generating thumbnail. Benefit is only speed, avoiding resizing large image.
$PHPTHUMB_CONFIG['allow_local_http_src']         = false; // If true, 'src' parameter can be "http://<thishostname>/path/image.ext" instead of just "/path/image.ext"; if false then display warning message to encourage more efficient local-filename calling.

// END USER CONFIGURATION SECTION

///////////////////////////////////////////////////////////////////////////////

// START DEFAULT PARAMETERS SECTION
// If any parameters are constant across ALL images, you can set them here

$PHPTHUMB_DEFAULTS_GETSTRINGOVERRIDE = true;  // if true, any parameters in the URL will override the defaults set here; if false, any parameters set here cannot be overridden in the URL
$PHPTHUMB_DEFAULTS_DISABLEGETPARAMS  = false; // if true, GETstring parameters will be ignored (except for 'src') and only below default parameters will be used; if false, both default and GETstring parameters will be used (depending on $PHPTHUMB_DEFAULTS_GETSTRINGOVERRIDE). Will be auto-set true if !empty($PHPTHUMB_CONFIG['cache_default_only_suffix'])

//$PHPTHUMB_DEFAULTS['w']    = 200;
//$PHPTHUMB_DEFAULTS['fltr'] = array('blur|10');
//$PHPTHUMB_DEFAULTS['q']    =  90;


// END DEFAULT PARAMETERS SECTION



///////////////////////////////////////////////////////////////////////////////
// Function for generating hashed calls to phpThumb if 'high_security_enabled'
// example:
//   require_once($_SERVER['DOCUMENT_ROOT'].'/phpThumb/phpThumb.config.php');
//   echo '<img src="'.phpThumbURL('src=/images/pic.jpg&w=50').'">';

function phpThumbURL($ParameterString) {
	global $PHPTHUMB_CONFIG;
	return str_replace(@$PHPTHUMB_CONFIG['document_root'], '', dirname(__FILE__)).DIRECTORY_SEPARATOR.'phpThumb.php?'.$ParameterString.'&hash='.md5($ParameterString.@$PHPTHUMB_CONFIG['high_security_password']);
}

///////////////////////////////////////////////////////////////////////////////

?>