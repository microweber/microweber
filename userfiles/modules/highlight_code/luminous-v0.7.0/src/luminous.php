<?php


require_once(dirname(__FILE__) . '/debug.php');

require_once(dirname(__FILE__) . '/options.class.php');

require_once(dirname(__FILE__) . '/cache/cache.class.php');
require_once(dirname(__FILE__) . '/cache/fscache.class.php');
require_once(dirname(__FILE__) . '/cache/sqlcache.class.php');
require_once(dirname(__FILE__) . '/scanners.class.php');
require_once(dirname(__FILE__) . '/formatters/formatter.class.php');
require_once(dirname(__FILE__) . '/core/scanner.class.php');

// updated automatically, use single quotes, keep single line
define('LUMINOUS_VERSION', 'v0.7.0');


/*
 * This file contains the public calling interface for Luminous. It's split
 * into two classes: one is basically the user-interface, the other is a
 * wrapper around it. The wrapper allows a single-line function call, and is
 * procedural. It's wrapped in an abstract class for a namespace.
 * The real class is instantiated into a singleton which is manipulated by
 * the abstract class methods.
 */




/**
 * @cond ALL
 *  This is kind of a pseudo-UI class. It's a singleton which will be
 * manipulated by a few procedural functions, for ease of use.
 * It's technically supposed to be private to this class, but this is a sort
 * of 'agreed' privateness, and we expose an instance of it globally.
 *
 * In fact, it is used by (at least) the diff scanner, which uses its
 * scanner table.
 * 
 * @internal
 * 
 */
class _Luminous {

  /// Settings array
  /// @see LuminousOptions
  public $settings;

  public $scanners; ///< the scanner table

  public $cache = null;
  
  /**
   * The language is passed to the formatter which may choose to do something
   * interesting with it. If you use the scanners table this can be figured out
   * automatically, but if you pass in your own scanner, you will need to 
   * give a language name if you want the formatter to consider it.
   */
  public $language = null;

  public function __construct() {
    $this->scanners = new LuminousScanners();
    $this->register_default_scanners();
  }

  /// registers builtin scanners
  private function register_default_scanners() {
    $this->settings = new LuminousOptions();
    require dirname(__FILE__) . '/load_scanners.php';
  }


  /**
   * Returns an instance of the current formatter
   */
  function get_formatter() {
    $fmt_path = dirname(__FILE__) . '/formatters/';

    $fmt = $this->settings->format;
    $formatter = null;
    if (!is_string($fmt) && is_subclass_of($fmt, 'LuminousFormatter')) {
      $formatter = clone $fmt;
    } elseif ($fmt === 'html') {
      require_once($fmt_path . 'htmlformatter.class.php');
      $formatter = new LuminousFormatterHTML();
    } elseif ($fmt ===  'html-inline') {
      require_once($fmt_path . 'htmlformatter.class.php');
      $formatter = new LuminousFormatterHTMLInline();
    } elseif ($fmt ===  'html-full') {
      require_once($fmt_path . 'htmlformatter.class.php');
      $formatter = new LuminousFormatterHTMLFullPage();
    } elseif($fmt === 'latex') {
      require_once($fmt_path . 'latexformatter.class.php');
      $formatter = new LuminousFormatterLatex();
    } elseif($fmt ===  null || $fmt === 'none') {
      require_once($fmt_path . 'identityformatter.class.php');
      $formatter = new LuminousIdentityFormatter();
    }
    
    if ($formatter === null) {
      throw new Exception('Unknown formatter: ' . $this->settings->format);
      return null;
    }
    $this->set_formatter_options($formatter);
    return $formatter;
  }

  /**
   * Sets up a formatter instance according to our current options/settings
   */
  private function set_formatter_options(&$formatter) {
    $formatter->wrap_length = $this->settings->wrap_width;
    $formatter->line_numbers = $this->settings->line_numbers;
    $formatter->start_line = $this->settings->start_line;
    $formatter->link = $this->settings->auto_link;
    $formatter->height = $this->settings->max_height;
    $formatter->strict_standards = $this->settings->html_strict;
    $formatter->set_theme(luminous::theme($this->settings->theme));
    $formatter->highlight_lines = $this->settings->highlight_lines;
    $formatter->language = $this->language;
  }

