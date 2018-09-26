<?php

require_once __DIR__.'/../vendor/autoload.php';

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->add('Recurr\\Test', __DIR__);
$classLoader->register(true);
