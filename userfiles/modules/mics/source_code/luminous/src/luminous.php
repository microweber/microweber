<?php


require_once(dirname(__FILE__) . '/debug.php');

require_once(dirname(__FILE__) . '/cache/cache.class.php');
require_once(dirname(__FILE__) . '/cache/fscache.class.php');
require_once(dirname(__FILE__) . '/cache/sqlcache.class.php');
require_once(dirname(__FILE__) . '/scanners.class.php');
require_once(dirname(__FILE__) . '/formatters/formatter.class.php');
require_once(dirname(__FILE__) . '/core/scanner.class.php');

// updated automatically, use single quotes, keep single line
define('LUMINOUS_VERSION', 'master');


/*
 * This file contains the public calling interface for Luminous. It's split
 * into two classes: one is basically the user-interface, the other is a
 * wrapper around it. The wrapper allows a single-line function call, and is
 * procedural. It's wrapped in an abstract class for a namespace.
 * The real class is instantiated into a singleton which is manipulated by
 * the abstract class methods.
 *
 * We also have a somewhat convoluted settings class, which is defined
 * explicitly instead of using an assoc. array because this way Doxygen
 * picks it up, and applying strict validation to the arguments is a lot
 * easier.
 */

/**
 * @cond USER
 *
 * @brief Options class.
 *
 * @warning This object's structure isn't guaranteed to be stable so don't read
 * or write these directly. As a user, you should be using luminous::set()
 * and luminous::setting()
 *
 * We use a fair bit of PHP trickery in the implementation here. The keener
 * among you will notice that the options are all private: don't worry about
 * that. We override the __set() method to apply option specific validation.
 * Options can be written to as normal.
 *
 * The option variable names correspond with option strings that can be passed
 * through luminous::set(), however, for historical reasons, underscores can be
 * replaced with dashed in the call.
 */
class LuminousOptions {
  /**
   * @brief Maximum age of cache files in seconds
   * 
   * Cache files which have not been read for this length of time will be
   * removed from the file system. The file's 'mtime' is used to calculate
   * when it was last used, and a cache hit triggers a 'touch'
   *
   * Set to -1 or 0 to disable cache purges
   */
  private $cache_age = 777600;

  /**
   * @brief Word wrapping
   *
   * If the formatter supports line wrapping, lines will be wrapped at
   * this number of characters (0 or -1 to disable)
   */
  private $wrap_width = -1;

  /**
   * @brief Line numbering
   *
   * If the formatter supports line numbering, this setting controls whether
   * or not lines should be numbered
   */
  private $line_numbers = true;

  /**
   * @brief Hyperlinking
   *
   * If the formatter supports hyper-linking, this setting controls whether
   * or not URLs will be automatically linked
   */
  private $auto_link = true;

  /**
   * @brief Widget height constraint
   *
   * If the formatter supports heigh constraint, this setting controls whether
   * or not to constrain the widget's height, and to what.
   */
  private $max_height = 500;

  /**
   * @brief Output format
   *
   * Chooses which output format to use. Current valid settings are:
   * \li 'html' - standard HTML element, contained in a \<div\> with class 'luminous',
   *    CSS is not included and must be included on the page separately
   *    (probably with luminous::head_html())
   * \li 'html-full' - A complete HTML document. CSS is included.
   * \li 'html-inline' - Very similar to 'html' but geared towards inline display.
   *    Probably not very useful.
   * \li 'latex' - A full LaTeX document
   * \li 'none' or \c NULL - No formatter. Internal XML format is returned.
   *    You probably don't want this.
   */
  private $format = 'html';

  /**
   * @brief Theme
   *
   * The default theme to use. This is observed by the HTML-full and LaTeX
   * formatters, it is also read by luminous::head_html().
   *
   * This should be a valid theme which exists in style/
   */
  private $theme = 'luminous_light.css';

  /**
   * @brief HTML strict standards mode
   *
   * The HTML4-strict doctype disallows a few things which are technically
   * useful. Set this to true if you don't want Luminous to break validation
   * on your HTML4-strict document. Luminous should be valid
   * HTML4 loose/transitional and HTML5 without needing to enable this.
   */
  private $html_strict = false;


  /**
   * @brief Location of Luminous relative to your document root
   *
   * If you use luminous::head_html(), it has to try to figure out the
   * path to the style/ directory so that it can return a correct URL to the
   * necessary stylesheets. Luminous may get this wrong in some situations,
   * specifically it is currently impossible to get this right if Luminous
   * exists on the filesystem outside of the document root, and you have used
   * a symbolic link to put it inside. For this reason, this setting allows you
   * to override the path.
   *
   * e.g. If you set this to '/extern/highlighter', the stylesheets will be
   * linked with
   * \<link rel='stylesheet' href='/extern/highlighter/style/luminous.css'\>
   *
   */
  private $relative_root = null;

