<?php

define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));


if(!strstr(INI_SYSTEM_CHECK_DISABLED,'ini_set')){
ini_set("memory_limit", "160M");
ini_set("set_time_limit",0);
}

if(!strstr(INI_SYSTEM_CHECK_DISABLED,'date_default_timezone_set')){
date_default_timezone_set('America/Los_Angeles');
}

 
 
/*
   * @return string
   * @param string $url
   * @desc Return string content from a remote file
   * @author Luiz Miguel Axcar (lmaxcar@yahoo.com.br)
*/

function get_content_url($url)
{
    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_HEADER, 0);

    ob_start();

    curl_exec ($ch);
    curl_close ($ch);
    $string = ob_get_contents();

    ob_end_clean();
    
    return $string;     
}


function getfile($requestUrl, $save_to_file = false) {
	
	if(function_exists('curl_init')){
		$result =  get_content_url($requestUrl);
	} else {
		$opts = array('http' => array('method' => 'POST', 'header' => "User-Agent: Microweber/Web Install" . "\r\n" . 'Content-type: application/x-www-form-urlencoded' . "\r\n"));
	$requestUrl = str_replace(' ', '%20', $requestUrl);
	$context = stream_context_create($opts);

	$result = file_get_contents($requestUrl, false, $context);
	}

	
	if ($save_to_file == true) {
		//  d($result);
		file_put_contents($save_to_file, $result);
	} else {
		return $result;
	}

	//..file_put_contents($dir . substr($url, strrpos($url, '/'), strlen($url)), file_get_contents($url));
}

$y = site_url();

$y = str_replace(basename(__FILE__), '', $y);
$y = str_replace('?/', '', $y);

 //$y = str_replace('//', '/', $y);

$do = false;
$done = false;
if (isset($_REQUEST['action'])) {
	$do = $_REQUEST['action'];
}
$dir = dirname(__FILE__);
switch ($do) {

	case 'download' :
	case 'download_and_unzip' :

	$latest_url = "http://api.microweber.net/service/update/?api_function=latest";
	$latest_url = getfile($latest_url);
	if ($latest_url != false) {
		$latest_url = json_decode($latest_url, 1);
	}
	if ($latest_url != false and isset($latest_url['core_update'])) {
		$url = $latest_url['core_update'];

		$fn = ($dir . DIRECTORY_SEPARATOR . 'mw-latest.zip');
		getfile($url, $fn);

	}

	if($do == 'download_and_unzip'){
	header('Location: '.$y.basename(__FILE__).'?action=unzip');
	exit();

	}



	break;

	case 'unzip' :
	
	
	if(!strstr(INI_SYSTEM_CHECK_DISABLED,'set_time_limit')){
	set_time_limit(0);
	}


	
	$dir = dirname(__FILE__);
	$fn = ('mw-latest.zip');

	$zip_dir = basename('mw-latest.zip');
		//get filename without extension fpr directory creation

	$unzip = new Unzip();

	$unzip -> extract($dir . DIRECTORY_SEPARATOR . $fn);

	$done = true;
		///   unlink($upload_dir . '/' . $filename); //delete uploaded file
		//        $zip = new ZipArchive;
		//        $res = $zip->open('mw-latest.zip');
		//        if ($res === TRUE) {
		//            $zip->extractTo('/');
		//            $zip->close();
		//            echo 'ok’';
		//        } else {
		//              echo 'failed';
		//        }
	break;


	default :


	break;
}




?>
<?php if ($done == false):

$check_pass = true;
$server_check_errors = array();

if (version_compare(phpversion(), "5.3.0", "<=")) {
	$check_pass = false;
	$server_check_errors['php_version'] = 'You must run PHP 5.3 or greater';
}
if (!ini_get('allow_url_fopen')) {
	$check_pass = false;

	$server_check_errors['allow_url_fopen'] =  'You must enable allow_url_fopen from php.ini';
}
$here = dirname(__FILE__).DIRECTORY_SEPARATOR.uniqid();
if (is_writable($here)) {
	$check_pass = false;

	$server_check_errors['not_wrtiable'] =  'The current directory is not writable';
}
/*if (!ini_get('short_open_tag')) {
	$check_pass = false;

	$server_check_errors['short_open_tag'] =  'You must enable short_open_tag from php.ini';
}*/

