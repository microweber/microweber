<?php defined('T') OR die(); headers_sent() OR header('HTTP/1.0 500 Internal Server Error'); ?>
<h1>System Error</h1>
<p><?php print $e; ?></p>
<p>In <?php print str_replace(dirname(__DIR__),'',$f); ?> on line <?php print $l; ?></p>
