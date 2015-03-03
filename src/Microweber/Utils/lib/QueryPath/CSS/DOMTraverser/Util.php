<?php
/**
 * @file
 *
 * Utilities for DOM traversal.
 */
namespace QueryPath\CSS\DOMTraverser;

use \QueryPath\CSS\EventHandler;

/**
 * Utilities for DOM Traversal.
 */
class Util {

  /**
   * Check whether the given DOMElement has the given attribute.
   */
  public static function matchesAttribute($node, $name, $value = NULL, $operation = EventHandler::isExactly) {
    if (!$node->hasAttribute($name)) {
      return FALSE;
    }

    if (is_null($value)) {
      return TRUE;
    }

    return self::matchesAttributeValue($value, $node->getAttribute($name), $operation);
  }
  /**
   * Check whether the given DOMElement has the given namespaced attribute.
   */
  public static function matchesAttributeNS($node, $name, $nsuri, $value = NULL, $operation = EventHandler::isExactly) {
    if (!$node->hasAttributeNS($nsuri, $name)) {
      return FALSE;
    }

    if (is_null($value)) {
      return TRUE;
    }

    return self::matchesAttributeValue($value, $node->getAttributeNS($nsuri, $name), $operation);
  }

  /**
   * Check for attr value matches based on an operation.
   */
  public static function matchesAttributeValue($needle, $haystack, $operation) {

    if (strlen($haystack) < strlen($needle)) return FALSE;

    // According to the spec:
    // "The case-sensitivity of attribute names in selectors depends on the document language."
    // (6.3.2)
    // To which I say, "huh?". We assume case sensitivity.
    switch ($operation) {
      case EventHandler::isExactly:
        return $needle == $haystack;
      case EventHandler::containsWithSpace:
        // XXX: This needs testing!
        return preg_match('/\b/', $haystack) == 1;
        //return in_array($needle, explode(' ', $haystack));
      case EventHandler::containsWithHyphen:
        return in_array($needle, explode('-', $haystack));
      case EventHandler::containsInString:
        return strpos($haystack, $needle) !== FALSE;
      case EventHandler::beginsWith:
        return strpos($haystack, $needle) === 0;
      case EventHandler::endsWith:
        //return strrpos($haystack, $needle) === strlen($needle) - 1;
        return preg_match('/' . $needle . '$/', $haystack) == 1;
    }
    return FALSE; // Shouldn't be able to get here.
  }

  /**
   * Remove leading and trailing quotes.
   */
  public static function removeQuotes($str) {
    $f = substr($str, 0, 1);
    $l = substr($str, -1);
    if ($f === $l && ($f == '"' || $f == "'")) {
      $str = substr($str, 1, -1);
    }
    return $str;
  }

  /**
   * Parse an an+b rule for CSS pseudo-classes.
   *
   * Invalid rules return `array(0, 0)`. This is per the spec.
   *
   * @param $rule
   *  Some rule in the an+b format.
   * @retval array
   *  `array($aVal, $bVal)` of the two values.
   */
  public static function parseAnB($rule) {
    if ($rule == 'even') {
      return array(2, 0);
    }
    elseif ($rule == 'odd') {
      return array(2, 1);
    }
    elseif ($rule == 'n') {
      return array(1, 0);
    }
    elseif (is_numeric($rule)) {
      return array(0, (int)$rule);
    }

    $regex = '/^\s*([+\-]?[0-9]*)n\s*([+\-]?)\s*([0-9]*)\s*$/';
    $matches = array();
    $res = preg_match($regex, $rule, $matches);

    // If it doesn't parse, return 0, 0.
    if (!$res) {
      return array(0, 0);
    }

    $aVal = isset($matches[1]) ? $matches[1] : 1;
    if ($aVal == '-') {
      $aVal = -1;
    }
    else {
      $aVal = (int) $aVal;
    }

    $bVal = 0;
    if (isset($matches[3])) {
      $bVal = (int) $matches[3];
      if (isset($matches[2]) && $matches[2] == '-') {
        $bVal *= -1;
      }
    }
    return array($aVal, $bVal);
  }

}
