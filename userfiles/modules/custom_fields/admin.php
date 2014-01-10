<?php only_admin_access(); ?>
<script type="text/javascript">



    mw.require("custom_fields.js", true);

    mw.require("options.js", true);



</script>
<?php
$for = 'module';

$copy_from = false;
$suggest_from_rel = false;
$list_preview = false;
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
if(isset($params['suggest-from-related']) and $params['suggest-from-related'] != 'false'){
  $suggest_from_rel = true;
}

if(isset($params['list-preview']) and $params['list-preview'] != 'false'){
  $list_preview = true;
}


if(isset($params['for_id'])){
	$for_id = $params['for_id'];
} elseif (isset($params['data-id'])){
  $for_id = $params['data-id'];
}
else  if(isset($params['id'])){
	$for_id = $params['id'];
}

  //$for_id =$params['id'];
if(isset($params['rel_id'])){
  $for_id =$module_id = $params['rel_id'];

}




if(isset($params['content-id'])){
	$for_id = $for_module_id = $params['rel_id']= $params['content-id'];
  $for = 'content';
}
$module_id = $for_id;
//$rand = rand();

?>
<script type="text/javascript">
__sort_fields = function(){
  var sortable_holder = mw.$(".mw-custom-fields-tags").eq(0);
  if(!sortable_holder.hasClass('ui-sortable') && sortable_holder.find('a.mw-ui-btn-small').length>1){
    sortable_holder.sortable({
      items:'a.mw-ui-btn-small',
	  distance: 35,
      update:function(event,ui){
        var obj = {ids:[]};
        $(this).find("a.mw-ui-btn-small").each(function(){
          var id = $(this).dataset("id");
          obj.ids.push(id);
        });
        $.post(mw.settings.api_url+"fields/reorder", obj, function(){
          if(window.parent != undefined && window.parent.mw != undefined){
           window.parent.mw.reload_module('custom_fields');
         }
		 
		 mw.reload_module('#mw_custom_fields_list_preview');
		 
		 
		 
         mw.$("#custom-field-editor").removeClass('mw-custom-field-created').hide();
         mw.$(".mw-custom-fields-tags .mw-ui-btn-blue").removeClass("mw-ui-btn-blue");
       });
      }
    });
  }
  return sortable_holder;
}

createFieldPill = function(el){
	
  mw.custom_fields.create({
    selector:'.mw-admin-custom-field-edit-<?php print $params['id']; ?>',
    type:$(el).dataset('type'),
    copy:false,
    table:'<?php print $for  ?>',
    id:'<?php print $module_id  ?>',
    onCreate:function(){
      mw.$(".custom-field-edit-title").html('<span class="'+el.querySelector('span').className+'"></span><strong>' + el.textContent + '</strong>');
      mw.$("#custom-field-editor").addClass('mw-custom-field-created').show();
      mw.$(".mw-custom-fields-tags .mw-ui-btn-blue").removeClass("mw-ui-btn-blue");
      $(mw.tools.firstParentWithClass(el, 'custom_fields_selector')).slideUp('fast');
      __save();
    }
  });
}

$(document).ready(function(){
 __sort_fields();

 mw.$(".custom_fields_selector strong").click(function(){
  var el = this.getElementsByTagName('a')[0];
  createFieldPill(el);
});




});

mw_cf_close_edit_window = function(el){
	 $('#custom-field-editor').slideUp();
	 $(".mw-ui-field").find('.mw-ui-btn-blue').removeClass('mw-ui-btn-blue');
	  mw.$("#smart_field_opener").show();
	
}
 
</script>

<div class="<?php print $config['module_class'] ?>-holder">
	<label class="mw-ui-label">Edit and explore your custom fields</label>
	<module
    data-type="custom_fields/list"
    for="<?php print $for  ?>"
    for_module_id="<?php print $module_id ?>"
    <?php if(isset($params['rel_id'])): ?> rel_id="<?php print $params['rel_id'] ?>"  <?php endif; ?>
    id="mw_custom_fields_list_<?php print $params['id']; ?>"  <?php if(isset($params['default-fields'])): ?> default-fields="<?php  print $params['default-fields'] ?>" <?php endif; ?>/>
    
  
    
    
    
