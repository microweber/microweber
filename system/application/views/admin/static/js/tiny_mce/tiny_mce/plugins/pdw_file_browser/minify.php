<?php
/**
 * Minify - Combines, minifies, and caches JavaScript and CSS files on demand.
 *
 * See http://code.google.com/p/minify/ for usage instructions.
 *
 * This library was inspired by jscsscomp by Maxim Martynyuk <flashkot@mail.ru>
 * and by the article "Supercharged JavaScript" by Patrick Hunlock
 * <wb@hunlock.com>.
 *
 * JSMin was originally written by Douglas Crockford <douglas@crockford.com>.
 *
 * Requires PHP 4.3.11+
 *
 * @package Minify
 * @author Ryan Grove <ryan@wonko.com>
 * @copyright 2007 Ryan Grove. All rights reserved.
 * @license http://opensource.org/licenses/bsd-license.php  New BSD License
 * @version 1.0.1 (2007-05-05)
 * @link http://code.google.com/p/minify/
 */

// Uncomment when debugging.
// error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'on');


// cut and paste from: http://us2.php.net/manual/en/function.sys-get-temp-dir.php
if ( !function_exists('sys_get_temp_dir') )
{
    // Based on http://www.phpit.net/
    // article/creating-zip-tar-archives-dynamically-php/2/
    function sys_get_temp_dir()
    {
        // Try to get from environment variable
        if ( !empty($_ENV['TMP']) )
        {
            return realpath( $_ENV['TMP'] );
        }
        else if ( !empty($_ENV['TMPDIR']) )
        {
            return realpath( $_ENV['TMPDIR'] );
        }
        else if ( !empty($_ENV['TEMP']) )
        {
            return realpath( $_ENV['TEMP'] );
        }

        // Detect by creating a temporary file
        else
        {
            // Try to use system's temporary directory
            // as random name shouldn't exist
            $temp_file = tempnam( md5(uniqid(rand(), TRUE)), '' );
            if ( $temp_file )
            {
                $temp_dir = realpath( dirname($temp_file) );
                unlink( $temp_file );
                return $temp_dir;
            }
            else
            {
                return FALSE;
            }
        }
    }
}

// again, cut and paste from comments for file_put_contents
if ( !function_exists('file_put_contents') )
{
	define('FILE_APPEND', 1);
	function file_put_contents($n, $d, $flag = false) {
	    $mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
	    $f = @fopen($n, $mode);
	    if ($f === false) {
	        return 0;
	    } else {
	        if (is_array($d)) $d = implode($d);
	        $bytes_written = fwrite($f, $d);
	        fclose($f);
	        return $bytes_written;
	    }
	}
}


if (!defined('MINIFY_BASE_DIR')) {
  /** 
   * Base path from which all relative file paths should be resolved. By default
   * this is set to the document root.
   */
  define('MINIFY_BASE_DIR', realpath(dirname(__FILE__)));
}

if (!defined('MINIFY_CACHE_DIR')) {
  /** Directory where compressed files will be cached. */
  define('MINIFY_CACHE_DIR', sys_get_temp_dir());
}

if (!defined('MINIFY_ENCODING')) {
  /** Character set to use when outputting the minified files. */
  define('MINIFY_ENCODING', 'utf-8');
}

if (!defined('MINIFY_MAX_FILES')) {
  /** Maximum number of files to combine in one request. */
  define('MINIFY_MAX_FILES', 16);
}

if (!defined('MINIFY_REWRITE_CSS_URLS')) {
  /** 
   * Whether or not Minify should attempt to rewrite relative URLs used in CSS
   * files so that they continue to point to the correct location after the file
   * is combined and minified.
   *
   * Minify is pretty good at getting this right, but occasionally it can make
   * mistakes. If you find that URL rewriting results in problems, you should
   * disable it.
   */
  define('MINIFY_REWRITE_CSS_URLS', false);
}

if (!defined('MINIFY_USE_CACHE')) {
  /**
   * Whether or not Minify should use a disk-based cache to increase
   * performance.
   */
  define('MINIFY_USE_CACHE', true);
}

/**
 * Minify is a library for combining, minifying, and caching JavaScript and CSS
 * files on demand before sending them to a web browser.
 *
 * @package Minify
 * @author Ryan Grove <ryan@wonko.com>
 * @copyright 2007 Ryan Grove. All rights reserved.
 * @license http://opensource.org/licenses/bsd-license.php  New BSD License
 * @version 1.0.1 (2007-05-05)
 * @link http://code.google.com/p/minify/
 */

define('TYPE_CSS', 	'text/css');
define('TYPE_JS', 	'text/javascript');
	
class Minify {
  
  var $files = array();
  var $type;

  function Minify($type = TYPE_JS) {
	$this->type = $type;
  }
  

