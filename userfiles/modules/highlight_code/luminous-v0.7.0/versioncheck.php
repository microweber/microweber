<?php

/*
 * quick script checks whether or not we're running the current release by
 * fetching the most recent release data from the site API and comparing it
 * to the version we're running here
 */
include (dirname(__FILE__) . '/luminous.php');

$URL = 'http://luminous.asgaard.co.uk/index.php/ajax/luminous/version';
$DOWNLOAD_URL = 'http://luminous.asgaard.co.uk/index.php/download';
$cmd_line = PHP_SAPI === 'cli';
$version = LUMINOUS_VERSION;

function urlify($url) {
  global $cmd_line;
  return $cmd_line? $url : "<a href='$url'>$url</a>";
}

function _echo($string) {
  echo wordwrap($string, 79) . "\n";
}

if (!$cmd_line) _echo('<pre>');

if ($version === 'master') {
  _echo('You are using a development version, I cannot check how up to date it is.');
  _echo('You can download the latest stable release from '
    . urlify($DOWNLOAD_URL));
}
else {
  _echo('Checking version...');

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $URL);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Luminous ' . LUMINOUS_VERSION 
    . ' version check');
  $json = curl_exec($ch);
  curl_close($ch);

  if (!$json) {
    _echo('Remote request failed. Try again later or visit '
      . urlify($DOWNLOAD_URL) . ' to see what the latest version is');
  } else {
    $data = json_decode($json, true);
    if ($data['release_number'] === $version || 'v' . $data['release_number'] === $version) {
      _echo('You are up to date!');
    } else {
      $output = "You are not up to date: your version is " . $version
        . " and the most recent release is " . $data['release_number']
        . ", released " . $data['release_date'] . '. '
        . ' Visit ' . urlify($DOWNLOAD_URL) . ' to upgrade';
      _echo($output);
    }
  }
}
if (!$cmd_line) _echo('</pre>');
