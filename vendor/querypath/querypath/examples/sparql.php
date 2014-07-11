<?php
/**
 * Use QueryPath to query SPARQL endpoints on semantic web servers.
 *
 * This example runs a SPARQL query against a remote database server
 * ({@link http://dbpedia.org}) and then parses the returned XML data, 
 * displaying it as an HTML table.
 *
 * This demo shows how a more complex GET query can be built up in 
 * QueryPath. POST queries are supported, too. Use a stream context
 * to create those.
 *
 * 
 * @author M Butcher <matt@aleph-null.tv>
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 * @see http://www.w3.org/2009/sparql/wiki/Main_Page
 * @see http://dbpedia.org
 * @see dbpedia.php
 * @see musicbrainz.php
 * @see http://drupal.org/project/querypath
 */
 
require '../src/QueryPath/QueryPath.php';

// We are using the dbpedia database to execute a SPARQL query.

// URL to DB Pedia's SPARQL endpoint.
$url = 'http://dbpedia.org/sparql';

// The SPARQL query to run.
$sparql = '
  PREFIX foaf: <http://xmlns.com/foaf/0.1/>
  PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
  SELECT ?uri ?name ?label
  WHERE {
    ?uri foaf:name ?name .
    ?uri rdfs:label ?label
    FILTER (?name = "The Beatles")
    FILTER (lang(?label) = "en")
  }
';

// We first set up the parameters that will be sent.
$params = array(
  'query' => $sparql,
  'format' => 'application/sparql-results+xml',
);

// DB Pedia wants a GET query, so we create one.
$data = http_build_query($params);
$url .= '?' . $data;

// Next, we simply retrieve, parse, and output the contents.
$qp = qp($url, 'head');

// Get the headers from the resulting XML.
$headers = array();
foreach ($qp->children('variable') as $col) {
  $headers[] = $col->attr('name');
}

// Get rows of data from result.
$rows = array();
$col_count = count($headers);
foreach ($qp->top()->find('results>result') as $row) {
  $cols = array();
  $row->children();
  for ($i = 0; $i < $col_count; ++$i) {
    $cols[$i] = $row->branch()->eq($i)->text();
  }
  $rows[] = $cols;
}

// Turn data into table.
$table = '<table><tr><th>' . implode('</th><th>', $headers) . '</th></tr>';
foreach ($rows as $row) {
  $table .= '<tr><td>';
  $table .= implode('</td><td>', $row);
  $table .= '</td></tr>';
}
$table .= '</table>';

// Add table to HTML document.
qp(QueryPath::HTML_STUB, 'body')->append($table)->writeHTML();