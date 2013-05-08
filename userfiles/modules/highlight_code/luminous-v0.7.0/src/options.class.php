<?php

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
   * @brief Whether to use the built-in cache
   */
  private $cache = true;
  
  /**
   * @brief Maximum age of cache files in seconds
   * 
   * Cache files which have not been read for this length of time will be
   * removed from the file system. The file's 'mtime' is used to calculate
   * when it was last used, and a cache hit triggers a 'touch'
   *
   * Set to -1 or 0 to disable cache purges
   */
  private $cache_age = 7776000; // 90 days

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
   * @brief Line number of first line
   *
   * If the formatter supports line numbering, this setting controls number
   * of the first line
   */
  private $start_line = 1;
  
  
  /**
    * @brief Highlighting of lines
    *
    * If the formatter supports highlighting lines, this setting allows
    * the caller to specify the set of line numbers to highlight
    */
  private $highlight_lines = array();

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
  private $max_height = -1;

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
  private $include_jquery = false;


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

  private $verbose = true;

  
  public function LuminousOptions($opts=null) {
    if (is_array($opts)) {
      $this->set($opts);
    }
  }
  
  public function set($nameOrArray, $value=null) {
    $array = $nameOrArray;
    if (!is_array($array)) {
      $array = array($nameOrArray => $value);
    }
    foreach($array as $option => $value) {
      // for backwards compatibility we need to do this here
      $option = str_replace('-', '_', $option);
      $this->__set($option, $value);
    }
  }
  

  private static function check_type($value, $type, $nullable=false) {
    if ($nullable && $value === null) return true;
    $func = null;
    if ($type === 'string') $func = 'is_string';
    elseif($type === 'int') $func = 'is_int';
    elseif($type === 'numeric') $func = 'is_numeric';
    elseif($type === 'bool') $func = 'is_bool';
    elseif($type === 'func') $func = 'is_callable';
    elseif($type === 'array') $func = 'is_array';
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
    else if ($name === 'cache') {
      $this->set_bool($name, $value);
    }
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
    elseif($name === 'start_line') 
      $this->set_start_line($value);
    elseif($name === 'highlight_lines') {
      if (self::check_type($value, 'array'))
        $this->highlight_lines = $value;
    }
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
    elseif ($name === 'verbose') {
      $this->set_bool($name, $value);
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
  
  private function set_start_line($value) {
      if (is_numeric($value) && $value > 0) {
          $this->start_line = $value;
      } else {
          throw new InvalidArgumentException('Start line must be a positive number');
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