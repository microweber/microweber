<?php only_admin_access(); ?>
<div class="settings-wrapper">
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
		 mw.reload_module_parent('custom_fields');
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




<div id="custom-field-editor" class="mw-ui-box mw-ui-box-content" style="display: none">
	<label class="mw-ui-label"><small>
		<?php _e("Edit"); ?>
		<b id="which_field"></b>
		<?php _e("Field"); ?>
		</small></label>
	<div class="custom-field-edit">
		<div  class="custom-field-edit-header">
			<span class="custom-field-edit-title"></span>
            <span onmousedown="mw_cf_close_edit_window()" class="custom-field-edit-title-head right" style="cursor:pointer;">close
           <span  class="mw-ui-arr mw-ui-arr-down " style="opacity:0.6;"></span> 
           </span>
		</div>
		<div class="mw-admin-custom-field-edit-item-wrapper">
			<div class="mw-admin-custom-field-edit-item mw-admin-custom-field-edit-<?php print $params['id']; ?> "></div>
		</div>
	</div>
</div>


<div class="mw-dropdown mw-dropdown-pop" id="dropdown-custom-fields">
  <span class="mw-dropdown-value">
      <span class="mw-ui-btn mw-ui-btn-info mw-dropdown-val">
          <?php _e("Add New"); ?><span class="mw-icon-dropdown mw-icon-right"></span>
      </span>
  </span>
  <div class="mw-dropdown-content">
      <ul class="mw-ui-btn-vertical-nav">
  		<li value="text"><span class="mw-ui-btn"><?php _e("Text Field"); ?></span></li>
  		<li value="number"><span class="mw-ui-btn"><?php _e("Number"); ?></span></li>
  		<li value="price"><span class="mw-ui-btn"><?php _e("Price"); ?></span></li>
  		<li value="phone"><span class="mw-ui-btn"><?php _e("Phone"); ?></span></li>
  		<li value="site"><span class="mw-ui-btn"><?php _e("Web Site"); ?></span></li>
  		<li value="email"><span class="mw-ui-btn"><?php _e("E-mail"); ?></span></li>
  		<li value="address"><span class="mw-ui-btn"><?php _e("Address"); ?></span></li>
  		<li value="date"><span class="mw-ui-btn"><?php _e("Date"); ?></span></li>
  		<li value="upload"><span class="mw-ui-btn"><?php _e("File Upload"); ?></span></li>
  		<li value="radio"><span class="mw-ui-btn"><?php _e("Single Choice"); ?></span></li>
  		<li value="dropdown"><span class="mw-ui-btn"><?php _e("Dropdown"); ?></span></li>
  		<li value="checkbox"><span class="mw-ui-btn"><?php _e("Multiple choices"); ?></span></li>
  	</ul>
  </div>
</div>


<script>mw.dropdown()</script>






<?php if( $list_preview != false ): ?>
 <hr>
<div class="mw-ui-box" id="custom-fields-box">

      <module
          data-type="custom_fields/list"
          for="<?php print $for  ?>"
          <?php if(isset($params['rel_id'])): ?> rel_id="<?php print $params['rel_id'] ?>"  <?php endif; ?>
          list-preview="true"
          id="mw_custom_fields_list_preview"  />

</div>
     <?php endif; ?>





</div>