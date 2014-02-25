<?php
/**
 * @file
 *
 * General IO exception.
 */

namespace QueryPath;

/**
 * Indicates that an input/output exception has occurred.
 *
 * @ingroup querypath_core
 */
class IOException extends \QueryPath\ParseException {
  public static function initializeFromError($code, $str, $file, $line, $cxt) {
    $class = __CLASS__;
    throw new $class($str, $code, $file, $line);
  }
}
