<?
 $for = 'module';
 if(isset($params['for'])){
	$for = $params['for'];
 }
 if(!isset($params['for_module_id'])){
	 if(isset($params['id'])){
		 $params['for_module_id'] = $params['id'];
	 }
 }
 ?>




<? if(isset($params['for_module_id'])): ?>
<?	$more = get_custom_fields($for,$params['for_module_id'],1,false);    ?>
<? if(!empty( $more)):  ?>

<div class="custom-field-table" id="custom-field-main-table">

  <? foreach( $more as $field): ?>
  <div class="custom-field-table-tr" data-field-id="<? print $field['id'] ?>">
    <div class="custom-field-preview-cell" onclick="$(this).parent().addClass('active')">
      <div class="custom-field-preview">
        <?   print  make_field($field); ?>
        <span class="edit-custom-field-btn" title="<?php _e("Edit this field"); ?>"></span></div>
    </div>
    <div class="second-col">
      <div class="custom-field-set-holder">
          <span class="ico iMove custom-field-handle-row right" onmousedown="mw.custom_fields.sort_rows()"></span>
          <div class="custom-field-set">
              <?  print  make_field($field, false, 2); ?>
          </div>
      </div>
    </div>
  </div>
  <? endforeach; ?>

</div>
<? endif; ?>
<? endif; ?>