  /**
   * calculates a 'cache_id' for the input. This is dependent upon the
   * source code and the settings. This should be (near-as-feasible) unique
   * for any cobmination of source, language and settings
   */
  private function cache_id($scanner, $source) {
    // to figure out the cache id, we mash a load of stuff together and
    // md5 it. This gives us a unique (assuming no collisions) handle to
    // a cache file, which depends on the input source, the relevant formatter
    // settings, the version, and scanner.
    $settings = array($this->settings->wrap_width,
      $this->settings->line_numbers,
      $this->settings->start_line,
      $this->settings->auto_link,
      $this->settings->max_height,
      $this->settings->format,
      $this->settings->theme,
      $this->settings->html_strict,
      LUMINOUS_VERSION,
    );

    $id = md5($source);
    $id = md5($id . serialize($scanner));
    $id = md5($id . serialize($settings));
    return $id;
  }


  
  /**
   * The real highlighting function
   * @throw InvalidArgumentException if $scanner is not either a string or a
   *    LuminousScanner instance, or if $source is not a string.
   */
  function highlight($scanner, $source, $settings = null) {
    $old_settings = null;
    if ($settings !== null) {
      if (!is_array($settings)) {
          throw new Exception('Luminous internal error: Settings is not an array');
      }
      $old_settings = clone $this->settings;
      foreach($settings as $k=>$v) {
        $this->settings->set($k, $v);
      }
    }
    $should_reset_language = false;
    $this->cache = null;
    if (!is_string($source)) throw new InvalidArgumentException('Non-string '
        . 'supplied for $source');
    
    if (!($scanner instanceof LuminousScanner)) {
      if (!is_string($scanner)) throw new InvalidArgumentException('Non-string
        or LuminousScanner instance supplied for $scanner');
      $code = $scanner;
      $scanner = $this->scanners->GetScanner($code);
      if ($scanner === null) throw new Exception("No known scanner for '$code' and no default set");
      $should_reset_language = true;
      $this->language = $this->scanners->GetDescription($code);
    }
    $cache_hit = true;
    $out = null;
    if ($this->settings->cache) {
      $cache_id = $this->cache_id($scanner, $source);
      if ($this->settings->sql_function !== null) {
        $this->cache = new LuminousSQLCache($cache_id);
        $this->cache->set_sql_function($this->settings->sql_function);
      } else {
        $this->cache = new LuminousFileSystemCache($cache_id);
      }
      $this->cache->set_purge_time($this->settings->cache_age);
      $out = $this->cache->read();
    }
    if ($out === null) {
      $cache_hit = false;
      $out_raw = $scanner->highlight($source);
      $formatter = $this->get_formatter();
      $out = $formatter->format($out_raw);
    }

    if ($this->settings->cache && !$cache_hit) {
      $this->cache->write($out);
    }
    if ($should_reset_language) $this->language = null;
    if ($old_settings !== null) {
      $this->settings = $old_settings;
    }
    return $out;
  }
}

/// @endcond
// ends ALL


// Here's our singleton.
global $luminous_; // sometimes need this or the object seems to disappear
$luminous_ = new _Luminous();

/// @cond USER

// here's our 'real' UI class, which uses the above singleton. This is all
// static because these are actually procudural functions, we're using the
// class as a namespace.
/**
 * @brief Users' API
 */
abstract class luminous {

