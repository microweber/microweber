<?php 

$for_id = false;
$for = 'content';

if(isset($params['rel'])){
	$params['rel_type'] = $params['rel'];
}


if(isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'post' and defined('POST_ID')){
	$for_id =  $params['content-id'] = POST_ID; 
	$for = 'content';
}

if(isset($params['rel_type']) and trim(strtolower(($params['rel_type']))) == 'page' and defined('PAGE_ID')){
	$for_id =  $params['content-id'] = PAGE_ID; 
	$for = 'content';
}






 ?>

<div class="module-live-edit-settings">
  <div id="tabsnav">
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
      <?php if($for_id): ?>
      <a href="javascript:;" class="mw-ui-btn active tabnav">Custom fields</a>
      <?php endif; ?>
      <a href="javascript:;" class="mw-ui-btn tabnav">Skin/Template</a> </div>
    <div class="mw-ui-box">
      <div class="mw-ui-box-content tabitem">
        <?php if($for_id): ?>
        <module type="custom_fields/admin" data-content-id="<?php print intval($for_id); ?>"  />
        <?php endif; ?>
      </div>
      <div class="mw-ui-box-content tabitem" style="display: none">
        <module type="admin/modules/templates"  />
      </div>
    </div>
  </div>
</div>
<script>
    $(document).ready(function(){
       mw.tabs({
          nav:'#tabsnav .mw-ui-btn-nav-tabs .tabnav',
          tabs:'#tabsnav .tabitem'
       });
    });
</script>