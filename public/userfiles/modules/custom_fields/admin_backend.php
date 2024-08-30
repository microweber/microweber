<?php must_have_access(); ?>
<script type="text/javascript">

    mw.require("custom_fields.js", true);
    //mw.require("options.js", true);

</script>
<?php
 $for = 'module';

  $copy_from = false;
 if(isset($params['for'])){
	$for = $params['for'];
 }

 if(isset($params['copy_from'])){
	$copy_from = $params['copy_from'];
 }
  $hide_preview = '';
  if(isset($params['live_edit'])){
	 $hide_preview = " live_edit=true " ;
 }

 if(isset($params['for_id'])){
	$for_id = $params['for_id'];
 } elseif (isset($params['data-id'])){
    $for_id = $params['data-id'];
 }
  else  if(isset($params['id'])){
	$for_id = $params['id'];
 }

 if(isset($params['rel_id'])){
    $for_id =$module_id = $params['rel_id'];
 }

$module_id = $for_id;
?>
<script type="text/javascript">

    $(document).ready(function(){
       __sort_fields();
       mw.$(".custom_fields_selector a").click(function(){
            var el = this;
            mw.custom_fields.create({
              selector:'.mw-admin-custom-field-edit-<?php print $params['id']; ?>',
              type:$(el).dataset('type'),
              copy:false,
              table:'<?php print $for  ?>',
              id:'<?php print $module_id  ?>',
              onCreate:function(){
                mw.$(".custom-field-edit-title").html(el.textContent);
                mw.$("#custom-field-editor").addClass('mw-custom-field-created').show();
                mw.$(".mw-custom-fields-tags .mw-ui-btn-blue").removeClass("mw-ui-btn-blue");
              }
            });
       });
    });
</script>

<div class="<?php print $config['module_class'] ?>-holder">
    <span class="mw-ui-btn mw-ui-btn-blue" onclick="mw.tools.toggle('.custom_fields_selector', this);" style="min-height: 15px;">
        <span class="ico iAdd"></span><span><?php _e("Add  New Custom Field"); ?></span>
    </span>
<!--
  <div class="custom_fields_selector" style="display: none;">
    <ul class="mw-quick-links mw-quick-links-cols">
      <li><a href="javascript:;" data-type="text"><span class="ico iSingleText"></span><span><?php /*_e("Text Field"); */?></span></a></li>
      <li><a href="javascript:;" data-type="number"><span class="ico iNumber"></span><span><?php /*_e("Number"); */?></span></a></li>
      <li><a href="javascript:;" data-type="price"><span class="ico iPrice"></span><span><?php /*_e("Price"); */?></span></a></li>
      <li><a href="javascript:;" data-type="phone"><span class="ico iPhone"></span><span><?php /*_e("Phone"); */?></span></a></li>
      <li><a href="javascript:;" data-type="site"><span class="ico iWebsite"></span><span><?php /*_e("Web Site"); */?></span></a></li>
      <li><a href="javascript:;" data-type="email"><span class="ico iEmail"></span><span><?php /*_e("E-mail"); */?></span></a></li>
      <li><a href="javascript:;" data-type="address"><span class="ico iAddr"></span><span><?php /*_e("Address"); */?></span></a></li>
      <li><a href="javascript:;" data-type="date"><span class="ico iDate"></span><span><?php /*_e("Date"); */?></span></a></li>
      <li><a href="javascript:;" data-type="time"><span class="ico iTime"></span><span><?php /*_e("Time"); */?></span></a></li>
      <li><a href="javascript:;" data-type="upload"><span class="ico iUpload"></span><span><?php /*_e("File Upload"); */?></span></a></li>
      <li><a href="javascript:;" data-type="radio"><span class="ico iRadio"></span><span><?php /*_e("Single Choice"); */?></span></a></li>
      <li><a href="javascript:;" data-type="dropdown"><span class="ico iDropdown"></span><span><?php /*_e("Dropdown"); */?></span></a></li>
      <li><a href="javascript:;" data-type="checkbox"><span class="ico iChk"></span><span><?php /*_e("Multiple choices"); */?></span></a></li>
    </ul>
  </div>-->

  <module data-type="custom_fields/list" <?php print $hide_preview  ?>  for="<?php print $for;  ?>" for_module_id="<?php print $module_id ?>" <?php if(isset($params['rel_id'])): ?> rel_id="<?php print $params['rel_id'] ?>"  <?php endif; ?> id="mw_custom_fields_list_<?php print $params['id']; ?>" />
  <div class="custom-field-edit" id="custom-field-editor" style="display:none;">
    <div  class="custom-field-edit-header">
      <div class="custom-field-edit-title"></div>

    </div>
    <div class="mw-admin-custom-field-edit-item-wrapper">
      <div class="mw-admin-custom-field-edit-item mw-admin-custom-field-edit-<?php print $params['id']; ?>"></div>
    </div>
  </div>
</div>
