<?php
/**
 * Compare jQuery documentation to QueryPath documentation
 *
 * 
 * @author Emily Brand
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 * @see http://api.jquery.com/api/
 * 
 * TODO: add to the xml file
 */

require_once '../src/QueryPath/QueryPath.php';

/**
 * Add the link & class to each key to show in the left div.
 * 
 * @param String $v
 */
function addClasses($v) {
  return "<a href='".$_SERVER['PHP_SELF']."?key=$v'><span class='keyname'>$v</span></a><br />";
}

// The document skeleton
$qpdoc = htmlqp('doc.html', 'body');

$key = $_GET['key'];

// The jQuery categories that are used in QueryPath
$qparray = array('Tree Traversal', 'Child Filter', 'Attribute', 'Content Filter', 'Basic Filter', 
'Hierarchy', 'Basic', 'Filtering', 'Miscellaneous Traversing', 'DOM Insertion, Outside', 
'DOM Insertion, Inside', 'Attributes', 'Style Properties');

$jqnames = array();
$qpnames = array();

// Search through the xml file to find any entries of jQuery entities
foreach(qp('querypath.xml', 'entry') as $entry) {
  $qpnames[$entry->attr('name')] =  
      array('desc' => $entry->find('desc')->innerXML(), 
            'jquery' => $entry->parent()->find('jquery')->innerXML(), 
            'querypath' => $entry->parent()->find('querypath')->innerXML());
}

// Search through the xml file to find all entries of jQuery entities
foreach(htmlqp('http://api.jquery.com/api/', 'entry') as $entry) {
  $category = false;
  $category = array_search($entry->find('category:first')->attr('name'), $qparray);
  while($entry->next('category')->html() != null) {
    $category = (array_search($entry->attr('name'), $qparray)) ? true : $category;
    if($category) break;
  }
  if($category) {
    $jqnames[$entry->parent()->attr('name')] =  
      array('longdesc' => $entry->find('longdesc')->innerXML(), 
            'name' => $entry->parent()->find('category')->attr('name'));
  }
}

// Map the keys & sort them
$jqkeys = array_keys($jqnames);
$jqkeys = array_map("addClasses", $jqkeys);
sort($jqkeys);

// Add the keys to the nav bar
$qpdoc->find('#leftbody');
foreach($jqkeys as $k => $v) {
  $qpdoc->append($v);
}

// Add the description to the main window if the key exists
if(array_key_exists($key, $jqnames)) {
  if(array_key_exists($key, $qpnames)) {
    $qpdoc->top()->find('#rightfunction')->text('Function: '.ucfirst($key));
    $qpdoc->top()->find('#rightdesc')->text($qpnames[$key]['desc']);
    $qpdoc->top()->find('#righttitle')->text('How it\'s done in jQuery');
    $qpdoc->top()->find('#righttext')->text($qpnames[$key]['jquery']);
    $qpdoc->top()->find('#righttitle2')->text('How it\'s done in QueryPath');
    $qpdoc->top()->find('#righttext2')->text($qpnames[$key]['querypath']);
  }
  else {
    $qpdoc->top()->find('#rightfunction')->text('Function: '.ucfirst($key));
    $qpdoc->top()->find('#rightdesc')->remove();
    $qpdoc->top()->find('#righttitle')->text('jQuery Documentation');
    $qpdoc->top()->find('#righttext')->append($jqnames[$key]['longdesc']);
  }
}

// Write the document
$qpdoc->writeHTML();

