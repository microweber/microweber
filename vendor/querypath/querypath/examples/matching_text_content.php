<?php
/**
 * Matching Text Content.
 *
 * This example shows one way of matching text content.
 * The `:contains()` pseudo-class requires that the ENTIRE CONTENTS
 * of an element match exactly. But sometimes what we want is a way
 * to match just part of the contents of an element. This example
 * illustrates how to accomplish this with a filter callback.
 *
 * As of QueryPath 2.1beta2, `:contains()` performs a substring match instead of 
 * and exact match, so the method outline below is roughly the same as merely
 * using `:contains(Release)`.
 *
 * 
 * @author M Butcher <matt@aleph-null.tv>
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 */

/** Include QueryPath. */
require_once '../src/QueryPath/QueryPath.php';

/**
 * Check if the string 'Release' is in the text content of any matched nodes.
 * 
 * Returns TRUE if the text is found, FALSE otherwise. Anytime a filter callback
 * returns FALSE, QueryPath will remove it from the matches.
 *
 * Note that $item is a DOMNode (actually, a DOMElement). So if we wanted to do QueryPath
 * manipulations on it, we could wrap it in a `qp()`.
 */
function exampleCallback($index, $item) {
  $text = qp($item)->text();
  return strpos($text, 'Release') !== FALSE;
}

/*
 * This is the QueryPath call.
 *
 * First we fetch the remote page, parse it, and grab just the `a` tags inside of the summary.
 * Then we filter the results through our callback.
 * Finally, we fetch all of the matching text and print it.
 *
 * NOTE: If you are using PHP 5.3, you can define the callback inline instead of separating it
 * into a stand-alone function.
 */
print htmlqp('http://php.net/', 'h1.summary a')
  ->filterCallback('exampleCallback')
  ->textImplode(PHP_EOL);
?>