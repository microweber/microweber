<!DOCTYPE html><?php defined('T') OR die(); ?>
<html>
<head>
	<meta charset="utf-8"> 
    <title>1kb MVC Framework</title>
	<style>
	body { margin: 0; padding: 0; font: 12px Arial; }
	#container { margin: 0 auto; width: 500px; }
	</style>
</head>
<body>
<div id="container">
	<div id="header">
		<h1>1kb MVC Framework</h1>
	</div>
	<div id="content">
	<?php 

	
		$a = core::url_title('asdas dasd as das d');
		//	$b = Model_Content::applyGlobalTemplateReplaceables('asdas dasd as das d','asdaaaas dasd as das d');

		 var_dump($a);
	?>
		<?php print $content; ?>
		
		<?php if(c('debug_mode')) include('debug.php'); ?>
	</div>
</div>
</body>
</html>