if(function_exists('apache_get_modules') ){


	 if(!in_array('mod_rewrite',apache_get_modules())){
	 	$check_pass = false;
		$server_check_errors['mod_rewrite'] =  'mod_rewrite is not enabled on your server';
	 }
}




?>

<!DOCTYPE HTML>
<html>
<head>
<title>Welcome to Microweber Web Install</title>

<link rel="stylesheet" type="text/css" href="http://microweber.net/webinstall/style.css" />

</head>

<body>

<div class="box-holder">

    <a href="http://microweber.net" target="_blank" id="logo"><img src="//microweber.net/webinstall/logo.png" alt="Microweber" /></a>

    <span class="Beta">Beta Version</span>

    <div class="vSpace"></div>

<div class="box">



<form name="installer">
  <?php if($check_pass == false): ?>
  <?php if(!empty($server_check_errors)): ?>
  <h3>Server check</h3>
  <h4>There are some errors on your server that will prevent Microweber from working properly</h4>
    <ol class="error">
    <?php foreach($server_check_errors as $server_check_error): ?>
    <li>
      <?php print $server_check_error; ?>
    </li>
    <?php endforeach ?>
    </ol>
  <?php endif; ?>
  <?php else: ?>
  <h2>Welcome to Microweber Web Install</h2>
  <p>This file will download the latest version and redirect you to the install page.</p>


  <!--
  <input type="radio" name="action" value="download">

  <input type="radio" name="action" value="unzip">
     <input type="radio" name="action"  value="download_and_unzip">-->

  <input type="hidden" name="action"  value="download_and_unzip">
  <input type="submit" name="submit" value="Download and install Microweber">

  <p class="agreement"> By downloading and installing Microweber you agree to the
  <a href="http://microweber.net/license" id="license">License Agreement</a> </p>

  <iframe id="license_text" frameborder="0" scrolling="auto" style="display: none;"></iframe>

  <script>
    var doc = document,
        link = doc.getElementById('license'),
        frame = doc.getElementById('license_text');
    lactivated = false;
    link.onclick = function(){
        if(!lactivated){
           lactivated = true;
           frame.src = this.href;
        }
        if(frame.style.display == 'none'){
            frame.style.display = 'block';
        }
        else{
          frame.style.display = 'none';
        }
        return false;
    }

    doc.forms['installer'].onsubmit = function(){
      doc.querySelector('.box').className += ' installing';
    }

  </script>

  <div class="preloader"><span>Installing</span></div>

  <?php endif; ?>
</form>



<?php else: ?>



<script> window.location.href = "index.php"; </script>

<?php unlink(__FILE__); ?>
<?php endif; ?>
<?php

function site_url($add_string = false) {
	static $u1;
	if ($u1 == false) {
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) and ($_SERVER["HTTPS"] == "on")) {
			$pageURL .= "s";
		}

		$subdir_append = false;
		if (isset($_SERVER['PATH_INFO'])) {
			// $subdir_append = $_SERVER ['PATH_INFO'];
		} else {
			$subdir_append = $_SERVER['REQUEST_URI'];
		}

		//  var_dump($_SERVER);
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"];
		}
		$pageURL_host = $pageURL;
		$pageURL .= $subdir_append;
		if (isset($_SERVER['SCRIPT_NAME'])) {
			$d = dirname($_SERVER['SCRIPT_NAME']);
			$d = trim($d, '/');
		}

		if (isset($_SERVER['QUERY_STRING'])) {
			$pageURL = str_replace($_SERVER['QUERY_STRING'], '', $pageURL);
		}

		//$url_segs1 = str_replace($pageURL_host, '',$pageURL);
		$url_segs = explode('/', $pageURL);
		$i = 0;
		$unset = false;
		foreach ($url_segs as $v) {
			if ($unset == true) {
				//unset($url_segs [$i]);
			}
			if ($v == $d) {

				$unset = true;
			}

			$i++;
		}
		$url_segs[] = '';
		$u1 = implode('/', $url_segs);
	}
	return $u1 . $add_string;
}

/**
 * UnZip Class
 *
 * This class is based on a library I found at PHPClasses:
 * http://phpclasses.org/package/2495-PHP-Pack-and-unpack-files-packed-in-ZIP-archives.html
 * so I
 * refactored it and added several additional methods -- Phil Sturgeon
 *
 * This class requires extension ZLib Enabled.
 *
 * @author		Alexandre Tedeschi
 * @author		Phil Sturgeon
 * @link		http://bitbucket.org/philsturgeon/codeigniter-unzip
 * @license        http://www.gnu.org/licenses/lgpl.html
 * @version     1.0.0
 */