  /**
   * @brief Highlights a string according to the current settings
   * 
   * @param $scanner The scanner to use, this can either be a langauge code,
   *    or it can be an instance of LuminousScanner.
   * @param $source The source string
   * @param $cache Whether or not to use the cache
   * @return the highlighted source code.
   *
   * To specify different output formats or other options, see set().
   */
  static function highlight($scanner, $source, $cache_or_settings=null) {
    global $luminous_;
    try {
      $settings = null;
      if (is_bool($cache_or_settings)) {
          $settings = array('cache' => $cache_or_settings);
      } else if (is_array($cache_or_settings)) {
        $settings = $cache_or_settings;
      }
      $h = $luminous_->highlight($scanner, $source, $settings);
      if ($luminous_->settings->verbose) {
        $errs = self::cache_errors();
        if (!empty($errs)) {
          trigger_error("Luminous cache errors were encountered. \n" .
            'See luminous::cache_errors() for details.');
        }
      }
      return $h;
    } catch (InvalidArgumentException $e) {
      // this is a user error, let it bubble
      //FIXME how do we let it bubble without throwing? the stack trace will
      // be wrong.
      throw $e;
    }
    catch (Exception $e) {
      // this is an internal error or a scanner error, or something
      // it might not technically be Luminous that caused it, but let's not
      // make it kill the whole page in production code
      if (LUMINOUS_DEBUG) throw $e;
      else {
        $return = $source;
        if (($t = self::setting('failure-tag')))
          $return = "<$t>$return</$t>";
        return $return;
      }
    }
  }

  /**
   * @brief Highlights a file according to the current setings.
   * 
   * @param $scanner The scanner to use, this can either be a langauge code,
   *    or it can be an instance of LuminousScanner.
   * @param $file the source string
   * @param $cache Whether or not to use the cache
   * @return the highlighted source code.
   * 
   * To specify different output formats or other options, see set().
   */
  static function highlight_file($scanner, $file, $cache_or_settings=null) {
    return self::highlight($scanner, file_get_contents($file), $cache_or_settings);
  }

  /**
   * @brief Returns a list of cache errors encountered during the most recent highlight
   *
   * @return An array of errors the cache encountered (which may be empty),
   *  or @c FALSE if the cache is not enabled
   */
  static function cache_errors() {
    global $luminous_;
    $c = $luminous_->cache;
    if ($c === null) return FALSE;
    return $c->errors();
  }

  /**
   * @brief Registers a scanner
   * 
   * Registers a scanner with Luminous's scanner table. Utilising this
   * function means that Luminous will handle instantiation and inclusion of
   * the scanner's source file in a lazy-manner.
   * 
   * @param $language_code A string or array of strings designating the
   *    aliases by which this scanner may be accessed
   * @param $classname The class name of the scanner, as string. If you
   *    leave this as 'null', it will be treated as a dummy file (you can use
   *    this to handle a set of non-circular include rules, if you run into
   *    problems).
   * @param $readable_language  A human readable language name
   * @param $path The path to the source file containing your scanner
   * @param dependencies An array of other scanners which this scanner
   *    depends on (as sub-scanners, or superclasses). Each item in the
   *    array should be a $language_code for another scanner.
   */
  static function register_scanner($language_code, $classname,
    $readable_language, $path, $dependencies=null) {
      global $luminous_;
      $luminous_->scanners->AddScanner($language_code, $classname,
        $readable_language, $path, $dependencies);
  }
  
  /**
   * @brief Get the full filesystem path to Luminous
   * @return what Luminous thinks its location is on the filesystem
   * @internal
   */
  static function root() {
    return realpath(dirname(__FILE__) . '/../');
  }

  /**
   * @brief Gets a list of installed themes
   * 
   * @return the list of theme files present in style/.
   * Each theme will simply be a filename, and will end in .css, and will not
   * have any directory prefix.
   */
  static function themes() {
    $themes_uri = self::root() . '/style/';
    $themes = array();
    foreach(glob($themes_uri . '/*.css') as $css) {
      $fn = trim(preg_replace("%.*/%", '', $css));
      switch($fn) {
        // these are special, exclude these
        case 'luminous.css':
        case 'luminous_print.css':
        case 'luminous.min.css':
          continue;
        default:
        $themes[] = $fn;
      }
    }
    return $themes;
  }

  /**
   * @brief Checks whether a theme exists
   * @param $theme the name of a theme, which should be suffixed with .css
   * @return @c TRUE if a theme exists in style/, else @c FALSE
   */
  static function theme_exists($theme) {
    return in_array($theme, self::themes());
  }
  
