<!DOCTYPE HTML>
<html>
<head>
 
{head}

<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/mw_framework.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/liveadmin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/wysiwyg.css"/>
<script>typeof jQuery != 'object'? mw.require("jquery.js"): '';</script>
<script>mw.require("jquery-ui.js");</script>
<script>mw.require("tools.js");</script>
<script>mw.require("url.js");</script>
<script>mw.require("events.js");</script>
<script>mw.require("wysiwyg.js");</script>
<script>

$(window).load(function(){

    $("#mw-iframe-editor-area").height($(window).height()-60);
     __area = mwd.getElementById('mw-iframe-editor-area');
	// $('.edit').attr('contenteditable',true);
   $(window).resize(function(){
    $("#mw-iframe-editor-area").height($(window).height()-60);
});

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

.mw-admin-editor{
    background: none;
}

</style>
</head>
<body style="padding: 0;margin: 0;">
<?php mw_var('plain_modules', true);
  if(is_admin() == false){

  exit('must be admin');
  }
 ?>

<div class="mw-admin-editor">
 <?php include INCLUDES_DIR . DS . 'toolbar' . DS ."wysiwyg_admin.php"; ?>
  <div class="mw-admin-editor-area" id="mw-iframe-editor-area" contenteditable="true" tabindex="0" autofocus="autofocus">{content}</div>
</div>
<? mw_var('plain_modules', false); ?>
</body>
</html>