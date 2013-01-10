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
.module{
  display: block;
  padding: 10px;
  border: 1px solid #ccc;
  background: #efecec;
  text-align: center;
  margin: 5px;
  font-size: 11px;
}

.mw-plain-module-name{
  display: block;
  padding-top: 5px;
}

</style>

<?php
mw_var('plain_modules', true);
  if(is_admin() == false){

  exit('must be admin');
  }
   $content = '';
if(!isset($_REQUEST['id']) and intval($_REQUEST['id']) == 0 ){

    
} else {
$data =  get_content_by_id(intval($_GET['id']));
       if(isset($data['content'])){
                 $content = $data['content'];
       }
}



 ?>
<div class="mw-admin-editor">
    <?php include INCLUDES_DIR.'toolbar'.DS.'wysiwyg_admin.php'; ?>
    <div class="mw-admin-editor-area" id="mw-iframe-editor-area" contenteditable="true">
        <?php print $content; ?>
    </div>
  </div>
  <? mw_var('plain_modules', false); ?>