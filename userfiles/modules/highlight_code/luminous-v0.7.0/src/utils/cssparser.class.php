<?php

/// \cond ALL
/*
 * This is a simple CSS parser, which we use to make CSS themes portable.
 * The basic idea is we're going to use the CSS scanner to tokenize the
 * input, then we're going to parse the tokens.
 * There is some amount of redundancy here with the scanner, but this way
 * means that we are 1) not dependent too much on the implementation of
 * the scanner, and 2) not having to write our own with full pattern matching.
 *
 */

require_once dirname(__FILE__) . '/../luminous.php';

// source: http://www.w3schools.com/css/css_colornames.asp
global $luminous_col2hex;
$luminous_col2hex = array(
  'aliceblue' => '#f0f8ff',
  'antiquewhite' => '#faebd7',
  'aqua' => '#00ffff',
  'aquamarine' => '#7fffd4',
  'azure' => '#f0ffff',
  'beige' => '#f5f5dc',
  'bisque' => '#ffe4c4',
  'black' => '#000000',
  'blanchedalmond' => '#ffebcd',
  'blue' => '#0000ff',
  'blueviolet' => '#8a2be2',
  'brown' => '#a52a2a',
  'burlywood' => '#deb887',
  'cadetblue' => '#5f9ea0',
  'chartreuse' => '#7fff00',
  'chocolate' => '#d2691e',
  'coral' => '#ff7f50',
  'cornflowerblue' => '#6495ed',
  'cornsilk' => '#fff8dc',
  'crimson' => '#dc143c',
  'cyan' => '#00ffff',
  'darkblue' => '#00008b',
  'darkcyan' => '#008b8b',
  'darkgoldenrod' => '#b8860b',
  'darkgray' => '#a9a9a9',
  'darkgrey' => '#a9a9a9',
  'darkgreen' => '#006400',
  'darkkhaki' => '#bdb76b',
  'darkmagenta' => '#8b008b',
  'darkolivegreen' => '#556b2f',
  'darkorange' => '#ff8c00',
  'darkorchid' => '#9932cc',
  'darkred' => '#8b0000',
  'darksalmon' => '#e9967a',
  'darkseagreen' => '#8fbc8f',
  'darkslateblue' => '#483d8b',
  'darkslategray' => '#2f4f4f',
  'darkslategrey' => '#2f4f4f',
  'darkturquoise' => '#00ced1',
  'darkviolet' => '#9400d3',
  'deeppink' => '#ff1493',
  'deepskyblue' => '#00bfff',
  'dimgray' => '#696969',
  'dimgrey' => '#696969',
  'dodgerblue' => '#1e90ff',
  'firebrick' => '#b22222',
  'floralwhite' => '#fffaf0',
  'forestgreen' => '#228b22',
  'fuchsia' => '#ff00ff',
  'gainsboro' => '#dcdcdc',
  'ghostwhite' => '#f8f8ff',
  'gold' => '#ffd700',
  'goldenrod' => '#daa520',
  'gray' => '#808080',
  'grey' => '#808080',
  'green' => '#008000',
  'greenyellow' => '#adff2f',
  'honeydew' => '#f0fff0',
  'hotpink' => '#ff69b4',
  'indianred' => '#cd5c5c',
  'indigo' => '#4b0082',
  'ivory' => '#fffff0',
  'khaki' => '#f0e68c',
  'lavender' => '#e6e6fa',
  'lavenderblush' => '#fff0f5',
  'lawngreen' => '#7cfc00',
  'lemonchiffon' => '#fffacd',
  'lightblue' => '#add8e6',
  'lightcoral' => '#f08080',
  'lightcyan' => '#e0ffff',
  'lightgoldenrodyellow' => '#fafad2',
  'lightgray' => '#d3d3d3',
  'lightgrey' => '#d3d3d3',
  'lightgreen' => '#90ee90',
  'lightpink' => '#ffb6c1',
  'lightsalmon' => '#ffa07a',
  'lightseagreen' => '#20b2aa',
  'lightskyblue' => '#87cefa',
  'lightslategray' => '#778899',
  'lightslategrey' => '#778899',
  'lightsteelblue' => '#b0c4de',
  'lightyellow' => '#ffffe0',
  'lime' => '#00ff00',
  'limegreen' => '#32cd32',
  'linen' => '#faf0e6',
  'magenta' => '#ff00ff',
  'maroon' => '#800000',
  'mediumaquamarine' => '#66cdaa',
  'mediumblue' => '#0000cd',
  'mediumorchid' => '#ba55d3',
  'mediumpurple' => '#9370d8',
  'mediumseagreen' => '#3cb371',
  'mediumslateblue' => '#7b68ee',
  'mediumspringgreen' => '#00fa9a',
  'mediumturquoise' => '#48d1cc',
  'mediumvioletred' => '#c71585',
  'midnightblue' => '#191970',
  'mintcream' => '#f5fffa',
  'mistyrose' => '#ffe4e1',
  'moccasin' => '#ffe4b5',
  'navajowhite' => '#ffdead',
  'navy' => '#000080',
  'oldlace' => '#fdf5e6',
  'olive' => '#808000',
  'olivedrab' => '#6b8e23',
  'orange' => '#ffa500',
  'orangered' => '#ff4500',
  'orchid' => '#da70d6',
  'palegoldenrod' => '#eee8aa',
  'palegreen' => '#98fb98',
  'paleturquoise' => '#afeeee',
  'palevioletred' => '#d87093',
  'papayawhip' => '#ffefd5',
  'peachpuff' => '#ffdab9',
  'peru' => '#cd853f',
  'pink' => '#ffc0cb',
  'plum' => '#dda0dd',
  'powderblue' => '#b0e0e6',
  'purple' => '#800080',
  'red' => '#ff0000',
  'rosybrown' => '#bc8f8f',
  'royalblue' => '#4169e1',
  'saddlebrown' => '#8b4513',
  'salmon' => '#fa8072',
  'sandybrown' => '#f4a460',
  'seagreen' => '#2e8b57',
  'seashell' => '#fff5ee',
  'sienna' => '#a0522d',
  'silver' => '#c0c0c0',
  'skyblue' => '#87ceeb',
  'slateblue' => '#6a5acd',
  'slategray' => '#708090',
  'slategrey' => '#708090',
  'snow' => '#fffafa',
  'springgreen' => '#00ff7f',
  'steelblue' => '#4682b4',
  'tan' => '#d2b48c',
  'teal' => '#008080',
  'thistle' => '#d8bfd8',
  'tomato' => '#ff6347',
  'turquoise' => '#40e0d0',
  'violet' => '#ee82ee',
  'wheat' => '#f5deb3',
  'white' => '#ffffff',
  'whitesmoke' => '#f5f5f5',
  'yellow' => '#ffff00',
  'yellowgreen' => '#9acd32'
);




