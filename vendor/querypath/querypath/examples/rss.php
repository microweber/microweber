<?php
/**
 * Using QueryPath to Generate an RSS feed.
 *
 * This file contains an example of how QueryPath can be used
 * to generate an RSS feed.
 *
 * It uses two stubs -- one for the main RSS file, and one for an RSS entry --
 * and it merges data into the stubs.
 *
 * The method exhibited here is one of the more primitive ways of templating 
 * information. See the {@link techniques.php techniques} example for multiple
 * methods of looping. An even more advanced method would be to use the 
 * {@link QPTPL} library. 
 *
 * 
 * @author M Butcher <matt@aleph-null.tv>
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 */
 
require_once '../src/QueryPath/QueryPath.php';

// This is the stub RSS document.
$rss_stub ='<?xml version="1.0"?>
<rss version="2.0" 
  xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
   <title></title>
   <link></link>
   <description></description>
   <language>en</language>
   <generator>QueryPath</generator>
   </channel>
</rss>
';

// This is the stub RSS element.
$rss_item_stub = '<?xml version="1.0"?>
<item>
  <title>Untitled</title>
  <link></link>
  <description>
  </description>
  <comments></comments>
  <category></category>
  <pubDate></pubDate>
  <guid isPermaLink="false"></guid>
</item>';

// Here are some dummy items. For the same of 
// simplicity, we are just using a nested array. Of
// course, this could be a database lookup or whatever.
$items = array(
  array(
    'title' => 'Item 1',
    'link' => 'http://example.com/item1',
    'description' => '<strong>This has embedded <em>HTML</em></strong>',
    'comments' => 'http://example.com/item1/comments',
    'category' => 'Some Term',
    'pubDate' => date('r'),
    'guid' => '123456-789',
  ),
  array(
    'title' => 'Item 2',
    'link' => 'http://example.com/item2',
    'description' => '<strong>This has embedded <em>HTML</em></strong>',
    'comments' => 'http://example.com/item2/comments',
    'category' => 'Some Other Term',
    'pubDate' => date('r'),
    'guid' => '123456-790',
  ),
);

// The main QueryPath, which holds the channel.
$qp = qp($rss_stub, 'title')
  ->text('A QueryPath RSS Feed')
  ->next('link')->text('http://example.com')
  ->next('description')->text('QueryPath: Find your way.')
  ->parent();

// For each element in the array above, we create a new 
// QueryPath and then populate the XML fragment with data.
foreach ($items as $item) {
  
  // Begin with the stub RSS item, with title currently selected.
  $qpi = qp($rss_item_stub, 'title')
    // Add a title.
    ->text($item['title']) 
    // Add a link. Note that we are giving no args to next() for the
    // sake of simplicity.
    ->next()->text($item['link'])
    // Go to next element and add a description. Note that the text()
    // call will automatically encode HTML. < will become &lt; and so on.
    ->next()->text($item['description'])
    // Go on down the list...
    ->next()->text($item['comments'])
    ->next()->text($item['category'])
    ->next()->text($item['pubDate'])
    ->next()->text($item['guid']);
    
  // Now we append it.
  $qp->append($qpi->top());
}

// If we were running this on a server, we would need to set the content
// type:
// header('Content-Type: application/rss+xml');

// Write the outpt as XML.
$qp->writeXML();
?>