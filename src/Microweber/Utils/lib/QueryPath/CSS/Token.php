<?php
/** @file
 * Parser tokens.
 */
namespace QueryPath\CSS;
/**
 * Tokens for CSS.
 * This class defines the recognized tokens for the parser, and also
 * provides utility functions for error reporting.
 *
 * @ingroup querypath_css
 */
final class Token {
  const char = 0;
  const star = 1;
  const rangle = 2;
  const dot = 3;
  const octo = 4;
  const rsquare = 5;
  const lsquare = 6;
  const colon = 7;
  const rparen = 8;
  const lparen = 9;
  const plus = 10;
  const tilde = 11;
  const eq = 12;
  const pipe = 13;
  const comma = 14;
  const white = 15;
  const quote = 16;
  const squote = 17;
  const bslash = 18;
  const carat = 19;
  const dollar = 20;
  const at = 21; // This is not in the spec. Apparently, old broken CSS uses it.

  // In legal range for string.
  const stringLegal = 99;

  /**
   * Get a name for a given constant. Used for error handling.
   */
  static function name($const_int) {
    $a = array('character', 'star', 'right angle bracket',
      'dot', 'octothorp', 'right square bracket', 'left square bracket',
      'colon', 'right parenthesis', 'left parenthesis', 'plus', 'tilde',
      'equals', 'vertical bar', 'comma', 'space', 'quote', 'single quote',
      'backslash', 'carat', 'dollar', 'at');
    if (isset($a[$const_int]) && is_numeric($const_int)) {
      return $a[$const_int];
    }
    elseif ($const_int == 99) {
      return 'a legal non-alphanumeric character';
    }
    elseif ($const_int == FALSE) {
      return 'end of file';
    }
    return sprintf('illegal character (%s)', $const_int);
  }
}
