<script>

    if(typeof __smart_field_opener !== 'function'){
          __smart_field_opener = function(e){
			  if(e === undefined){
				return;	
				}
            if(mw.tools.hasClass(e.target.className, 'mw-ui-field') || mw.tools.hasClass(e.target.className, 'mw-custom-fields-from-page-title-text')){
                mw.tools.toggle('.custom_fields_selector', '#smart_field_opener');
				
            }
          }
    }

</script>
<?php
  $for = 'module';
 if(isset($params['for'])){
	$for = $params['for'];
 }
$list_preview = false;
 $live_edit = false;
  if(isset($params['live_edit'])){
	$live_edit = $params['live_edit'];
 }

  if(isset($params['rel_id'])){
	 $params['for_module_id'] = $params['rel_id'];
 } elseif(!isset($params['for_module_id'])){
	 if(isset($params['id'])){
		 $params['for_module_id'] = $params['id'];
	 }

	 if(isset($params['data-id'])){

		 $params['for_module_id'] = $params['data-id'];
	 }
 }

if(isset($params['list-preview']) and $params['list-preview'] != 'false'){
  $list_preview = true;
}
 $diff = false;

  if(isset($params['save_to_content_id'])){
		 //
			 $diff = get_custom_fields($for,$params['save_to_content_id'],1,false,false, false, true);

		  }
		  

	$suggest_from_rel = false;
 
	if(isset($params['suggest-from-related']) and $params['suggest-from-related'] != 'false'){
	  $suggest_from_rel = true;
	}
	  


?>
<?php
 

$data = array();
 if(isset($params['for_module_id'])): ?>
<?php
if(isset($params['default-fields'])){
	mw()->fields_manager->make_default($for,$params['for_module_id'],$params['default-fields']);
}
   
	$more = get_custom_fields($for,$params['for_module_id'],1,false,false);
 
 if($suggest_from_rel == true){
	 $par =array();
	 $par['rel_type'] = $for;
	 $more = get_custom_fields($for,'all',1,false,false);
	 $have = array();
	  if(isset($diff) and is_array($diff)){
		$i=0;
		 foreach($diff as $item){
			if(isset($item['name']) and in_array($item['name'],$have)){
				 unset($diff[$i]);
			} else if(isset($diff[$i]) and isset($item['name'])){
				$have[] = $item['name'];
			 }
			 $i++; 
		 }
	 }
	 if(is_array($more)){
		$i=0;
		 foreach($more as $item){
			if(isset($item['name']) and in_array($item['name'],$have)){
				 unset($more[$i]);
			} else if(isset($more[$i]) and isset($item['name'])){
				$have[] = $item['name'];
			 }
			 $i++; 
		 }
	 }
	 
 }
 $custom_names_for_content = array();
if(is_array( $diff) and is_array($more) ){
     $i=0;
	 foreach($more as $item2){
	      foreach($diff as $item1){
			  if(isset($more[$i]) and isset($item1['copy_of_field'])){
				  if($item1['copy_of_field'] == $item2['id']){
				    unset($more[$i]);
				  }
			  }
              if(isset($more[$i]) and isset($item1['name'])){
                if($item1['name'] == $item2['name']){
                  unset($more[$i]);
                }
              }
		  }
		  $i++;
	 }
}
 if(!empty($data)){
	//$more = $data;
 }
 
?>
<?php if(!empty( $more)):  ?>
<?php if($list_preview == false): ?>

<div class="mw-ui-field mw-tag-selector mw-custom-fields-tags" onclick="__smart_field_opener(event)">
  <?php if(isset($params['save_to_content_id']) and isset($params["rel_id"]) and intval(($params["rel_id"]) > 0)): ?>
  <?php $p = get_content_by_id($params["rel_id"]); ?>
  <?php if(isset($p['title'])): ?>
  <div class="mw-custom-fields-from-page-title"> <span class="mw-custom-fields-from-page-title-text">
    <?php _e("From"); ?>
    <strong><?php print $p['title'] ?></strong></span> </div>
  <?php endif; ?>
  <?php endif; ?>
  <?php foreach( $more as $field): ?>
  <?php if(isset($params['save_to_content_id'])): ?>
  <a class="mw-ui-btn mw-ui-btn-small mw-field-type-<?php print $field['type']; ?>" href="javascript:;"
    onmouseup="mw.custom_fields.copy_field_by_id('<?php print $field['id'] ?>', 'content', '<?php print intval($params['save_to_content_id']); ?>');"><span class="ico ico-<?php print $field['type']; ?>"></span><?php print ($field['title']); ?> </a>
  <?php else: ?>
  <a class="mw-ui-btn mw-ui-btn-small mw-field-type-<?php print $field['type']; ?>" href="javascript:;"
    data-id="<?php print $field['id'] ?>"
    id="custom-field-<?php print $field['id'] ?>"
    onmouseup="mw.custom_fields.edit('.mw-admin-custom-field-edit-item','<?php print $field['id'] ?>', false, event);"> <span class="ico ico-<?php print $field['type'] ?>"></span> <span onclick="mw.admin.custom_fields.del(<?php print $field['id'] ?>, this.parentNode);" class="mw-icon-close"></span> <?php print ($field['title']); ?> </a>
  <?php endif; ?>
  <?php endforeach; ?>