  /**
   * Combines, minifies, and outputs the requested files.
   *
   * Inspects the $_GET array for a 'files' entry containing a comma-separated
   * list and uses this as the set of files to be combined and minified.
   */
  function handleRequest() {
    // 404 if no files were requested.
    if (!isset($_GET['files'])) {
      header('HTTP/1.0 404 Not Found');
      exit;
    }

    $files = array_map('trim', explode(',', $_GET['files'], MINIFY_MAX_FILES));

    // 404 if the $files array is empty for some weird reason.
    if (!count($files)) {
      header('HTTP/1.0 404 Not Found');
      exit;
    }

    // Determine the content type based on the extension of the first file
    // requested.
    $type = preg_match('/\.js$/iD', $files[0]) ? TYPE_JS : TYPE_CSS;

    // Minify and spit out the result.
    $minify = new Minify($type);
	$minify->addFile($files);
    
    ob_start("ob_gzhandler");
    header("Content-Type: $type;charset=".MINIFY_ENCODING);

   	$minify->browserCache();
    echo $minify->combine();

  }  

  
  /**
   * Minifies the specified string and returns it.
   *
   * @param string $string JavaScript or CSS string to minify
   * @param string $type content type of the string (either Minify::TYPE_CSS or
   *   Minify::TYPE_JS)
   * @return string minified string
   */
  function _minify($string, $type = TYPE_JS) {
    return $type === TYPE_JS ? Minify::minifyJS($string) :
        Minify::minifyCSS($string);
  }

  // -- Protected Static Methods -----------------------------------------------

  /**
   * Minifies the specified CSS string and returns it.
   *
   * @param string $css CSS string
   * @return string minified string
   * @see minify()
   * @see minifyJS()
   */
  function minifyCSS($css) {

	// Compress whitespace.
    $css = preg_replace('/\s+/', ' ', $css);

    // Remove comments.
    $css = preg_replace('/\/\*.*?\*\//', '', $css);

    return trim($css);
  }

  /**
   * Minifies the specified JavaScript string and returns it.
   *
   * @param string $js JavaScript string
   * @return string minified string
   * @see minify()
   * @see minifyCSS()
   */
  function minifyJS($js) {
    require_once dirname(__FILE__).'/jsmin.php';
    return JSMin::minify($js);
  }

  /**
   * Rewrites relative URLs in the specified CSS string to point to the correct
   * location. URLs are assumed to be relative to the absolute path specified in
   * the $path parameter.
   *
   * @param string $css CSS string
   * @param string $path absolute path to which URLs are relative (should be a
   *   directory, not a file)
   * @return string CSS string with rewritten URLs
   */
  function rewriteCSSUrls($css, $path) {
    /*
    Parentheses, commas, whitespace chars, single quotes, and double quotes are
    escaped with a backslash as described in the CSS spec:
    http://www.w3.org/TR/REC-CSS1#url
    */
    $relativePath = preg_replace('/([\(\),\s\'"])/', '\\\$1',
        str_replace(MINIFY_BASE_DIR, '', $path));

    return preg_replace('/url\(\s*[\'"]?\/?(.+?)[\'"]?\s*\)/i', 'url('.
        $relativePath.'/$1)', $css);
  }

  // -- Public Instance Methods ------------------------------------------------

  /**
   * Instantiates a new Minify object. A filename can be in the form of a
   * relative path or a URL that resolves to the same site that hosts Minify.
   *
   * @param string $type content type of the specified files (either
   *   Minify::TYPE_CSS or Minify::TYPE_JS)
   * @param array|string $files filename or array of filenames to be minified
   */
  function __construct($type = TYPE_JS, $files = array()) {
    if ($type !== TYPE_JS && $type !== TYPE_CSS) {
      die('Invalid argument ($type): '.
          $type);
    }

    $this->type = $type;

    if (count((array) $files)) {
      $this->addFile($files);
    }
  }

  /**
   * Adds the specified filename or array of filenames to the list of files to
   * be minified. A filename can be in the form of a relative path or a URL
   * that resolves to the same site that hosts Minify.
   *
   * @param array|string $files filename or array of filenames
   * @see getFiles()
   * @see removeFile()
   */
  function addFile($files) {
    $files = array_map(array($this, 'resolveFilePath'), (array) $files);
    $this->files = array_unique(array_merge($this->files, $files));
  }

  /**
   * Attempts to serve the combined, minified files from the cache if possible.
   *
   * This method first checks the ETag value and If-Modified-Since timestamp
   * sent by the browser and exits with an HTTP "304 Not Modified" response if
   * the requested files haven't changed since they were last sent to the
   * client.
   *
   * If the browser hasn't cached the content, we check to see if it's been
   * cached on the server and, if so, we send the cached content and exit.
   *
   * If neither the client nor the server has the content in its cache, we don't
   * do anything.
   *
   * @return bool
   */
  function browserCache() {
    $hash         = $this->getHash();
    $lastModified = $this->getLastModified();

    $lastModifiedGMT = gmdate('D, d M Y H:i:s', $lastModified).' GMT';

    // Check/set the ETag.
    $etag = $hash.'_'.$lastModified;

    if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
      if (strpos($_SERVER['HTTP_IF_NONE_MATCH'], $etag) !== false) {
        header("Last-Modified: $lastModifiedGMT", true, 304);
        exit;
      }
    }

    header('ETag: "'.$etag.'"');

