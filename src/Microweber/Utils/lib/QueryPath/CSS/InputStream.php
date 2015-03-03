<?php
/**
 * @file
 *
 * The CSS Input Stream abstraction.
 */

namespace QueryPath\CSS;

/**
 * Simple wrapper to turn a string into an input stream.
 * This provides a standard interface on top of an array of
 * characters.
 */
class InputStream {
  protected $stream = NULL;
  public $position = 0;
  /**
   * Build a new CSS input stream from a string.
   *
   * @param string
   *  String to turn into an input stream.
   */
  function __construct($string) {
    $this->stream = str_split($string);
  }
  /**
   * Look ahead one character.
   *
   * @return char
   *  Returns the next character, but does not remove it from
   *  the stream.
   */
  function peek() {
    return $this->stream[0];
  }
  /**
   * Get the next unconsumed character in the stream.
   * This will remove that character from the front of the
   * stream and return it.
   */
  function consume() {
    $ret = array_shift($this->stream);
    if (!empty($ret)) {
      $this->position++;
    }
    return $ret;
  }
  /**
   * Check if the stream is empty.
   * @return boolean
   *   Returns TRUE when the stream is empty, FALSE otherwise.
   */
  function isEmpty() {
    return count($this->stream) == 0;
  }
}
