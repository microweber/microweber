<?php
/**
 * Use QueryPath's database extension to import XML data into a database.
 *
 * 
 * @author M Butcher <matt@aleph-null.tv>
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 */

require_once '../src/QueryPath/QueryPath.php';
require_once '../src/QueryPath/Extension/QPDB.php';

// Set the default database.
QPDB::baseDB('sqlite:../test/db/qpTest.db');

// To begin, let's create a new database. We can do this outside
// of QueryPath:
$db = QPDB::getBaseDB();
$db->exec('CREATE TABLE IF NOT EXISTS qpdb_article (title, author, body)');

// Here's our sample article:
$article = '<?xml version="1.0"?>
<article>
  <title>Use QueryPath for Fun and Profit</title>
  <author>
    <first>Matt</first>
    <last>Butcher</last>
  </author>
  <body>
  <![CDATA[
  <p>QueryPath is a great tool.</p>
  <p>Use it in many ways.</p>
  ]]>
  </body>
</article>';

// Now let's take this article and insert it into the database:
$qp = qp($article);

// We are going to store our insert params in here.
$params = array();

// First, let's get the title
$params[':title'] = $qp->find('title')->text();

// Next, let's get the name:
$params[':name'] = $qp->top()->find('author>last')->text() . ', ' . $qp->prev('first')->text();

// Finally, let's get the article content:
$params[':body'] = $qp->top()->find('body')->text();

// Here's the query we are going to run:
$sql = 'INSERT INTO qpdb_article (title, author, body) VALUES (:title, :name, :body)';

// Now we can insert this:
$qp->query($sql, $params);

// Finally, we can now read this information back out into an HTML document
qp(QueryPath::HTML_STUB, 'body')->queryInto('SELECT * FROM qpdb_article')->writeHTML();

// Finally, we clean up:
$qp->exec('DROP TABLE qpdb_article');