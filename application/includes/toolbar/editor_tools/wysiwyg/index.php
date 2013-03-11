<!DOCTYPE HTML>
<html>
<head>
 
{head}

<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/mw_framework.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/liveadmin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/wysiwyg.css"/>
<script>
    mwAdmin = true;
</script>
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


$(document).ready(function(){


$(".module").attr("contentEditable", false);

$(".edit:first").attr("contentEditable", true);

$(mwd.body).bind('keydown keyup keypress mouseup mousedown click paste selectstart', function(e){
  var el= $(e.target);

  if(mw.tools.hasClass(e.target.className, 'module') || mw.tools.hasParentsWithClass(e.target, 'module')){
    e.preventDefault();
  //  var curr =  mw.tools.hasClass(e.target.className, 'module') ? e.target : mw.tools.firstParentWithClass(e.target, 'module');

 }




  if(el.hasClass('edit')){
    el.addClass('changed');
  }
  else{
     $(mw.tools.firstParentWithClass(e.target, 'edit')).addClass('changed');
  }


});

 $(".module").each(function(){
    var curr = this;
    if($(curr).next().length == 0){
      _next = mwd.createElement('div');
      _next.className = 'mw-wysiwyg-module-helper';
      _next.innerHTML = '&nbsp;';
      $(curr).after(_next)
    }

    if($(curr).prev().length == 0){
      _prev = mwd.createElement('div');
      _prev.className = 'mw-wysiwyg-module-helper';
      _prev.innerHTML = '&nbsp;';
      $(curr).before(_prev)
    }

 });


})
 
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
    width: auto !important;
    height: auto !important;

}



.mw-plain-module-name {
	display: block;
	padding-top: 5px;
}

.mw-admin-editor{
    background: none;
}

.mw-wysiwyg-module-helper{
  min-height: 23px;
}

</style>
</head>
<body style="padding: 0;margin: 0;">
<?php mw_var('plain_modules', true);
  if(is_admin() == false){
    exit('Must be admin');
  }
 ?>

<div class="mw-admin-editor">
 <?php include INCLUDES_DIR . DS . 'toolbar' . DS ."wysiwyg_admin.php"; ?>
  <div class="mw-admin-editor-area" id="mw-iframe-editor-area" tabindex="0" autofocus="autofocus">{content}</div>
</div>
<? mw_var('plain_modules', false); ?>
</body>
</html>