</div>
<?php else : ?>
<table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-table" id="custom-fields-post-table">
  <colgroup>
    <col width="40">
    <col>
    <col>
    <col width="40">
    <col width="40">
  </colgroup>
  <thead>
    <tr>
      <th><?php _e("Type"); ?></th>
      <th><?php _e("Name"); ?></th>
      <th><?php _e("Value"); ?></th>
      <th><?php _e("Settings"); ?></th>
      <th><?php _e("Delete"); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach( $more as $field): ?>
    <tr id="mw-custom-list-element-<?php print $field['id']; ?>" data-id="<?php print $field['id']; ?>">
      <td data-tip="<?php print  ucfirst($field['type']); ?>" class="tip custom-field-icon" data-tipposition="top-left"><div><span class="mw-custom-field-icon-<?php print $field['type']; ?>"></span></div></td>
      <td data-id="<?php print $field['id']; ?>"><span class="mw-admin-custom-field-name-edit-inline" data-id="<?php print $field['id']; ?>"><?php print $field['name']; ?></span></td>
      <td data-id="<?php print $field['id']; ?>" width="100%">
      
    
      
      
        <div id="mw-custom-fields-list-preview-<?php print $field['id']; ?>" class="mw-custom-fields-list-preview">
       
          <module type="custom_fields/values_preview" field-id="<?php print $field['id']; ?>" id="mw-admin-custom-field-edit-item-preview-<?php print $field['id']; ?>" />
        </div>
        <div id="mw-custom-fields-list-settings-<?php print $field['id']; ?>" class="mw-admin-custom-field-edit-item-wrapper"><?php /*settings are loaded here*/ ?></div>
      </td>
      <td class="custom-fields-cell-settings">
        <a class="show-on-hover" href="javascript:mw.admin.custom_fields.edit_custom_field_item('#mw-custom-fields-list-settings-<?php print $field['id']; ?>',<?php print $field['id']; ?>);"><span class="mw-icon-gear"></span></a></td>
      <td class="custom-fields-cell-delete">
        <a class="show-on-hover" href="javascript:;" onclick="mw.admin.custom_fields.del(<?php print $field['id']; ?>,'#mw-custom-list-element-<?php print $field['id']; ?>');"><span class="mw-icon-close"></span></a>
     </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>



<script>
      mw.require('admin_custom_fields.js');
	  
</script>
<script>
      $(document).ready(function(){
		  
		if(typeof( mw.admin.custom_fields) != 'undefined'){
			 mw.admin.custom_fields.initValues();
		}
		  
       

        mw.$("#custom-fields-post-table tbody").sortable({
          handle:"td.custom-field-icon",
          axis:'y',
          update:function(){
                var _data = $(this).sortable('serialize');
                var xhr = $.post(mw.settings.api_url + 'fields/reorder', _data);
                xhr.success(function(){
                   <?php if(isset( $params['for_module_id'])){ ?>
                   mw.reload_module_parent('#<?php print $params['for_module_id']; ?>');
                   <?php } else { ?>
                   mw.reload_module_parent('#<?php print $params['id']; ?>');
                   <?php } ?>
                });
				 
				mw.custom_fields.after_save();
          }
        })
      });
</script>
<?php endif; ?>
<?php else : ?>
<?php if(!isset($params['save_to_content_id']) and $suggest_from_rel == false and $list_preview == false): ?>
<div class="mw-ui-field mw-tag-selector mw-custom-fields-tags" onclick="__smart_field_opener(event)">
  <div class="mw-custom-fields-from-page-title"> <span class="mw-custom-fields-from-page-title-text">
    <?php _e("You dont have any custom fields"); ?>
    . </span> </div>
</div>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
