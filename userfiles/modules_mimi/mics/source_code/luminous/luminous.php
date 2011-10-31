<?php

/*
 * If we're running in a web environment, this is simply an include
 * file which includes everything necessary to use Luminous.
 *
 * If we're running in CLI-mode then this handles the CLI interface.
 *
 * If you are including Luminous from a cli application then you should bypass
 * this file and include src/luminous.php.
 */

require_once(dirname(__FILE__) . '/src/luminous.php');

if (PHP_SAPI === 'cli') {
  // cli mode
  if (isset($argv[0]) && $argv[0] === basename(__FILE__)) 
    require(dirname(__FILE__) . '/src/cli.php');
}
