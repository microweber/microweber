<?php
/// @cond ALL


/**
 * \file luminous_formatter.class.php
 * \brief Formatting logic -- converts Luminous output into displayable formats
 */

/**
 * \brief Abstract class to convert Luminous output into a universal format.
 *
 * Abstract base class to implement an output formatter. A formatter
 * will convert Luminous's tags into some kind of output (e.g. HTML), by
 * overriding the method Format().
 */
abstract class LuminousFormatter {
  /// Number of chars to wrap at
  public $wrap_length = 120;
  /// Don't use this yet.
  public $language_specific_tags = false;
  /**
   * Tab width, in spaces. If this is -1 or 0, tabs will not be converted. This
   * is not recommended as browsers may render tabs as different widths which
   * will break the wrapping.
   */
  public $tab_width = 2;

  /// Whether or not to add line numbering
  public $line_numbers = true;
  /// Number of first line
  public $start_line = 1;
  
  /// An array of lines to be highlighted initially, if the formatter supports
  /// it
  public $highlight_lines = array();

  /// sets whether or not to link URIs.
  public $link = true;

  /**
   * Height of the resulting output. This may or may not make any sense
   * depending on the output format.
   *
   * Use 0 or -1 for no limit.
   */
  public $height = 0;
  
  
  /**
   * The language of the source code being highlighted. Formatters may choose
   * to do something with this.
   */
  public $language = null;


  /**
   * The main method for interacting with formatter objects.
   * @param src the input string, which is of the form output by an instance of
   * Luminous.
   * @return The input string reformatted to some other specification.
   */
  public abstract function format($src);

  /**
   * If relevant, the formatter should implement this and use LuminousCSSParser
   * to port the theme.
   * @param $theme A CSS string representing the theme
   */
  public function set_theme($theme)
  {
  }

  /**
   * @internal
   * Handles line wrapping.
   * @param line the line which needs to be broken. This is a reference, which
   * will be operated upon. After calling, $line will have appropriate line
   * breaks to wrap to the given width, and will contain at least one line break
   * at the end.
   * @param wrap_length the width to wrap to.
   *
   * @return the number of lines it was broken up into (1 obviously means no
   *    wrapping occurred.).
   *
   * @todo wrap to indent? or not? hm.
   *
   */
  protected static function wrap_line(&$line, $wrap_length) {
    // The vast majority of lines will not need wrapping so it pays to
    // check this first.
    if ($wrap_length <= 0 || !isset($line[$wrap_length])
      || strlen(strip_tags($line)) < $wrap_length) {
      $line .= "\n";
      return 1;
    }

    $line_split = preg_split('/((?:<.*?>)|(?:&.*?;)|[ \t]+)/',
      $line, -1,   PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);


    $strlen = 0;
    $line_cpy = "";
    $num_lines = 1;

    $num_open = 0;
    foreach($line_split as $l) {

      $l0 = $l[0];
      if ($l0 === '<') {
        $line_cpy .= $l;
        continue;
      }

      $s = strlen($l);

      if($l0 === '&') {
        // html entity codes only count as 1 char.
        if(++$strlen > $wrap_length) {
          $strlen = 1;
          $line_cpy .= "\n";
          $num_lines++;
        }
        $line_cpy .= $l;

        continue;
      }
      if ($s+$strlen <= $wrap_length) {
        $line_cpy .= $l;
        $strlen += $s;
        continue;
      }

      if ($s <= $wrap_length) {
        $line_cpy .= "\n" . $l;
        $num_lines++;
        $strlen = $s;
        continue;
      }
      // at this point, the line needs wrapping.

      // bump us up to the next line
      $diff = $wrap_length-$strlen;

      $line_cpy .= substr($l, 0, $diff) . "\n";
      $l_ = substr($l, $diff);
      // now start copying.
      $strlen = 0;
      // this would probably be marginally faster if it did its own arithmetic
      // instead of calling strlen

      while (strlen($l_) > 0) {
        $strl = strlen($l_);
        $num_lines++;

        if ($strl > $wrap_length)  {
          $line_cpy .= substr($l_, 0, $wrap_length) . "\n";
          $l_ = substr($l_, $wrap_length);
        } else {
          $line_cpy .= $l_;
          $strlen = $strl;
          break;
        }
      }
    }
    $line = $line_cpy . "\n";

    return $num_lines;
  }
}


/// @endcond