  /**
   * @brief JavaScript extras
   *
   * controls whether luminous::head_html() outputs the javascript 'extras'.
   */
  private $include_javascript = false;

  /**
   * @brief jQuery
   *
   * Controls whether luminous::head_html() outputs jQuery, which is required
   * for the JavaScript extras. This has no effect if $include_javascript is
   * false.
   */
  private $include_jquery = true;


  /**
   * @brief Failure recovery
   *
   * If Luminous hits some kind of unrecoverable internal error, it should
   * return the input source code back to you. If you want, it can be
   * wrapped in an HTML tag. Hopefully you will never see this.
   */
  private $failure_tag = 'pre';

  /**
   * @brief Defines an SQL function which can execute queries on a database
   *
   * An SQL database can be used as a replacement for the file-system cache
   * database.
   * This function should act similarly to the mysql_query function:
   * it should take a single argument (the query string) and return:
   *    @li boolean @c false if the query fails
   *    @li boolean @c true if the query succeeds but has no return value
   *    @li An array of associative arrays if the query returns rows (each
   *      element is a row, and each row is an map keyed by field name)
   */
  private $sql_function = null;

  

  private static function check_type($value, $type, $nullable=false) {
    if ($nullable && $value === null) return true;
    $func = null;
    if ($type === 'string') $func = 'is_string';
    elseif($type === 'int') $func = 'is_int';
    elseif($type === 'numeric') $func = 'is_numeric';
    elseif($type === 'bool') $func = 'is_bool';
    elseif($type === 'func') $func = 'is_callable';
    else {
      assert(0);
      return true;
    }

    $test = call_user_func($func, $value);
    if (!$test) {
      throw new InvalidArgumentException('Argument should be type ' . $type .
      ($nullable? ' or null' : ''));
    }
    return $test;
  }

  public function __get($name) {
    if (property_exists($this, $name))
      return $this->$name;
    else {
      throw new Exception('Unknown property: ' . $name);
    }
  }

  public function __set($name, $value) {
    if ($name === 'auto_link')
      $this->set_bool($name, $value);
    elseif($name === 'cache_age') {
      if (self::check_type($value, 'int')) $this->$name = $value;
    }
    elseif($name === 'failure_tag') {
      if (self::check_type($value, 'string', true)) $this->$name = $value;
    }
    elseif($name === 'format')
      $this->set_format($value);
     elseif($name === 'html_strict') {
      if (self::check_type($value, 'bool')) $this->$name = $value;
    }
    elseif($name === 'include_javascript' || $name === 'include_jquery') 
      $this->set_bool($name, $value);
    elseif($name === 'line_numbers') 
      $this->set_bool($name, $value);
    elseif($name === 'max_height') 
      $this->set_height($value);
    elseif($name === 'relative_root') {
      if (self::check_type($value, 'string', true)) $this->$name = $value;
    }
    elseif($name === 'theme')
      $this->set_theme($value);
    elseif($name === 'wrap_width') {
      if (self::check_type($value, 'int')) $this->$name = $value;
    }
    elseif($name === 'sql_function') {
      if (self::check_type($value, 'func', true)) $this->$name = $value;
    }
    else {
      throw new Exception('Unknown option: ' . $name);
    }
  }

  private function set_bool($key, $value) {
    if (self::check_type($value, 'bool')) {
      $this->$key = $value;
    }
  }
  private function set_string($key, $value, $nullable=false) {
    if (self::check_type($value, 'string', $nullable)) {
      $this->$key = $value;
    }
  }

  private function set_format($value) {
    // formatter can either be an instance or an identifier (string)
    $is_obj = $value instanceof LuminousFormatter;
    if($is_obj || self::check_type($value, 'string', true)) {
      // validate the string is a known type
      if (!$is_obj && !in_array($value, array('html', 'html-full',
        'html-inline', 'latex', 'none', null), true)) {
        throw new Exception('Invalid formatter: ' . $value);
      }
      else {
        $this->format = $value;
      }
    }
  }

  private function set_theme($value) {
    if (self::check_type($value, 'string')) {
      if (!preg_match('/\.css$/', $value)) $value .= '.css';
      if (!luminous::theme_exists($value)) {
        throw new Exception('No such theme: '
          . luminous::root() . '/style/' . $value);
      }
      else $this->theme = $value;
    }
  }

