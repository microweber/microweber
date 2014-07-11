<?php
/**
 * Build a package.xml from the description in this file.
 */
 
require '../src/QueryPath/QueryPath.php';

$base_xml = './bin/base-package.xml';
$package_xml = './package-out.xml';

$dir['php'] = 'src/';
$dir['docs'] = 'docs/';
$dir['tests'] = 'test/';

chdir('../');

$base = qp($base_xml, 'contents');

foreach ($dir as $name => $dir) {
  $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveIteratorIterator::SELF_FIRST));
  $oldPath = $dir;
  foreach ($it as $o => $item) {
    $base->append('<file name="' . $o . '" role="' . $name . '"/>' . PHP_EOL);
  }
}

$base->writeXML();