</div>
<div class="custom_fields_selector" style="display: none;">

  <small>Create new field</small>
  <?php if( $suggest_from_rel != false ): ?>  
 
    <module
    data-type="custom_fields/list"
    for="<?php print $for  ?>"
         <?php if(isset($params['rel_id'])): ?> save_to_content_id="<?php print $params['rel_id'] ?>"  <?php endif; ?>
      suggest-from-related="true"
    id="mw_custom_fields_suggested_list_<?php print $params['id']; ?>"  />

    
    
     <?php endif; ?>
      


	<ul class="mw-quick-links mw-quick-links-cols">
		<li><strong><a href="javascript:;" data-type="text"><span class="ico iSingleText"></span><span>
			<?php _e("Text Field"); ?>
			</span></a></strong></li>
		<li><strong><a href="javascript:;" data-type="number"><span class="ico iNumber"></span><span>
			<?php _e("Number"); ?>
			</span></a></strong></li>
		<li><strong id="field-type-price"><a href="javascript:;" data-type="price"><span class="ico iPrice"></span><span>
			<?php _e("Price"); ?>
			</span></a></strong></li>
		<li><strong><a href="javascript:;" data-type="phone"><span class="ico iPhone"></span><span>
			<?php _e("Phone"); ?>
			</span></a></strong></li>
		<li><strong><a href="javascript:;" data-type="site"><span class="ico iWebsite"></span><span>
			<?php _e("Web Site"); ?>
			</span></a></strong></li>
		<li><strong><a href="javascript:;" data-type="email"><span class="ico iEmail"></span><span>
			<?php _e("E-mail"); ?>
			</span></a></strong></li>
		<li><strong><a href="javascript:;" data-type="address"><span class="ico iAddr"></span><span>
			<?php _e("Address"); ?>
			</span></a></strong></li>
		<li><strong><a href="javascript:;" data-type="date"><span class="ico iDate"></span><span>
			<?php _e("Date"); ?>
			</span></a></strong></li>
		<li><strong><a href="javascript:;" data-type="upload"><span class="ico iUpload"></span><span>
			<?php _e("File Upload"); ?>
			</span></a></strong></li>
		<li><strong><a href="javascript:;" data-type="radio"><span class="ico iRadio"></span><span>
			<?php _e("Single Choice"); ?>
			</span></a></strong></li>
		<li><strong><a href="javascript:;" data-type="dropdown"><span class="ico iDropdown"></span><span>
			<?php _e("Dropdown"); ?>
			</span></a></strong></li>
		<li><strong><a href="javascript:;" data-type="checkbox"><span class="ico iChk"></span><span>
			<?php _e("Multiple choices"); ?>
			</span></a></strong></li>
	</ul>
    
    
    
    
</div>
<span class="mw-ui-btn mw-ui-btn-blue" id="smart_field_opener" onclick="mw.tools.toggle('.custom_fields_selector', this);" style="height: 15px;"> <span class="ico iAdd"></span><span>
<?php _e("Add Custom Field"); ?>
</span> </span>
<div id="custom-field-editor" style="display: none">
	<label class="mw-ui-label"><small>
		<?php _e("Edit"); ?>
		<b id="which_field"></b>
		<?php _e("Field"); ?>
		</small></label>
	<div class="custom-field-edit">
		<div  class="custom-field-edit-header">
			<span class="custom-field-edit-title"></span>
            <span onmousedown="mw_cf_close_edit_window()" class="custom-field-edit-title-head right" style="cursor:pointer;">
           <span  class="mw-ui-arr mw-ui-arr-down " style="opacity:0.6;"></span> 
           </span>
		</div>
		<div class="mw-admin-custom-field-edit-item-wrapper">
			<div class="mw-admin-custom-field-edit-item mw-admin-custom-field-edit-<?php print $params['id']; ?> "></div>
		</div>
	</div>
</div>


<div class="conatent_cf_list_all">



<?php if( $list_preview != false ): ?>  
  <div class="vSpace"></div>
    <module
    data-type="custom_fields/list"
    for="<?php print $for  ?>"
         <?php if(isset($params['rel_id'])): ?> rel_id="<?php print $params['rel_id'] ?>"  <?php endif; ?>
     list-preview="true"
    id="mw_custom_fields_list_preview"  />

    
    
     <?php endif; ?>

<?php
/*


 if(isset($params['content-id'])){
	$rel_id= $params['content-id'];
	 
	 $cf_list = get_custom_fields($for,$rel_id,1,false,false, false, true);
	 
	 if(!empty($cf_list)){
		 foreach($cf_list as $item){
		 
			print  make_custom_field($item['id'],$item['custom_field_type'],'y');
		 }
		
	 }
 }*/
  
?>
</div>