<?php
/**
 * Example of grabbing and parsing Linked Data from DBPedia.
 *
 * This example illustrates how QueryPath can be used to do the following:
 * 
 * - Make a robust HTTP connection to a remote server to fetch data.
 * - Using context to control the underlying stream.
 * - Working with Linked Data.
 * - Work with XML Namespaces in documents.
 *   * Using namespaces to access elements in selectors
 *   * Using namespaces to access attributes in selectors
 *   * Using namespaces to access attributes in XML methods.
 *
 * The code here connects to the DBPedia server and looks up the Linked
 * Data stored there for a particular Wikipedia entry (any Wikipedia
 * wiki name should work here).
 * 
 * 
 * @author M Butcher <matt@aleph-null.tv>
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 * @see http://www.w3.org/DesignIssues/LinkedData.html
 * @see http://dbpedia.org
 * @see sparql.php
 * @see musicbrainz.php
 */

require_once '../src/QueryPath/QueryPath.php';

// The URL to look up (any of these works):
$url = 'http://dbpedia.org/data/The_Beatles.rdf';
//$url = 'http://dbpedia.org/data/Swansea.rdf';
//$url = 'http://dbpedia.org/data/The_Lord_of_the_Rings.rdf';
// HTTP headers:
$headers = array(
  'Accept: application/rdf,application/rdf+xml;q=0.9,*/*;q=0.8',
  'Accept-Language: en-us,en',
  'Accept-Charset: ISO-8859-1,utf-8',
  'User-Agent: QueryPath/1.2',
);

// The context options:
$options = array(
  'http' => array(
    'method' => 'GET',
    'protocol_version' => 1.1,
    'header' => implode("\r\n", $headers),
  ),
);

// Create a stream context that will tell QueryPath how to 
// load the file.
$cxt = stream_context_create($options);

// Fetch the URL and select all rdf:Description elements.
// (Note that | is the CSS 3 equiv of colons for namespacing.)
// To add the context, we pass it in as an option to QueryPath.
$qp = qp($url, 'rdf|Description', array('context' => $cxt));
//$qp = qp('The_Beatles.rdf');

printf("There are %d descriptions in this record.\n", $qp->size());

// Here, we use rdf|* to select all elements in the RDF namespace.
$qp->top()->find('rdf|*');
printf("There are %d RDF items in this record.\n", $qp->size());

// Standard pseudo-classes that are not HTML specific can be used on 
// namespaced elements, too.
print "About: " . $qp->top()->find('rdfs|label:first')->text() . PHP_EOL;
print "About (FOAF): " . $qp->top()->find('foaf|name:first')->text() . PHP_EOL;

// Namespaced attributes can be retrieved using the same sort of delimiting.
print "\nComment:\n";
print $qp->top()->find('rdfs|comment[xml|lang="en"]')->text();
print PHP_EOL;

$qp->top();

print "\nImages:\n";
foreach ($qp->branch()->find('foaf|img') as $img) {
  // Note that when we use attr() we are using the XML name, NOT 
  // the CSS 3 name. So it is rdf:resource, not rdf|resource.
  // The same goes for the tag() function -- it will return 
  // the full element name (e.g. rdf:Description).
  print $img->attr('rdf:resource') . PHP_EOL;
}

print "\nImages Galleries:\n";
foreach ($qp->branch()->find('dbpprop|hasPhotoCollection') as $img) {
  print $img->attr('rdf:resource') . PHP_EOL;
}

print "\nOther Sites:\n";
foreach ($qp->branch()->find('foaf|page') as $img) {
  print $img->attr('rdf:resource') . PHP_EOL;
}

//$qp->writeXML();