    // Check If-Modified-Since.
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
      if ($lastModified <= strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        header("Last-Modified: $lastModifiedGMT", true, 304);
        exit;
      }
    }

    header("Last-Modified: $lastModifiedGMT");

    return false;
  }

  /**
   * Combines and returns the contents of all files that have been added with
   * addFile() or via this class's constructor.
   *
   * If MINIFY_USE_CACHE is true, the content will be returned from the server's
   * cache if the cache is up to date; otherwise the new content will be saved
   * to the cache for future use.
   *
   * @param bool $minify minify the combined contents before returning them
   * @return string combined file contents
   */
  function combine($minify = true) {

    // Return contents from server cache if possible.
    if (MINIFY_USE_CACHE) {
      if ($cacheResult = $this->serverCache(true)) {
        return $cacheResult;
      }
    }

    // Combine contents.
    $combined = array();

    foreach($this->files as $file) {
      if ($this->type === TYPE_CSS && MINIFY_REWRITE_CSS_URLS) {
        // Rewrite relative CSS URLs.
        $combined[] = Minify::rewriteCSSUrls(file_get_contents($file),
            dirname($file));
      }
      else {
        $combined[] = file_get_contents($file);
      }
    }
    
    $combined = $minify ? Minify::_minify(implode("\n", $combined), $this->type) :
        implode("\n", $combined);

    // Save combined contents to the cache.
    if (MINIFY_USE_CACHE) {
      $cacheFile = MINIFY_CACHE_DIR.'/minify_'.$this->getHash();
      file_put_contents($cacheFile, $combined, LOCK_EX);
    }

    return $combined;
  }

  /**
   * Gets an array of absolute pathnames of all files that have been added with
   * addFile() or via this class's constructor.
   *
   * @return array array of absolute pathnames
   * @see addFile()
   * @see removeFile()
   */
  function getFiles() {
    return $this->files;
  }

  /**
   * Gets the MD5 hash of the concatenated filenames from the list of files to
   * be minified.
   */
  function getHash() {
    return md5(implode('', $this->files));
  }
  
  /**
   * Gets the timestamp of the most recently modified file.
   *
   * @return int timestamp
   */
  function getLastModified() {
    $lastModified = 0;

    // Get the timestamp of the most recently modified file.
    foreach($this->files as $file) {
      $modified = filemtime($file);
      
      if ($modified !== false && $modified > $lastModified) {
        $lastModified = $modified;
      }
    }

    return $lastModified;
  }

  /**
   * Removes the specified filename or array of filenames from the list of files
   * to be minified.
   *
   * @param array|string $files filename or array of filenames
   * @see addFile()
   * @see getFiles()
   */
  function removeFile($files) {
    $files = array_map(array($this, 'resolveFilePath'), (array) $files);
    $this->files = array_diff($this->files, $files);
  }

  /**
   * Attempts to serve the combined, minified files from the server's disk-based
   * cache if possible.
   *
   * @param bool $return return cached content as a string instead of outputting
   *   it to the client
   * @return bool|string
   */
  function serverCache($return = false) {
    $cacheFile    = MINIFY_CACHE_DIR.'/minify_'.$this->getHash();
    $lastModified = $this->getLastModified();

    if (is_file($cacheFile) && $lastModified <= filemtime($cacheFile)) {
      if ($return) {
        return file_get_contents($cacheFile);
      }
      else {
        echo file_get_contents($cacheFile);
        exit;
      }
    }

    return false;
  }

  // -- Protected Instance Methods ---------------------------------------------

  /**
   * Returns the canonicalized absolute pathname to the specified file or local
   * URL.
   *
   * @param string $file relative file path
   * @return string canonicalized absolute pathname
   */
  function resolveFilePath($file) {
    // Is this a URL?
    if (preg_match('/^https?:\/\//i', $file)) {
      if (!$parsedUrl = parse_url($file)) {
        die("Invalid URL: $file");
      }

      // Does the server name match the local server name?
      if (!isset($parsedUrl['host']) ||
          $parsedUrl['host'] != $_SERVER['SERVER_NAME']) {
        die('Non-local URL not supported: '.
            $file);
      }

      // Get the file's absolute path.
      $filepath = realpath(MINIFY_BASE_DIR.$parsedUrl['path']);
    }
    else {
      // Get the file's absolute path.
      $filepath = realpath(MINIFY_BASE_DIR.'/'.$file);
    }

    // Ensure that the file exists, that the path is under the base directory,
    // that the file's extension is either '.css' or '.js', and that the file is
    // actually readable.
    if (!$filepath ||
        !is_file($filepath) ||
        !is_readable($filepath) ||
        !preg_match('/^'.preg_quote(MINIFY_BASE_DIR, '/').'/', $filepath) ||
        !preg_match('/\.(?:css|js)$/iD', $filepath)) {

      // Even when the file exists, we still throw a
      // MinifyFileNotFoundException in order to try to prevent an information
      // disclosure vulnerability.
      die("File not found: $file");
    }

    return $filepath;
  }
}

// -- Global Scope -------------------------------------------------------------
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
  Minify::handleRequest();
}

?>