class Unzip {

	private $compressed_list = array();
	// List of files in the ZIP
	private $central_dir_list = array();
	// Central dir list... It's a kind of 'extra attributes' for a set of files
	private $end_of_central = array();
	// End of central dir, contains ZIP Comments
	private $info = array();
	private $error = array();
	private $_zip_file = '';
	private $_target_dir = FALSE;
	private $apply_chmod = 0755;
	private $fh;
	private $zip_signature = "\x50\x4b\x03\x04";
	// local file header signature
	private $dir_signature = "\x50\x4b\x01\x02";
	// central dir header signature
	private $central_signature_end = "\x50\x4b\x05\x06";
	// ignore these directories (useless meta data)
	private $_skip_dirs = array('__MACOSX');
	// Rename target files with underscore case
	private $underscore_case = TRUE;
	private $_allow_extensions = NULL;
	// What is allowed out of the zip

	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @access    Public
	 * @param     string
	 * @return    none
	 */
	function __construct() {

	}

	// --------------------------------------------------------------------

	/**
	 * Unzip all files in archive.
	 *
	 * @access    Public
	 * @param     none
	 * @return    none
	 */
	public function extract($zip_file, $target_dir = NULL, $preserve_filepath = TRUE) {
		$this -> _zip_file = $zip_file;
		$this -> _target_dir = $target_dir ? $target_dir : dirname($this -> _zip_file);

		if (!$files = $this -> _list_files()) {
			$this -> set_error('ZIP folder was empty.');
			return FALSE;
		}

		$file_locations = array();
		foreach ($files as $file => $trash) {
			$dirname = pathinfo($file, PATHINFO_DIRNAME);
			$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

			$folders = explode('/', $dirname);
			$out_dn = $this -> _target_dir . '/' . $dirname;

			// Skip stuff in stupid folders
			if (in_array(current($folders), $this -> _skip_dirs)) {
				continue;
			}

			// Skip any files that are not allowed
			if (is_array($this -> _allow_extensions) && $extension && !in_array($extension, $this -> _allow_extensions)) {
				continue;
			}

			if (!is_dir($out_dn) && $preserve_filepath) {
				$str = "";
				foreach ($folders as $folder) {
					$str = $str ? $str . '/' . $folder : $folder;
					if (!is_dir($this -> _target_dir . '/' . $str)) {
						$this -> set_debug('Creating folder: ' . $this -> _target_dir . '/' . $str);

						if (!@mkdir($this -> _target_dir . '/' . $str)) {
							$this -> set_error('Desitnation path is not writable.');
							return FALSE;
						}

						// Apply chmod if configured to do so
						$this -> apply_chmod && chmod($this -> _target_dir . '/' . $str, $this -> apply_chmod);
					}
				}
			}

			if (substr($file, -1, 1) == '/') {
				continue;
			}

			$file_locations[] = $file_location = $this -> _target_dir . '/' . ($preserve_filepath ? $file : basename($file));

			$this -> _extract_file($file, $file_location, $this -> underscore_case);
		}

		return $file_locations;
	}

	// --------------------------------------------------------------------

	/**
	 * What extensions do we want out of this ZIP
	 *
	 * @access    Public
	 * @param     none
	 * @return    none
	 */
	public function allow($ext = NULL) {
		$this -> _allow_extensions = $ext;
	}

	// --------------------------------------------------------------------

	/**
	 * Show error messages
	 *
	 * @access    public
	 * @param    string
	 * @return    string
	 */
	public function error_string($open = '<p>', $close = '</p>') {
		return $open . implode($close . $open, $this -> error) . $close;
	}

	// --------------------------------------------------------------------

	/**
	 * Show debug messages
	 *
	 * @access    public
	 * @param    string
	 * @return    string
	 */
	public function debug_string($open = '<p>', $close = '</p>') {
		return $open . implode($close . $open, $this -> info) . $close;
	}

	// --------------------------------------------------------------------

	/**
	 * Save errors
	 *
	 * @access    Private
	 * @param    string
	 * @return    none
	 */
	function set_error($string) {
		$this -> error[] = $string;
	}

	// --------------------------------------------------------------------

	/**
	 * Save debug data
	 *
	 * @access    Private
	 * @param    string
	 * @return    none
	 */
	function set_debug($string) {
		$this -> info[] = $string;
	}