  /**
   * @brief Reads a CSS theme file
   * Gets the CSS-string content of a theme file.
   * Use this function for reading themes as it involves security
   * checks against reading arbitrary files
   * 
   * @param $theme The name of the theme to retrieve, which may or may not
   *    include the .css suffix.
   * @return the content of a theme; this is the actual CSS text.
   * @internal
   */
  static function theme($theme) {
    if (!preg_match('/\.css$/i', $theme)) $theme .= '.css';
    if (self::theme_exists($theme)) 
      return file_get_contents(self::root() . "/style/" . $theme);
    else
      throw new Exception('No such theme file: ' . $theme);
  }


  
  /**
   * @brief Gets a setting's value
   * @param $option The name of the setting (corresponds to an attribute name
   * in LuminousOptions)
   * @return The value of the given setting
   * @throws Exception if the option is unrecognised
   *
   * Options are stored in LuminousOptions, which provides documentation of
   * each option.
   * @see LuminousOptions
   */
  static function setting($option) {
    global $luminous_;
    $option = str_replace('-', '_', $option);
    return $luminous_->settings->$option;
  }

  /**
   * @brief Sets the given option to the given value
   * @param $option The name of the setting (corresponds to an attribute name
   * in LuminousOptions)
   * @param $value The new value of the setting
   * @throws Exception if the option is unrecognised (and in various other
   * validation failures),
   * @throws InvalidArgumentException if the argument fails the type-validation
   * check
   *
   * @note This function can also accept multiple settings if $option is a
   * map of option_name=>value
   *
   * Options are stored in LuminousOptions, which provides documentation of
   * each option.
   * 
   * @note as of 0.7 this is a thin wrapper around LuminousOptions::set()
   * 
   * @see LuminousOptions::set
   */
  static function set($option, $value=null) {
    global $luminous_;
    $luminous_->settings->set($option, $value);
  }  

  /**
   * @brief Gets a list of registered scanners
   * 
   * @return a list of scanners currently registered. The list is in the
   * format:
   * 
   *    language_name => codes,
   * 
   * where language_name is a string, and codes is an array of strings. 
   * 
   * The array is sorted alphabetically by key.
   */
  static function scanners() {
    global $luminous_;
    $scanners = $luminous_->scanners->ListScanners();
    ksort($scanners);
    return $scanners;
  }

  /**
   * @brief Gets a formatter instance
   * 
   * @return an instance of a LuminousFormatter according to the current
   * format setting
   *
   * This shouldn't be necessary for general usage, it is only implemented
   * for testing.
   * @internal
   */
  static function formatter() {
    global $luminous_;
    return $luminous_->get_formatter();
  }

  /**
    * @internal
    * Comparison function for guess_language()'s sorting
    */
  static function __guess_language_cmp($a, $b) {
    $c = $a['p'] - $b['p'];
    if ($c === 0) return 0;
    elseif ($c < 0) return -1;
    else return 1;
  }



  /**
   * @brief Attempts to guess the language of a piece of source code
   * @param $src The source code whose language is to be guessed
   * @param $confidence The desired confidence level: if this is 0.05 but the
   *  best guess has a confidence of 0.04, then $default is returned. Note 
   *  that the confidence level returned by scanners is quite arbitrary, so
   *  don't set this to '1' thinking that'll give you better results. 
   *  A realistic confidence is likely to be quite low, because a scanner will
   *  only return 1 if it's able to pick out a shebang (#!) line or something
   *  else definitive. If there exists no such identifier, a 'strong' 
   *  confidence which is right most of the time might be as low as 0.1. 
   *  Therefore it is recommended to keep this between 0.01 and 0.10.
   * @param $default The default name to return in the event that no scanner
   * thinks this source belongs to them (at the desired confidence).
   *
   * @return A valid code for the best scanner, or $default.
   *
   * This is a wrapper around luminous::guess_language_full
   */
  static function guess_language($src, $confidence=0.05, $default = 'plain') {
    $guess = self::guess_language_full($src);
    if ($guess[0]['p'] >= $confidence) 
      return $guess[0]['codes'][0];
    else 
      return $default;
  }

