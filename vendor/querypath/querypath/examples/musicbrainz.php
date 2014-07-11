<?php
/**
 * Do an XML lookup from MusicBrainz.
 *
 * This example shows how to make a simple REST-style request against a remote
 * server. (For a more advanced example of HTML requests, see {@link sparql.php})
 *
 * This does two HTTP requests -- one to get information about a band, and another
 * to get a list of albums put out by that band.
 * 
 * TODO: Fix the output.
 *
 * 
 * @author M Butcher <matt@aleph-null.tv>
 * @license LGPL The GNU Lesser GPL (LGPL) or an MIT-like license.
 * @see http://musicbrainz.org
 */
require_once '../src/QueryPath/QueryPath.php';

$artist_url = 'http://musicbrainz.org/ws/1/artist/?type=xml&name=u2';
$album_url = 'http://musicbrainz.org/ws/1/release/?type=xml&artistid=';
try {
  $artist = qp($artist_url, 'artist:first');
  if ($artist->size() > 0) {
    $id = $artist->attr('id');
    print '<p>The best match we found was for ' . $artist->children('name')->text() . PHP_EOL;
    print '</p><p>Artist ID: ' . $id . PHP_EOL;
    print '</p><p>Albums for this artist' . PHP_EOL;
    print '</p><p><a href="'.$album_url . urlencode($id).'">'.$album_url.'</a></p>';
    $albums = qp($album_url . urlencode($id))->writeXML();
    
    foreach ($albums as $album) {
      print $album->find('title')->text() . PHP_EOL;
      // Fixme: Label is broken. See Drupal QueryPath module.
      print '(' . $album->next('label')->text() . ')' .PHP_EOL;
    }
  }
}
catch (Exception $e) {
  print $e->getMessage();
}