	// --------------------------------------------------------------------

	/**
	 * List all files in archive.
	 *
	 * @access    Public
	 * @param     boolean
	 * @return    mixed
	 */
	private function _list_files($stop_on_file = FALSE) {
		if (sizeof($this -> compressed_list)) {
			$this -> set_debug('Returning already loaded file list.');
			return $this -> compressed_list;
		}

		// Open file, and set file handler
		$fh = fopen($this -> _zip_file, 'r');
		$this -> fh = &$fh;

		if (!$fh) {
			$this -> set_error('Failed to load file: ' . $this -> _zip_file);
			return FALSE;
		}

		$this -> set_debug('Loading list from "End of Central Dir" index list...');

		if (!$this -> _load_file_list_by_eof($fh, $stop_on_file)) {
			$this -> set_debug('Failed! Trying to load list looking for signatures...');

			if (!$this -> _load_files_by_signatures($fh, $stop_on_file)) {
				$this -> set_debug('Failed! Could not find any valid header.');
				$this -> set_error('ZIP File is corrupted or empty');

				return FALSE;
			}
		}

		return $this -> compressed_list;
	}

	// --------------------------------------------------------------------

	/**
	 * Unzip file in archive.
	 *
	 * @access    Public
	 * @param     string, boolean, boolean
	 * @return    Unziped file.
	 */
	private function _extract_file($compressed_file_name, $target_file_name = FALSE, $underscore_case = FALSE) {
		if (!sizeof($this -> compressed_list)) {
			$this -> set_debug('Trying to unzip before loading file list... Loading it!');
			$this -> _list_files(FALSE, $compressed_file_name);
		}

		$fdetails = &$this -> compressed_list[$compressed_file_name];

		if (!isset($this -> compressed_list[$compressed_file_name])) {
			$this -> set_error('File "<strong>$compressed_file_name</strong>" is not compressed in the zip.');
			return FALSE;
		}

		if (substr($compressed_file_name, -1) == '/') {
			$this -> set_error('Trying to unzip a folder name "<strong>$compressed_file_name</strong>".');
			return FALSE;
		}

		if (!$fdetails['uncompressed_size']) {
			$this -> set_debug('File "<strong>$compressed_file_name</strong>" is empty.');

			return $target_file_name ? file_put_contents($target_file_name, '') : '';
		}

		if ($underscore_case) {
			$pathinfo = pathinfo($target_file_name);

			if (!isset($pathinfo['extension'])) {
				$pathinfo['extension'] = '';
			}
//$pathinfo['filename_new'] = preg_replace('/([^.a-z0-9]+)/i', '_', strtolower($pathinfo['filename']));
			$pathinfo['filename_new'] = $pathinfo['filename'];
			$target_file_name = $pathinfo['dirname'] . '/' . $pathinfo['filename_new'] . '.' . strtolower($pathinfo['extension']);
		}

		fseek($this -> fh, $fdetails['contents_start_offset']);
		$ret = $this -> _uncompress(fread($this -> fh, $fdetails['compressed_size']), $fdetails['compression_method'], $fdetails['uncompressed_size'], $target_file_name);

		if ($this -> apply_chmod && $target_file_name) {
			chmod($target_file_name, 0755);
		}

		return $ret;
	}

	// --------------------------------------------------------------------

