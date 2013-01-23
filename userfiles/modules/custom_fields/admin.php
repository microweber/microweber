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


<script>


    __sort_fields = function(){
        var sortable_holder = mw.$(".mw-custom-fields-tags").eq(0);
        if(!sortable_holder.hasClass('ui-sortable') && sortable_holder.find('a.mw-ui-btn-small').length>1){
          sortable_holder.sortable({
            items:'a.mw-ui-btn-small',
            change:function(event,ui){

            },
            containment: "parent"
          });
        }
        return sortable_holder;
    }

    $(document).ready(function(){
       __sort_fields();

    });

</script>

<div class="<? print $config['module_class'] ?>-holder">
  <span class="mw-ui-btn mw-ui-btn-blue" onclick="mw.tools.toggle('.custom_fields_selector', this);" style="height: 15px;">
  <span class="ico iAdd"></span>
  <span><?php _e("Add New Custom Field"); ?></span>
  </span>
  <div class="vSpace"></div>
  <div class="custom_fields_selector" style="display: none;">
    <ul class="mw-quick-links mw-quick-links-cols-4">
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','text',    false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iSingleText"></span><span>Text Field</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','number',  false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iNumber"></span><span>Number</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','price',   false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iPrice"></span><span>Price</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','phone',   false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iPhone"></span><span>Phone</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','website', false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iWebsite"></span><span>Web Site</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','email',   false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iEmail"></span><span>E-mail</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','address', false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iAddr"></span><span>Adress</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','date',    false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iDate"></span><span>Date</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','upload',  false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iUpload"></span><span>File Upload</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','radio',   false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iRadio"></span><span>Single Choice</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','dropdown',false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iDropdown"></span><span>Dropdown</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('.mw-admin-custom-field-edit-<? print $params['id']; ?>','checkbox',false,'<? print $for  ?>','<? print $module_id  ?>');"><span class="ico iChk"></span><span>Multiple choices</span></a></li>
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

  <module
        data-type="custom_fields/list" <? print $hide_preview  ?>
        for="<? print $for  ?>"
        for_module_id="<? print $module_id ?>"
        <? if(isset($params['to_table_id'])): ?> to_table_id="<? print $params['to_table_id'] ?>"  <? endif; ?>
        id="mw_custom_fields_list_<? print $params['id']; ?>"
  />

  <div class="custom-field-edit" id="custom-field-editor" style="display:none;">
    <div  class="custom-field-edit-header">
       <div class="custom-field-edit-title"></div>
    </div>
    <div class="mw-admin-custom-field-edit-item-wrapper"><div class="mw-admin-custom-field-edit-item mw-admin-custom-field-edit-<? print $params['id']; ?>"></div></div>
  </div>




</div>
