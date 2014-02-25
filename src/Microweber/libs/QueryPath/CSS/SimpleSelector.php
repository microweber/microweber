<?php
/** @file
 *
 * A simple selector.
 *
 */

namespace QueryPath\CSS;

/**
 * Models a simple selector.
 *
 * CSS Selectors are composed of one or more simple selectors, where 
 * each simple selector may have any of the following components:
 *
 * - An element name (or wildcard *)
 * - An ID (#foo)
 * - One or more classes (.foo.bar)
 * - One or more attribute matchers ([foo=bar])
 * - One or more pseudo-classes (:foo)
 * - One or more pseudo-elements (::first)
 *
 * For performance reasons, this object has been kept as sparse as
 * possible.
 *
 * @since QueryPath 3.x
 * @author M Butcher
 *
 */
class SimpleSelector {

  const adjacent = 1;
  const directDescendant = 2;
  const anotherSelector = 4;
  const sibling = 8;
  const anyDescendant = 16;

  public $element;
  public $ns;
  public $id;
  public $classes = array();
  public $attributes = array();
  public $pseudoClasses = array();
  public $pseudoElements = array();
  public $combinator;

  public static function attributeOperator($code) {
    switch($code) {
      case EventHandler::containsWithSpace:
        return '~=';
      case EventHandler::containsWithHyphen:
         return '|=';
      case EventHandler::containsInString:
         return '*=';
      case EventHandler::beginsWith:
        return '^=';
      case EventHandler::endsWith:
        return '$=';
      default:
        return '=';
    }
  }

  public static function combinatorOperator($code) {
    switch ($code) {
      case self::adjacent:
        return '+';
      case self::directDescendant:
        return '>';
      case self::sibling:
        return '~';
      case self::anotherSelector:
        return ', ';
      case self::anyDescendant:
        return '   ';
    }
  }

  public function __construct() {
  }

  public function notEmpty() {
    return !empty($element)
      && !empty($id)
      && !empty($classes)
      && !empty($combinator)
      && !empty($attributes)
      && !empty($pseudoClasses)
      && !empty($pseudoElements)
    ;
  }

  public function __tostring() {
    $buffer = array();
    try {

      if (!empty($this->ns)) {
        $buffer[] = $this->ns; $buffer[] = '|';
      }
      if (!empty($this->element)) $buffer[] = $this->element;
      if (!empty($this->id)) $buffer[] = '#' . $this->id;
      if (!empty($this->attributes)) {
        foreach ($this->attributes as $attr) {
          $buffer[] = '[';
          if(!empty($attr['ns'])) $buffer[] = $attr['ns'] . '|';
          $buffer[] = $attr['name'];
          if (!empty($attr['value'])) {
            $buffer[] = self::attributeOperator($attr['op']);
            $buffer[] = $attr['value'];
          }
          $buffer[] = ']';
        }
      }
      if (!empty($this->pseudoClasses)) {
        foreach ($this->pseudoClasses as $ps) {
          $buffer[] = ':' . $ps['name'];
          if (isset($ps['value'])) {
            $buffer[] = '(' . $ps['value'] . ')';
          }
        }
      }
      foreach ($this->pseudoElements as $pe) {
        $buffer[] = '::' . $pe;
      }

      if (!empty($this->combinator)) {
        $buffer[] = self::combinatorOperator($this->combinator);
      }

    }
    catch (\Exception $e) {
     return $e->getMessage();
   }

   return implode('', $buffer);
  }

}
