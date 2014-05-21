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
 }

   if(!isset($params['for_module_id'])){
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
	mw('fields')->make_default($for,$params['for_module_id'],$params['default-fields']);
}

	$more = get_custom_fields($for,$params['for_module_id'],1,false,false);

 if($suggest_from_rel == true){
	 $par =array();
	 $par['rel'] = $for;
	 $more = get_custom_fields($for,'all',1,false,false);
	 $have = array();
	  if(isset($diff) and is_array($diff)){
		$i=0;
		 foreach($diff as $item){
			if(isset($item['custom_field_name']) and in_array($item['custom_field_name'],$have)){
				 unset($diff[$i]);
			} else if(isset($diff[$i]) and isset($item['custom_field_name'])){
				$have[] = $item['custom_field_name'];
			 }
			 $i++; 
		 }
	 }
	 if(is_array($more)){
		$i=0;
		 foreach($more as $item){
			if(isset($item['custom_field_name']) and in_array($item['custom_field_name'],$have)){
				 unset($more[$i]);
			} else if(isset($more[$i]) and isset($item['custom_field_name'])){
				$have[] = $item['custom_field_name'];
			 }
			 $i++; 
		 }
	 }
	 
 }
 $custom_custom_field_names_for_content = array();
if(is_array( $diff) and is_array($more) ){
    $i=0;
	 foreach($more as $item2){

	 foreach($diff as $item1){
			  if(isset($more[$i]) and isset($item1['copy_of_field'])){
				  if($item1['copy_of_field'] == $item2['id']){
				//print $item1['copy_of_field'];
				 unset($more[$i]);
				  }
			  }
			    if(isset($more[$i]) and isset($item1['custom_field_name'])){
				  if($item1['custom_field_name'] == $item2['custom_field_name']){
				//print $item1['copy_of_field'];
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
  <a class="mw-ui-btn mw-ui-btn-small mw-field-type-<?php print $field['custom_field_type']; ?>" href="javascript:;"
    onmouseup="mw.custom_fields.copy_field_by_id('<?php print $field['id'] ?>', 'content', '<?php print intval($params['save_to_content_id']); ?>');"><span class="ico ico-<?php print $field['custom_field_type']; ?>"></span><?php print ($field['title']); ?> </a>
  <?php else: ?>
  <a class="mw-ui-btn mw-ui-btn-small mw-field-type-<?php print $field['custom_field_type']; ?>" href="javascript:;"
    data-id="<?php print $field['id'] ?>"
    id="custom-field-<?php print $field['id'] ?>"
    onmouseup="mw.custom_fields.edit('.mw-admin-custom-field-edit-item','<?php print $field['id'] ?>', false, event);"> <span class="ico ico-<?php print $field['custom_field_type'] ?>"></span> <span onclick="mw.custom_fields.del(<?php print $field['id'] ?>, this.parentNode);" class="mw-ui-btnclose"></span> <?php print ($field['title']); ?> </a>
  <?php endif; ?>
  <?php endforeach; ?>
</div>
<?php else : ?>
<?php if(isset($more) and !empty($more)): ?>
<?php endif; ?>
<table width="100%" cellspacing="0" cellpadding="0" id="custom-fields-post-table">
  <thead>
    <tr>
      <th width="20%">Name</th>
      <th>Value</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach( $more as $field): ?>
    <tr>
      <td  data-id="<?php print $field['id']; ?>" xondblclick="mw.custom_fields.edit('.mw-admin-custom-field-edit-item','<?php print $field['id'] ?>', false);"><span class="mw-admin-custom-field-name-edit-inline" data-id="<?php print $field['id']; ?>"><?php print $field['custom_field_name']; ?></span></td>
      <td  data-id="<?php print $field['id']; ?>" xondblclick="mw.custom_fields.edit('.mw-admin-custom-field-edit-item','<?php print $field['id'] ?>', false);"><?php if(isset($field['custom_field_type']) and ( $field['custom_field_type'] == 'select' or $field['custom_field_type'] == 'dropdown' or $field['custom_field_type'] == 'radio')): ?>
        <?php if(isset($field['custom_field_values']) and is_array($field['custom_field_values'])): ?>
        <?php $vals =  $field['custom_field_values']; ?>
        <?php elseif(isset($field['custom_field_value']) and is_array($field['custom_field_value'])): ?>
        <?php $vals =  $field['custom_field_values']; ?>
        <?php endif; ?>
        <?php $i=0; foreach($vals as $val): ?>
        <?php $i++; ?>
        <span class="mw-admin-custom-field-value-edit-inline"><?php print $val; ?></span>
        <?php if(isset($vals[$i])) : ?>,<?php endif; ?>
        <?php endforeach; ?>
        <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-icon btn-create-custom-field-value"><span class="mw-icon-plus"></span></span>
        <?php else: ?>
        <?php if($field['custom_field_values_plain'] != ''): ?>
        <?php  print $field['custom_field_values_plain']; ?>
        <?php elseif(is_string($field['custom_field_value']) and $field['custom_field_value'] != ''): ?>
        <?php  print $field['custom_field_value']; ?>
        <?php else: ?>
        <?php endif; ?>
        <?php endif; ?></td>
    </tr>
    <?php endforeach; ?>
    <script>




        initValues = function(all){
            var all = all || mwd.getElementById('custom-fields-post-table').querySelectorAll('.mw-admin-custom-field-name-edit-inline, .mw-admin-custom-field-value-edit-inline'),
                l = all.length,
                i = 0;
                for( ; i<l; i++){
                  if(!!all[i].fieldBinded){ continue }
                  initValue(all[i]);
                }
        }
        initValue = function(node){
           node.fieldBinded = true;
           node.onclick = function(){
            valueLiveEdit(this);
           }
        }
        valueLiveEdit = function(span){
            var input = mw.tools.liveEdit(span, true, function(el){
                       if(mw.tools.hasClass(el, 'mw-admin-custom-field-value-edit-inline')){
                           var vals = [],
                               all = el.parentNode.querySelectorAll('.mw-admin-custom-field-value-edit-inline'),
                               l = all.length,
                               i = 0;
                           for( ; i<l; i++){
                              vals.push(all[i].textContent);
                           }
                           var data = {
                              id:$(el.parentNode).dataset('id'),
                              custom_field_value:vals
                           }
                       }
                       else{
                           var data = {
                              id:$(el.parentNode).dataset('id'),
                              custom_field_name:$(el).text()
                           }
                       }
                     $.post(mw.settings.api_url + 'fields/save', data, function(){

                     });
            }, 'mw-ui-field mw-ui-field-small');
        }

        initValues();

        mw.$(".btn-create-custom-field-value").bind('click', function(){
          var span = mwd.createElement('span');
          span.className = 'mw-admin-custom-field-value-edit-inline';
          initValue(span);
          $(this).before(span);
          $(span).before(',');
          valueLiveEdit(span);
        });

    </script>
  </tbody>
</table>
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
