<?php

/*
 * This file is part of Component Installer.
 *
 * (c) Rob Loach (http://robloach.net)
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

error_reporting(E_ALL);

// Add the Component Installer test paths.
$loader = require __DIR__ . '/../src/bootstrap.php';
$loader->add('ComponentInstaller\Test', __DIR__);

// Allow use of the Composer\Test namespace.
$path = $loader->findFile('Composer\\Composer');
$loader->add('Composer\Test', dirname(dirname(dirname($path))) . '/tests');
