<?php
/**
 * Using QueryPath.
 *
 * This file contains an example of how QueryPath can be used
 * to generate XML.
 *
 * QueryPath's ability to handle arbitrary XML comes in handy. Fragments of HTML
 * can be composed as external XML documents, and then inserted selectively into
 * an HTML document as needed. Just remember: Ever XML document -- even just a 
 * string -- needs to begin with the XML declaration:
 * <code>
 * <? xml version="1.0"?>
 * </code>
 * (A space was inserted above to prevent the documentation renderer from 
 * misinterpreting it.)
 * 
 * @author M Butcher <matt@aleph-null.tv>
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 */
 
require_once '../src/QueryPath/QueryPath.php';


// Create a new XML document wrapped in a QueryPath.
// By default, it will point to the root element,
// <author/>
$record = qp('<?xml version="1.0"?><author></author>')
  // Add a new last name inside of author.
  ->append('<lastName>Dostoyevsky</lastName>')
  // Select all of the children of <author/>. In this case,
  // that is <lastName/>
  ->children()
  // Oh, wait... we wanted last name to be inside of a <name/> 
  // element. Use wrap to wrap the current element in something:
  ->wrap('<name/>')
  // And before last name, we want to add first name.
  ->before('<firstName/>')
  // Select first name
  ->prev()
  // Set the text of first name
  ->text('Fyodor')
  // And then after first name, add the patronymic
  ->after('<patronymic>Fyodorovich</patronymic>')
  // Now go back to the root element, the top of the document.
  ->top()
  // Add another tag -- origin.
  ->append('<origin>Russia</origin>')
  // turn the QueryPath contents back into a string. Since we are 
  // at the top of the document, the whole document will be converted
  // to a string.
  ->xml();

// Print our results.
print $record;