<?php
// very easy, set the format to html-full
require_once('helper.inc');
luminous::set('format', 'html-full');
echo luminous::highlight_file('php', 'themeswitcher.php', $use_cache);