  private function set_height($value) {
    // height should be either a number or a numeric string with some units at
    // the end
    if (is_numeric($value) 
      || (is_string($value) && preg_match('/^\d+/', $value))
    ) {
      $this->max_height = $value;
    }
    else {
      throw new InvalidArgumentException('Unrecognised format for height');
    }
  }

}
/// @endcond


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
  

  public function __construct() {
    $this->scanners = new LuminousScanners();
    $this->register_default_scanners();
  }

  /// registers builtin scanners
  private function register_default_scanners() {

    $this->settings = new LuminousOptions();
    // we should probably hide this in an include for neatness
    // when it starts growing.
    $language_dir = luminous::root() . '/languages/';
    
    
    // this is a dummy file which includes ECMAScript dependencies in a 
    // non-circular way.
    $this->scanners->AddScanner('ecma-includes', null, null, 
      "$language_dir/include/ecma.php");
      
    $this->scanners->AddScanner(array('ada', 'adb', 'ads'), 
      'LuminousAdaScanner', 'Ada', "$language_dir/ada.php");

    $this->scanners->AddScanner(array('as', 'actionscript'),
    'LuminousActionScriptScanner', 'ActionScript', "$language_dir/as.php", 
    'ecma');

    $this->scanners->AddScanner(array('bnf'), 
      'LuminousBNFScanner', 'Backus Naur Form', "$language_dir/bnf.php");

    $this->scanners->AddScanner(array('bash', 'sh'),
    'LuminousBashScanner', 'Bash', "$language_dir/bash.php");
    
    $this->scanners->AddScanner(array('c', 'cpp', 'h', 'hpp', 'cxx', 'hxx'),
      'LuminousCppScanner', 'C/C++', "$language_dir/cpp.php");
      
    $this->scanners->AddScanner(array('cs', 'csharp', 'c#'),
      'LuminousCSharpScanner', 'C#', "$language_dir/csharp.php");
      
    $this->scanners->AddScanner('css',
      'LuminousCSSScanner', 'CSS', "$language_dir/css.php");
      
    $this->scanners->AddScanner(array('diff', 'patch'),
      'LuminousDiffScanner', 'Diff', "$language_dir/diff.php");
      
    $this->scanners->AddScanner(array('html', 'htm'),
      'LuminousHTMLScanner', 'HTML', "$language_dir/html.php",
      array('js', 'css'));
      
    $this->scanners->AddScanner(array('ecma', 'ecmascript'),
      'LuminousECMAScriptScanner', 'ECMAScript', 
      "$language_dir/ecmascript.php", 'ecma-includes');

    $this->scanners->AddScanner(array('erlang', 'erl', 'hrl'),
      'LuminousErlangScanner', 'Erlang', "$language_dir/erlang.php");

    $this->scanners->AddScanner('go', 'LuminousGoScanner', 'Go',
      "$language_dir/go.php");
    

    $this->scanners->AddScanner(array('groovy'),
      'LuminousGroovyScanner', 'Groovy',
      "$language_dir/groovy.php");

    $this->scanners->AddScanner(array('haskell', 'hs'),
      'LuminousHaskellScanner', 'Haskell', "$language_dir/haskell.php");

    $this->scanners->AddScanner('java',
      'LuminousJavaScanner', 'Java', "$language_dir/java.php");
      
    $this->scanners->AddScanner(array('js', 'javascript'),
      'LuminousJavaScriptScanner', 'JavaScript', "$language_dir/javascript.php",
      array('ecma'));
      
    $this->scanners->AddScanner('json',
      'LuminousJSONScanner', 'JSON', "$language_dir/json.php");

    $this->scanners->AddScanner(array('latex', 'tex'),
      'LuminousLatexScanner', 'LaTeX', "$language_dir/latex.php");
      
    $this->scanners->AddScanner(array('lolcode', 'lolc', 'lol'),
      'LuminousLOLCODEScanner', 'LOLCODE', "$language_dir/lolcode.php");
      
    $this->scanners->AddScanner(array('m', 'matlab'),
      'LuminousMATLABScanner', 'MATLAB', "$language_dir/matlab.php");

    $this->scanners->AddScanner(array('perl', 'pl', 'pm'),
      'LuminousPerlScanner', 'Perl', "$language_dir/perl.php");

    $this->scanners->AddScanner(array('rails','rhtml', 'ror'),
      'LuminousRailsScanner', 'Ruby on Rails',
      "$language_dir/rails.php", array('ruby', 'html'));
      
    $this->scanners->AddScanner(array('ruby','rb'),
      'LuminousRubyScanner', 'Ruby', "$language_dir/ruby.php");

    $this->scanners->AddScanner(array('plain', 'text', 'txt'),
      'LuminousIdentityScanner', 'Plain', "$language_dir/identity.php");

    // PHP Snippet does not require an initial <?php tag to begin highlighting
    $this->scanners->AddScanner('php_snippet', 'LuminousPHPSnippetScanner',
      'PHP Snippet', "$language_dir/php.php", array('html'));
      
    $this->scanners->AddScanner('php',
      'LuminousPHPScanner', 'PHP', "$language_dir/php.php",
      array('html'));
      
    $this->scanners->AddScanner(array('python', 'py'),
      'LuminousPythonScanner', 'Python', "$language_dir/python.php");
    $this->scanners->AddScanner(array('scala', 'scl'),
      'LuminousScalaScanner', 'Scala', "$language_dir/scala.php", 'xml');
    $this->scanners->AddScanner(array('sql', 'mysql'),
      'LuminousSQLScanner', 'SQL', "$language_dir/sql.php");
      
    $this->scanners->AddScanner(array('vim', 'vimscript'),
      'LuminousVimScriptScanner', 'Vim Script', "$language_dir/vim.php");

    $this->scanners->AddScanner(array('vb', 'bas'),
      'LuminousVBScanner', 'Visual Basic', "$language_dir/vb.php",
      'xml');
      
    $this->scanners->AddScanner('xml', 'LuminousXMLScanner', 
      'XML', "$language_dir/xml.php", 'html');

    $this->scanners->SetDefaultScanner('plain');

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
    $formatter->link = $this->settings->auto_link;
    $formatter->height = $this->settings->max_height;
    $formatter->strict_standards = $this->settings->html_strict;
    $formatter->set_theme(luminous::theme($this->settings->theme));
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
  function highlight($scanner, $source, $use_cache=true) {
    if (!is_string($source)) throw new InvalidArgumentException('Non-string '
        . 'supplied for $source');
    
    if (!($scanner instanceof LuminousScanner)) {
      if (!is_string($scanner)) throw new InvalidArgumentException('Non-string
        or LuminousScanner instance supllied for $scanner');
      $code = $scanner;
      $scanner = $this->scanners->GetScanner($code);
      if ($scanner === null) throw new Exception("No known scanner for '$code' and no default set");
    }
    $cache_obj = null;
    $cache_hit = true;
    $out = null;
    if ($use_cache) {
      $cache_id = $this->cache_id($scanner, $source);
      if ($this->settings->sql_function !== null) {
        $cache_obj = new LuminousSQLCache($cache_id);
        $cache_obj->set_sql_function($this->settings->sql_function);
      } else {
        $cache_obj = new LuminousFileSystemCache($cache_id);
      }
      $cache_obj->set_purge_time($this->settings->cache_age);
      $out = $cache_obj->read();
    }
    if ($out === null) {
      $cache_hit = false;
      $out_raw = $scanner->highlight($source);
      $formatter = $this->get_formatter();
      $out = $formatter->format($out_raw);
    }

    if ($use_cache && !$cache_hit) {
      $cache_obj->write($out);
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
  static function highlight($scanner, $source, $cache=true) {
    global $luminous_;
    try {
      return $luminous_->highlight($scanner, $source, $cache);
    } catch (InvalidArgumentException $e) {
      // this is a user error, let it bubble
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
  static function highlight_file($scanner, $file, $cache=true) {
    return self::highlight($scanner, file_get_contents($file), $cache);
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
   * @see LuminousOptions
   */
  static function set($option, $value=null) {
    global $luminous_;
    if (!is_array($option)) $option = array($option => $value);

    foreach($option as $opt=>$val) {
      // we switched from storing objects as a keyed array to an actual object
      // for backwards compatability, we change '-' to '_'
      $opt = str_replace('-', '_', $opt);
      $luminous_->settings->$opt = $val;
    }
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
    $link_template = "<link rel='stylesheet' type='text/css' href='$relative_root/style/%s'>\n";
    $script_template = "<script type='text/javascript' src='$relative_root/client/%s'></script>\n";
    $out .= sprintf($link_template, 'luminous.css');
    $out .= sprintf($link_template, $theme);
    if ($js || LUMINOUS_DEBUG) {
      if ($jquery)
        $out .= sprintf($script_template, 'jquery-1.4.2.min.js');
      $out .= sprintf($script_template, 'luminous.js');
      if (LUMINOUS_DEBUG) 
        $out .= sprintf($script_template, 'lineheight.js');
    }

    return $out;
  }
}

/// @endcond 
// ends user
