<?php
/**
 * @file
 * The main Traverser interface.
 */

namespace QueryPath\CSS;

/**
 * An object capable of walking (and searching) a datastructure.
 */
interface Traverser {
  /**
   * Process a CSS selector and find matches.
   *
   * This specifies a query to be run by the Traverser. A given
   * Traverser may, in practice, delay the finding until some later time
   * but must return the found results when getMatches() is called.
   *
   * @param string $selector
   *   A selector. Typically this is a CSS 3 Selector.
   * @retval object Traverser
   *  The Traverser that can return matches.
   */
  public function find($selector);
  /**
   * Get the results of a find() operation.
   *
   * Return an array of matching items.
   *
   * @retval array
   *   An array of matched values. The specific data type in the matches
   *   will differ depending on the data type searched, but in the core
   *   QueryPath implementation, this will be an array of DOMNode
   *   objects.
   */
  public function matches();
}
