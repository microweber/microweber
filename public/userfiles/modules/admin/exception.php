<?php defined('T') OR die(); headers_sent() OR header('HTTP/1.0 500 Internal Server Error'); ?>
<!doctype html>
<head>
	<meta charset="utf-8">
	<title><?php _e('Site Error'); ?></title>
	<style >
		#exception { font: 12px Arial; border: 1px solid #6d0019; padding: 0 2em; margin: 2em; }
		pre {background: #EDF8FC; border: 1px solid #D9F1F9; margin: 2em 0; padding: 1em;}
		h1 { color: #6d0019; }
	</style>
</head>
<body>
<div id="exception">
<h1><?php _e('Site Error'); ?></h1>
<p><?php

d($e);
 print $e->getMessage(); ?></p>

<?php if(c('debug_mode'))
{
	print '<p>In '. str_replace(dirname(__DIR__),'',$e->getFile()).' on line '. $e->getLine(). '</p>';
	foreach($e->getTrace() as $item){
		if(isset($item['file'])) $file = str_replace(dirname(__DIR__),'',$item['file']);
		$line = isset($item['line']) ? $item['line'] : '';
		print $file. ($line?" on line $line":'').'<br>';
		if(!empty($item['args'])) print dump($item['args']);
	}
}
?>
</div>
</body>
</html>