	/**
	 * Free the file resource.
	 *
	 * @access    Public
	 * @param     none
	 * @return    none
	 */
	public function close() {
		// Free the file resource
		if ($this -> fh) {
			fclose($this -> fh);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Free the file resource Automatic destroy.
	 *
	 * @access    Public
	 * @param     none
	 * @return    none
	 */
	public function __destroy() {
		$this -> close();
	}

	// --------------------------------------------------------------------

	/**
	 * Uncompress file. And save it to the targetFile.
	 *
	 * @access    Private
	 * @param     Filecontent, int, int, boolean
	 * @return    none
	 */
	private function _uncompress($content, $mode, $uncompressed_size, $target_file_name = FALSE) {
		switch ($mode) {
			case 0 :
			return $target_file_name ? file_put_contents($target_file_name, $content) : $content;
			case 1 :
			$this -> set_error('Shrunk mode is not supported... yet?');
			return FALSE;
			case 2 :
			case 3 :
			case 4 :
			case 5 :
			$this -> set_error('Compression factor ' . ($mode - 1) . ' is not supported... yet?');
			return FALSE;
			case 6 :
			$this -> set_error('Implode is not supported... yet?');
			return FALSE;
			case 7 :
			$this -> set_error('Tokenizing compression algorithm is not supported... yet?');
			return FALSE;
			case 8 :
				// Deflate
			return $target_file_name ? file_put_contents($target_file_name, gzinflate($content, $uncompressed_size)) : gzinflate($content, $uncompressed_size);
			case 9 :
			$this -> set_error('Enhanced Deflating is not supported... yet?');
			return FALSE;
			case 10 :
			$this -> set_error('PKWARE Date Compression Library Impoloding is not supported... yet?');
			return FALSE;
			case 12 :
				// Bzip2
			return $target_file_name ? file_put_contents($target_file_name, bzdecompress($content)) : bzdecompress($content);
			case 18 :
			$this -> set_error('IBM TERSE is not supported... yet?');
			return FALSE;
			default :
			$this -> set_error('Unknown uncompress method: $mode');
			return FALSE;
		}
	}

	private function _load_file_list_by_eof(&$fh, $stop_on_file = FALSE) {
		// Check if there's a valid Central Dir signature.
		// Let's consider a file comment smaller than 1024 characters...
		// Actually, it length can be 65536.. But we're not going to support it.

		for ($x = 0; $x < 1024; $x++) {
			fseek($fh, -22 - $x, SEEK_END);

			$signature = fread($fh, 4);

			if ($signature == $this -> central_signature_end) {
				// If found EOF Central Dir
				$eodir['disk_number_this'] = unpack("v", fread($fh, 2));
				// number of this disk
				$eodir['disk_number'] = unpack("v", fread($fh, 2));
				// number of the disk with the start of the central directory
				$eodir['total_entries_this'] = unpack("v", fread($fh, 2));
				// total number of entries in the central dir on this disk
				$eodir['total_entries'] = unpack("v", fread($fh, 2));
				// total number of entries in
				$eodir['size_of_cd'] = unpack("V", fread($fh, 4));
				// size of the central directory
				$eodir['offset_start_cd'] = unpack("V", fread($fh, 4));
				// offset of start of central directory with respect to the starting disk number
				$zip_comment_lenght = unpack("v", fread($fh, 2));
				// zipfile comment length
				$eodir['zipfile_comment'] = $zip_comment_lenght[1] ? fread($fh, $zip_comment_lenght[1]) : '';
				// zipfile comment

				$this -> end_of_central = array('disk_number_this' => $eodir['disk_number_this'][1], 'disk_number' => $eodir['disk_number'][1], 'total_entries_this' => $eodir['total_entries_this'][1], 'total_entries' => $eodir['total_entries'][1], 'size_of_cd' => $eodir['size_of_cd'][1], 'offset_start_cd' => $eodir['offset_start_cd'][1], 'zipfile_comment' => $eodir['zipfile_comment'], );

				// Then, load file list
				fseek($fh, $this -> end_of_central['offset_start_cd']);
				$signature = fread($fh, 4);

				while ($signature == $this -> dir_signature) {
					$dir['version_madeby'] = unpack("v", fread($fh, 2));
					// version made by
					$dir['version_needed'] = unpack("v", fread($fh, 2));
					// version needed to extract
					$dir['general_bit_flag'] = unpack("v", fread($fh, 2));
					// general purpose bit flag
					$dir['compression_method'] = unpack("v", fread($fh, 2));
					// compression method
					$dir['lastmod_time'] = unpack("v", fread($fh, 2));
					// last mod file time
					$dir['lastmod_date'] = unpack("v", fread($fh, 2));
					// last mod file date
					$dir['crc-32'] = fread($fh, 4);
					// crc-32
					$dir['compressed_size'] = unpack("V", fread($fh, 4));
					// compressed size
					$dir['uncompressed_size'] = unpack("V", fread($fh, 4));
					// uncompressed size
					$zip_file_length = unpack("v", fread($fh, 2));
					// filename length
					$extra_field_length = unpack("v", fread($fh, 2));
					// extra field length
					$fileCommentLength = unpack("v", fread($fh, 2));
					// file comment length
					$dir['disk_number_start'] = unpack("v", fread($fh, 2));
					// disk number start
					$dir['internal_attributes'] = unpack("v", fread($fh, 2));
					// internal file attributes-byte1
					$dir['external_attributes1'] = unpack("v", fread($fh, 2));
					// external file attributes-byte2
					$dir['external_attributes2'] = unpack("v", fread($fh, 2));
					// external file attributes
					$dir['relative_offset'] = unpack("V", fread($fh, 4));
					// relative offset of local header
					$dir['file_name'] = fread($fh, $zip_file_length[1]);
					// filename
					$dir['extra_field'] = $extra_field_length[1] ? fread($fh, $extra_field_length[1]) : '';
					// extra field
					$dir['file_comment'] = $fileCommentLength[1] ? fread($fh, $fileCommentLength[1]) : '';
					// file comment
					// Convert the date and time, from MS-DOS format to UNIX Timestamp
					$binary_mod_date = str_pad(decbin($dir['lastmod_date'][1]), 16, '0', STR_PAD_LEFT);
					$binary_mod_time = str_pad(decbin($dir['lastmod_time'][1]), 16, '0', STR_PAD_LEFT);
					$last_mod_year = bindec(substr($binary_mod_date, 0, 7)) + 1980;
					$last_mod_month = bindec(substr($binary_mod_date, 7, 4));
					$last_mod_day = bindec(substr($binary_mod_date, 11, 5));
					$last_mod_hour = bindec(substr($binary_mod_time, 0, 5));
					$last_mod_minute = bindec(substr($binary_mod_time, 5, 6));
					$last_mod_second = bindec(substr($binary_mod_time, 11, 5));

					$this -> central_dir_list[$dir['file_name']] = array('version_madeby' => $dir['version_madeby'][1], 'version_needed' => $dir['version_needed'][1], 'general_bit_flag' => str_pad(decbin($dir['general_bit_flag'][1]), 8, '0', STR_PAD_LEFT), 'compression_method' => $dir['compression_method'][1], 'lastmod_datetime' => mktime($last_mod_hour, $last_mod_minute, $last_mod_second, $last_mod_month, $last_mod_day, $last_mod_year), 'crc-32' => str_pad(dechex(ord($dir['crc-32'][3])), 2, '0', STR_PAD_LEFT) . str_pad(dechex(ord($dir['crc-32'][2])), 2, '0', STR_PAD_LEFT) . str_pad(dechex(ord($dir['crc-32'][1])), 2, '0', STR_PAD_LEFT) . str_pad(dechex(ord($dir['crc-32'][0])), 2, '0', STR_PAD_LEFT), 'compressed_size' => $dir['compressed_size'][1], 'uncompressed_size' => $dir['uncompressed_size'][1], 'disk_number_start' => $dir['disk_number_start'][1], 'internal_attributes' => $dir['internal_attributes'][1], 'external_attributes1' => $dir['external_attributes1'][1], 'external_attributes2' => $dir['external_attributes2'][1], 'relative_offset' => $dir['relative_offset'][1], 'file_name' => $dir['file_name'], 'extra_field' => $dir['extra_field'], 'file_comment' => $dir['file_comment'], );

					$signature = fread($fh, 4);
				}

				// If loaded centralDirs, then try to identify the offsetPosition of the compressed data.
				if ($this -> central_dir_list) {
					foreach ($this->central_dir_list as $filename => $details) {
						$i = $this -> _get_file_header($fh, $details['relative_offset']);
						$this -> compressed_list[$filename]['file_name'] = $filename;
						$this -> compressed_list[$filename]['compression_method'] = $details['compression_method'];
						$this -> compressed_list[$filename]['version_needed'] = $details['version_needed'];
						$this -> compressed_list[$filename]['lastmod_datetime'] = $details['lastmod_datetime'];
						$this -> compressed_list[$filename]['crc-32'] = $details['crc-32'];
						$this -> compressed_list[$filename]['compressed_size'] = $details['compressed_size'];
						$this -> compressed_list[$filename]['uncompressed_size'] = $details['uncompressed_size'];
						$this -> compressed_list[$filename]['lastmod_datetime'] = $details['lastmod_datetime'];
						$this -> compressed_list[$filename]['extra_field'] = $i['extra_field'];
						$this -> compressed_list[$filename]['contents_start_offset'] = $i['contents_start_offset'];

						if (strtolower($stop_on_file) == strtolower($filename)) {
							break;
						}
					}
				}

				return true;
			}
		}
		return FALSE;
	}

	private function _load_files_by_signatures(&$fh, $stop_on_file = FALSE) {
		fseek($fh, 0);

		$return = FALSE;
		for (; ; ) {
			$details = $this -> _get_file_header($fh);

			if (!$details) {
				$this -> set_debug('Invalid signature. Trying to verify if is old style Data Descriptor...');
				fseek($fh, 12 - 4, SEEK_CUR);
				// 12: Data descriptor - 4: Signature (that will be read again)
				$details = $this -> _get_file_header($fh);
			}

			if (!$details) {
				$this -> set_debug('Still invalid signature. Probably reached the end of the file.');
				break;
			}

			$filename = $details['file_name'];
			$this -> compressed_list[$filename] = $details;
			$return = true;

			if (strtolower($stop_on_file) == strtolower($filename)) {
				break;
			}
		}

		return $return;
	}

	private function _get_file_header(&$fh, $start_offset = FALSE) {
		if ($start_offset !== FALSE) {
			fseek($fh, $start_offset);
		}

		$signature = fread($fh, 4);

		if ($signature == $this -> zip_signature) {
			// Get information about the zipped file
			$file['version_needed'] = unpack("v", fread($fh, 2));
			// version needed to extract
			$file['general_bit_flag'] = unpack("v", fread($fh, 2));
			// general purpose bit flag
			$file['compression_method'] = unpack("v", fread($fh, 2));
			// compression method
			$file['lastmod_time'] = unpack("v", fread($fh, 2));
			// last mod file time
			$file['lastmod_date'] = unpack("v", fread($fh, 2));
			// last mod file date
			$file['crc-32'] = fread($fh, 4);
			// crc-32
			$file['compressed_size'] = unpack("V", fread($fh, 4));
			// compressed size
			$file['uncompressed_size'] = unpack("V", fread($fh, 4));
			// uncompressed size
			$zip_file_length = unpack("v", fread($fh, 2));
			// filename length
			$extra_field_length = unpack("v", fread($fh, 2));
			// extra field length
			$file['file_name'] = fread($fh, $zip_file_length[1]);
			// filename
			$file['extra_field'] = $extra_field_length[1] ? fread($fh, $extra_field_length[1]) : '';
			// extra field
			$file['contents_start_offset'] = ftell($fh);

			// Bypass the whole compressed contents, and look for the next file
			fseek($fh, $file['compressed_size'][1], SEEK_CUR);

			// Convert the date and time, from MS-DOS format to UNIX Timestamp
			$binary_mod_date = str_pad(decbin($file['lastmod_date'][1]), 16, '0', STR_PAD_LEFT);
			$binary_mod_time = str_pad(decbin($file['lastmod_time'][1]), 16, '0', STR_PAD_LEFT);

			$last_mod_year = bindec(substr($binary_mod_date, 0, 7)) + 1980;
			$last_mod_month = bindec(substr($binary_mod_date, 7, 4));
			$last_mod_day = bindec(substr($binary_mod_date, 11, 5));
			$last_mod_hour = bindec(substr($binary_mod_time, 0, 5));
			$last_mod_minute = bindec(substr($binary_mod_time, 5, 6));
			$last_mod_second = bindec(substr($binary_mod_time, 11, 5));

			// Mount file table
			$i = array('file_name' => $file['file_name'], 'compression_method' => $file['compression_method'][1], 'version_needed' => $file['version_needed'][1], 'lastmod_datetime' => mktime($last_mod_hour, $last_mod_minute, $last_mod_second, $last_mod_month, $last_mod_day, $last_mod_year), 'crc-32' => str_pad(dechex(ord($file['crc-32'][3])), 2, '0', STR_PAD_LEFT) . str_pad(dechex(ord($file['crc-32'][2])), 2, '0', STR_PAD_LEFT) . str_pad(dechex(ord($file['crc-32'][1])), 2, '0', STR_PAD_LEFT) . str_pad(dechex(ord($file['crc-32'][0])), 2, '0', STR_PAD_LEFT), 'compressed_size' => $file['compressed_size'][1], 'uncompressed_size' => $file['uncompressed_size'][1], 'extra_field' => $file['extra_field'], 'general_bit_flag' => str_pad(decbin($file['general_bit_flag'][1]), 8, '0', STR_PAD_LEFT), 'contents_start_offset' => $file['contents_start_offset']);

			return $i;
		}
		return FALSE;
	}

}
?>
</div>
</div>
</body>

</html>