/**
 * @brief Simple CSS parser to make theme files portable across output formats.
 * 
 * This is CSS parser for making Luminous themes portable. This is not
 * a general CSS parser, but could be with a bit of work!
 * 
 * Parses CSS strings into a map of rules and values. The resulting map is
 * a somewhat simplified version of CSS.
 * For simplificity we re-map the following properties:
 *
 * background-color => bgcolor 
 * font-weight =>  bold? (bool)
 * font-style => italic? (bool)
 * text-decoration => underline? OR strikethrough? (bool)
 *
 * We also drop things like '!important'.
 *
 * Colours are stored as 6-digit hexadecimal strings with leading #. 3-digit
 * hex strings are expanded to their 6-digit equivalents. Named colour aliases
 * are replaced with their hex equivalents.
 */
class LuminousCSSParser {

  private $data = array();

  private static function format_property_value($prop, $value) {
    global $luminous_col2hex;
    // drop !important      
    $value = preg_replace('/\s*!important$/', '', $value);
    // expand 3-digit hex
    if (preg_match('/^#([a-fA-F0-9]{3})$/', $value, $m))
      $value .= $m[1];
    // remove quotes
    $value = trim($value);
    if (preg_match('/^(["\'])(.*)\\1$/', $value, $m)) $value = $m[2];

    // now get it into a simpler form:
    switch($prop) {
      case 'color':
        if (isset($luminous_col2hex[$value]))
          $value = $luminous_col2hex[$value];
        break;
      case 'background-color':
        $prop = 'bgcolor';
        if (isset($luminous_col2hex[$value]))
          $value = $luminous_col2hex[$value];
        break;
      case 'font-weight':
        $prop = 'bold';
        $value = in_array($value, array('bold', 'bolder', '700', '800', '900'));
        break;
      case 'font-style':
        $prop = 'italic';
        $value = in_array($value, array('italic', 'oblique'));
      case 'text-decoration':
        if ($value === 'line-through') {
          $prop = 'strikethrough';
          $value = true;
        }
        elseif($value === 'underline') {
          $prop = 'underline';
          $value = true;
        }
        break;
    }
    return array($prop, $value);
  }


