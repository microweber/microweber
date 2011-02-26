<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>
<title><?=$title?></title>
<link href="<?=$abs?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?=$abs?>images/themes/<?=$theme?>/theme.css" rel="stylesheet" type="text/css" />
<link href="<?=$abs?>css/iframe.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<link rel="stylesheet" href="<?=$abs?>css/style_ie.css" type="text/css" media="all" />
<![endif]-->
<script src="<?=$abs?>js/libraries/jsl.js" type="text/javascript"></script>
<script src="<?=$abs?>js/application.js" type="text/javascript"></script>
<?=$includes?>
</head>
<body>
<div id="loading"><?=t('Loading...')?></div>
<div id="contents">
<div class="with-icon" id="error-message" <?=($QUERY['error']) ? '':'style="display:none;"';?>><?=stripslashes($QUERY['error'])?></div>
<div class="with-icon" id="success-message" <?=($QUERY['success']) ? '':'style="display:none;"';?>><?=stripslashes($QUERY['success'])?></div>
<!-- Begin Content -->
<?php 
/////////////////////////////////// The Template file will appear here ////////////////////////////

include($GLOBALS['template']->template); 

/////////////////////////////////// The Template file will appear here ////////////////////////////
?>
<!-- End Content -->
</div>
</body>
</html>