<?php
/**
 * Retrieving remote RSS feeds.
 *
 * This file contains an example of how QueryPath can be used
 * to retrieve and parse a remote RSS feed. If PHP is configured to allow 
 * HTTP URLs for remote hosts in file manipulation functions, you can use
 * QueryPath to retrieve the remote file and parse it.
 *
 * In this example, we grab the RSS feed from remote server and 
 * parse it. From there, we make a list of hyperlinks, one for each item in 
 * the original feed.
 *
 * 
 * @author M Butcher <matt@aleph-null.tv>
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 */
require_once '../src/QueryPath/QueryPath.php';

// The URL of the remote RSS feed.
$remote = 'http://querypath.org/aggregator/rss/2/rss.xml';

// We will write the results into this document.
$out = qp(QueryPath::HTML_STUB, 'title')
  ->text('RSS Titles')
  ->top()
  ->find('body')
  ->append('<ul/>')
  ->children('ul');

// Load the remote document and loop through all of the items.
foreach (qp($remote, 'channel>item') as $item) {
  // Get title and link.
  $title = $item->find('title')->text();
  $link = $item->next('link')->text();
  
  // Do a little string building.
  $bullet = '<li><a href="' . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . '">' . $title . '</a></li>';
  
  // Add it to the output document.
  $out->append($bullet);
}

// Write the results.
$out->writeHTML();