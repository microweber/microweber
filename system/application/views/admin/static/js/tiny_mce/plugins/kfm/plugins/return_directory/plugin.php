<?php
$p=new kfmPlugin('return_url');
if(isset($_GET['return_directory'])) $p->addJavascript(file_get_contents(dirname(__FILE__).'/plugin_conditional.js'));
$kfm->addPlugin($p);
?>