  private static function format_css_array($css) {
    $css_ = array();

    // now cleanup the array, drop !important
    foreach($css as $selector=>$rules) {
      $rules_ = array();
      foreach($rules as $prop=>$value) {
        list($prop, $value) = self::format_property_value($prop, $value);
        $rules_[$prop] = $value;
      }
      // now split selector by comma
      $selectors = preg_split('/\s*,\s*/', $selector);

      foreach($selectors as $s) {
        // drop .luminous from the selector
        $s = preg_replace('/^\.luminous\s*/', '', $s);
        // now we assume that if something is in the form .classname then
        // it's probably of interest, ie directly specifying a rule for a
        // highlighting calss.
        // and if it's not in that form them
        // it's probably something else (like a rule for hyperlinks or something)
        if (preg_match('/\.([\-\w]+)/', $s, $m)) $s = $m[1];
        else continue;
        if (!isset($css_[$s])) $css_[$s] = array();
        $css_[$s] = array_merge($css_[$s], $rules_);
      }
    }
    return $css_;
  
  }

  /**
   * Returns the parsed rules. The rules are an array in the format:
   *
   * array(
   *    $rule_name => array($property => $value)
   * )
   *
   * So, $rules['.comment']['color'] would return the color property of comment
   * classes.
   */
  function rules() {
    return $this->data;
  }

  /**
   * Returns the value for the given property of the given rule name, or
   * returns $default.
   * @param $rule_name the CSS rule name, e.g. 'a', '.luminous', etc
   * @param $property the property to access, e.g. 'color'
   * @param $default the value to return in the case that the rule/proeprty
   *     was not set. Default: null
   */
  function value($rule_name, $property, $default=null) {
    if (isset($this->data[$rule_name][$property]))
      return $this->data[$rule_name][$property];
    else return $default;
  }


  /**
   * Converts a CSS string into a nested map of values.
   * See LuminousCSSParser::rules() for the structure.
   * @param $string the CSS string
   * @returns the rule map
   */
  function convert($string) {
    $css = self::parse($string);
    $data = self::format_css_array($css);
    $this->data = $data;
    return $data;
  }

  private static function parse($string) {
    global $luminous_col2hex;
    // singleton from usage API
    global $luminous_;
    $scanner = $luminous_->scanners->GetScanner('css');
    $scanner->string($string);
    $scanner->init();
    $scanner->main();
    $tokens = $scanner->token_array();

    $block = false;
    $expect;
    $key;
    $value;
    $selector = '';

    $css = array();
    // array of selectors => rules, where rules is an array itself of (property, value)
    // note this is going to get @font-face wrong, but we don't care about that.
    for($i=0; $i<count($tokens); $i++) {
      list($tok, $content, ) = $tokens[$i];
      if ($tok === 'COMMENT') continue;
      if (!$block) {
        $expect = 'key';
        // not in block, look for selectors.
        if ($content === '{') {
          $block = true;
          $key = '';
          $selector = trim($selector);

          if (!isset($css[$selector]))
            $css[$selector] = array();
        }
        else {
          $selector .= $content;
        }
        continue;
      }
      if ($content === '}') {
        $block = false;
        $value = null;
        $key = null;
        $selector = '';
        continue;
      }

      // expecting key, append to the key or finalise it
      if ($expect === 'key') {
        if ($content === ':') {
          $expect = 'value';
          $value = '';
        }
        else $key .= $content;
      } elseif($expect === 'value') {
        // expecting value, append to it or finalise and insert it (with the key)
        if ($content === ';') {
          // don't overwrite things - use the first definition
          // this is for stuff like rgba which might re-define something
          $key = trim($key);
          $value = trim($value);
          if (!isset($css[$selector][$key]))  {
            $css[$selector][$key] = $value;
          }
          $expect = 'key';
          $key = '';
          $value = '';
        }
        else $value .= $content;
      }
    }
    return $css;
  }
}
/// \endcond