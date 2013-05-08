<?php

/**
 * @cond CORE
 * @brief A set of utility functions for scanners
 */
class LuminousUtils {

  /**
   * @brief Tries to balance a delimiter
   * 
   * Tries to 'balance' a single character delimiter, i.e:
   *     '(' is mapped to ')'
   *     '{' is mapped to '}',
   *     '[' is mapped to ']',
   *     '<' is mapped to '>'
   * Any other character is mapped to itself.
   *
   * @param $delimiter the left/opening delimiter to try to balance
   * @return The corresponding close delimiter character, or the input
   *    character.
   */
  static function balance_delimiter($delimiter) {
    switch($delimiter) {
    case '(' : return ')';
    case '{' : return '}';
    case '[' : return ']';
    case '<' : return '>';
    default: return $delimiter;
    }
  }

  /**
   * @brief Escapes a string suitable for use in XML
   * 
   * Escapes a string according to the Luminous internal escaping format
   * (this is currently htmlspecialchars with ENT_NOQUOTES.)
   * @param $string the string to escape
   * @return the escaped string
   */
  static function escape_string($string) {
    return htmlspecialchars($string, ENT_NOQUOTES);
  }

  /**
   * @brief Escapes a token so its string is suitable for use in XML
   * 
   * Escapes a token. If the token is already escaped, nothing changes.
   * If the token is not escaped, the escaped flag is set (index 2) and the
   * token text (index 1) is escaped according to the internal escaping format
   * @param $token the token to escape
   * @return the escaped token
   */
  static function escape_token($token) {
    $esc = &$token[2];
    if (!$esc) {
      $str = &$token[1];
      $str = htmlspecialchars($str, ENT_NOQUOTES);
      $esc = true;
    }
    return $token;
  }

  /**
   * @brief Wraps a block of text in an XML tag
   * 
   * Tags a block of text. The block is assumed to have been escaped correctly
   * with LuminousUtils::escape_string.
   * @param $type the type to tag the string as, this is the token name
   * @param $block the block of text
   * @param $split_multiline if this is set to true, the tags are closed at
   *    the end of  each line and re-opened again on the next line. This is
   *    useful for output formats like HTML, where spanning multiple lines
   *    could break the markup
   * @return The tagged block of text. This resembles an XML fragment.
   */
  static function tag_block($type, $block, $split_multiline=true) {
    if ($type === null) return $block;
    $open = '<' . $type . '>';
    $close = '</' . $type . '>';
    if ($split_multiline)
      return $open . str_replace("\n", $close . "\n" . $open, $block) .
          $close;
    else
      return $open . $block . $close;
  }

  /**
   * @brief Decodes PCRE error codes to human readable strings
   * 
   * Decodes a PCRE error code, which was returned by preg_last_error(), to
   * something readable
   * @param $errcode the error code
   * @return the error description, as string. This is currently the same
   * as the constant name, so the constant PREG_NO_ERROR is mapped to the
   * string 'PREG_NO_ERROR'
   */
  static function pcre_error_decode($errcode) {
    switch ($errcode) {
      case PREG_NO_ERROR:
        return 'PREG_NO_ERROR';
      case PREG_INTERNAL_ERROR:
        return 'PREG_INTERNAL_ERROR';
      case PREG_BACKTRACK_LIMIT_ERROR:
        return 'PREG_BACKTRACK_LIMIT_ERROR';
      case PREG_RECURSION_LIMIT_ERROR:
        return 'PREG_RECURSION_LIMIT_ERROR';
      case PREG_BAD_UTF8_ERROR:
        return 'PREG_BAD_UTF8_ERROR';
      case PREG_BAD_UTF8_OFFSET_ERROR:
        return 'PREG_BAD_UTF8_OFFSET_ERROR';
      default:
        return "Unknown error code `$errcode'";
    }
  }
}
/// @endcond
// ends CORE