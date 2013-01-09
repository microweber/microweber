<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/mw_framework.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/liveadmin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/wysiwyg.css"/>
<script>mw.require("tools.js");</script>
<script>mw.require("url.js");</script>
<script>mw.require("events.js");</script>
<script>mw.require("wysiwyg.js");</script>

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