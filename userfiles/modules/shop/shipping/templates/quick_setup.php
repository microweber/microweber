<?php

$driver = app()->shipping_manager->getDefaultDriver();

echo $driver->quickSetup();
?>
