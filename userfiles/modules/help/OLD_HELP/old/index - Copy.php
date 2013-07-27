<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>MW help</title>
<script src="<?php print $config['url_to_module'] ?>static/bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>static/help.css"/>
</head>

<body>
<div class="mw_help_nav">
  <?php 
 $path = $base_path = $config['path_to_module'].'docs'.DS;
 if(isset($_GET['from_path'])): ?>
  <?php $path .=html_entity_decode($_GET['from_path']).DS;  ?>
  <?php endif; ?>
  <?php  
 $dirs =  mw('Mw\Utils\Files')->dir_tree($path);
  $dirs = str_replace($base_path, '', $dirs);
 print $dirs  ;
   ?>
</div>
<form method="get">
<input name="kw" type="text"  <?php if(isset($_GET['kw'])): ?> value="<?php  print $_GET['kw'] ?>" <?php endif; ?> />
 <input name="search" type="submit" value="search" />
</form>
<div id="wrapper">
  <module type="help/browser" />
</div>
</body>
</html>