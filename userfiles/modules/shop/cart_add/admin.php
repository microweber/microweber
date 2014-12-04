<?php 

$for_id = false;
$for = 'content';
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
<?php if($for_id): ?>
<module type="custom_fields/admin" data-content-id="<?php print intval($for_id); ?>"  />
<?php endif; ?>




 
  <module type="admin/modules/templates"  />
</div>