  /**
   * @brief Attempts to guess the language of a piece of source code
   * @param $src The source code whose language is to be guessed
   * @return An array - the array is ordered by probability, with the most
   *    probable language coming first in the array.
   *    Each array element is an array which represents a language (scanner), 
   *    and has the keys:
   *    \li \c 'language' => Human-readable language description,
   *    \li \c 'codes' => valid codes for the language (array),
   *    \li \c 'p' => the probability (between 0.0 and 1.0 inclusive),
   * 
   * note that \c 'language' and \c 'codes' are the key => value pair from
   * luminous::scanners()
   *
   * @warning Language guessing is inherently unreliable but should be right
   * about 80% of the time on common languages. Bear in mind that guessing is
   * going to be severely hampered in the case that one language is used to
   * generate code in another language.
   *
   * Usage for this function will be something like this:
   * @code
   * $guesses = luminous::guess_language($src);
   * $output = luminous::highlight($guesses[0]['codes'][0], $src);
   * @endcode
   * 
   * @see luminous::guess_language 
   */
  static function guess_language_full($src) {
    global $luminous_;
    // first we're going to make an 'info' array for the source, which
    // precomputes some frequently useful things, like how many lines it 
    // has, etc. It prevents scanners from redundantly figuring these things
    // out themselves
    $lines = preg_split("/\r\n|[\r\n]/", $src);
    $shebang = '';
    if (preg_match('/^#!.*/', $src, $m)) $shebang = $m[0];

    $info = array(
      'lines' => $lines,
      'num_lines' => count($lines),
      'trimmed' => trim($src),
      'shebang' => $shebang
    );

    $return = array();
    foreach(self::scanners() as $lang=>$codes) {
      $scanner_name = $luminous_->scanners->GetScanner($codes[0], false,
        false);
      assert($scanner_name !== null);
      $return[] = array(
        'language' => $lang,
        'codes' => $codes,
        'p' => call_user_func(array($scanner_name, 'guess_language'), $src,
          $info)
      );
    }
    uasort($return, array('luminous', '__guess_language_cmp'));
    $return = array_reverse($return);
    return $return;
  }


/**
  * @brief Gets the markup you need to include in your web page
  * @return a string representing everything that needs to be printed in
  * the \<head\> section of a website.
  *
  * This is influenced by the following settings:
  *     relative-root,
  *     include-javascript,
  *     include-jquery
  *     theme
  */
  static function head_html() {
    global $luminous_;
    $theme = self::setting('theme');
    $relative_root = self::setting('relative-root'); 
    $js = self::setting('include-javascript');
    $jquery = self::setting('include-jquery');
    
    if (!preg_match('/\.css$/i', $theme)) $theme .= '.css';
    if (!self::theme_exists($theme)) $theme = 'luminous_light.css';
    
    if ($relative_root === null) {
      $relative_root = str_replace($_SERVER['DOCUMENT_ROOT'], '/',
        dirname(__FILE__));
      $relative_root = str_replace('\\', '/', $relative_root); // bah windows
      $relative_root = rtrim($relative_root, '/');
      // go up one level.
      $relative_root = preg_replace('%/[^/]*$%', '', $relative_root);
    }
    // if we ended up with any double slashes, let's zap them, and also
    // trim any trailing ones
    $relative_root = preg_replace('%(?<!:)//+%', '/', $relative_root);
    $relative_root = rtrim($relative_root, '/');
    $out = '';
    $link_template = "<link rel='stylesheet' type='text/css' href='$relative_root/style/%s' id='%s'>\n";
    $script_template = "<script type='text/javascript' src='$relative_root/client/%s'></script>\n";
    $out .= sprintf($link_template, 'luminous.css', 'luminous-style');
    $out .= sprintf($link_template, $theme, 'luminous-theme');
    if ($js) {
      if ($jquery)
        $out .= sprintf($script_template, 'jquery-1.6.4.min.js');
      $out .= sprintf($script_template, 'luminous.js');
    }

    return $out;
  }
}

/// @endcond 
// ends user
