<? //  d($params) ?>
<script type="text/javascript">



    mw.require("custom_fields.js");

    mw.require("options.js");



    </script>
<?
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
 } else  if(isset($params['id'])){
	$for_id = $params['id'];
 }
 
  //$for_id =$params['id'];
 if(isset($params['to_table_id'])){
$for_id =$module_id = $params['to_table_id'];
 
 }
 
$module_id = $for_id;
$rand = rand();
 
?>

<div class="<? print $config['module_class'] ?>-holder">
  <span class="mw-ui-btn mw-ui-btn-blue" onclick="mw.tools.toggle('.custom_fields_selector', this);">
  <span class="ico iAdd"></span>
  <span><?php _e("Add New Custom Field"); ?></span>
  </span>
  <div class="vSpace"></div>
  <div class="custom_fields_selector" style="display: none;">
    <ul class="mw-quick-links left">
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','text',false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iSingleText"></span><span>Single Line Text</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','paragraph',false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iSingleText"></span><span>Paragraph Text</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','radio',false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iSingleText"></span><span>Multiple Choice</span></a></li>
      
      <!--  <li><a href="javascript:;" onclick="mw.custom_fields.create('text');"><span class="ico iSingleText"></span><span>Single Line Text</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('paragraph');"><span class="ico iPText"></span><span>Paragraph Text</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('radio');"><span class="ico iRadio"></span><span>Multiple Choice</span></a></li>-->
      <li><a href="#"><span class="ico iName"></span><span>Name</span></a></li>
    </ul>
    <ul class="mw-quick-links left">
      <li><a href="javascript:void(0);"><span class="ico iPhone"></span><span>Phone</span></a></li>
      <li><a href="javascript:void(0);"><span class="ico iWebsite"></span><span>Web Site</span></a></li>
      <li><a href="javascript:void(0);"><span class="ico iEmail"></span><span>E-mail</span></a></li>
      <li><a href="javascript:void(0);"><span class="ico iUpload"></span><span>File Upload</span></a></li>
    </ul>
    <ul class="mw-quick-links left">
      <li><a href="javascript:;" onclick="mw.custom_fields.create('number');"><span class="ico iNumber"></span><span>Number</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('checkbox');"><span class="ico iChk"></span><span>Checkbox</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('dropdown');"><span class="ico iDropdown"></span><span>Dropdown</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('date');"><span class="ico iDate"></span><span>Date</span></a></li>
    </ul>
    <ul class="mw-quick-links left">
      <li><a href="javascript:void(0);"><span class="ico iTime"></span><span>Time</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('address');"><span class="ico iAddr"></span><span>Adress</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('price');"><span class="ico iPrice"></span><span>Price</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('hr');"><span class="ico iSpace"></span><span>Section Break</span></a></li>
    </ul>
  </div>
  <div class="custom-field-table create-custom-field-table">
    <div class="custom-field-table-tr active">
      <div class="custom-field-preview-cell"></div>
      <div class="second-col">
        <div class="custom-fields-form-wrap custom-fields-form-wrap-<? print $rand ?>" id="custom-fields-form-wrap-<? print $rand ?>"> </div>
      </div>
    </div>
  </div>
  <div  class="custom-field-edit">
    <div class="mw-admin-custom-field-edit-item mw-admin-custom-field-edit-<? print $params['id']; ?>"></div>
  </div>
  <module data-type="custom_fields/list" <? print $hide_preview  ?>  for="<? print $for  ?>" for_module_id="<? print $module_id ?>" <? if(isset($params['to_table_id'])): ?> tssssso_table_id="<? print $params['to_table_id'] ?>"  <? endif; ?> id="mw_custom_fields_list_<? print $params['id']; ?>" />
</div>
