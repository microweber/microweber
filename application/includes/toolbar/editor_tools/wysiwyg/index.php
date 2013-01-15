<!DOCTYPE HTML>
<html>
<head>
 
{head}

<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/mw_framework.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/liveadmin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/wysiwyg.css"/>
<script>mw.require("tools.js");</script>
<script>mw.require("url.js");</script>
<script>mw.require("events.js");</script>
<script>mw.require("wysiwyg.js");</script>
<script>

$(document).ready(function(){

$("#mw-iframe-editor-area").height($(window).height()-60)

});

</script>
<style>
*{
  margin: 0;
  padding: 0;
}

.module {
	display: block;
	padding: 10px;
	border: 1px solid #ccc;
	background: #efecec;
	text-align: center;
	margin: 5px;
	font-size: 11px;
}
.mw-plain-module-name {
	display: block;
	padding-top: 5px;
}
</style>
</head>
<body>
<?php mw_var('plain_modules', true);
  if(is_admin() == false){

  exit('must be admin');
  }
 ?>
 <?php include INCLUDES_DIR . DS . 'toolbar' . DS ."wysiwyg_admin.php"; ?>
<div class="mw-admin-editor">
  <div class="mw-admin-editor-area" id="mw-iframe-editor-area" contenteditable="true">{content}</div>
</div>
<? mw_var('plain_modules', false); ?>
</body>
</html>