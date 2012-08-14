<?php defined('T') OR die(); headers_sent() OR header('HTTP/1.0 404 Page Not Found'); ?>
<h1>404 Not Found</h1>
<p>Sorry, we could not find the page you requested.</p>
<code><?php print implode('/', url());?></code>