<?php
/**
 * @file
 * Query path parsing exception.
 */

namespace QueryPath;

/**
 * Exception indicating that a parser has failed to parse a file.
 *
 * This will report parser warnings as well as parser errors. It should only be
 * thrown, though, under error conditions.
 *
 * @ingroup querypath_core
 */
class ParseException extends \QueryPath\Exception {
  const ERR_MSG_FORMAT = 'Parse error in %s on line %d column %d: %s (%d)';
  const WARN_MSG_FORMAT = 'Parser warning in %s on line %d column %d: %s (%d)';
  // trigger_error
  public function __construct($msg = '', $code = 0, $file = NULL, $line = NULL) {

    $msgs = array();
    foreach(libxml_get_errors() as $err) {
      $format = $err->level == LIBXML_ERR_WARNING ? self::WARN_MSG_FORMAT : self::ERR_MSG_FORMAT;
      $msgs[] = sprintf($format, $err->file, $err->line, $err->column, $err->message, $err->code);
    }
    $msg .= implode("\n", $msgs);

    if (isset($file)) {
      $msg .= ' (' . $file;
      if (isset($line)) $msg .= ': ' . $line;
      $msg .= ')';
    }

    parent::__construct($msg, $code);
  }

  public static function initializeFromError($code, $str, $file, $line, $cxt) {
    //printf("\n\nCODE: %s %s\n\n", $code, $str);
    $class = __CLASS__;
    throw new $class($str, $code, $file, $line);
  }
}
