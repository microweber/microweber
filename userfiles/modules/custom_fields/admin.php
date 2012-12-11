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

<div id="the_custom_fields1"> <span class="mw-ui-btn-rect" onclick="mw.tools.toggle('.custom_fields_selector', this);"><span class="ico iAdd"></span>
  <?php _e("Add New Custom Field"); ?>
  </span>
  <div class="vSpace"></div>
  <div class="custom_fields_selector" style="display: none;">
    <ul class="mw-quick-links left">
      <li><a href="javascript:;" onclick="mw.custom_fields.create('text');"><span class="ico iSingleText"></span><span>Single Line Text</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('paragraph');"><span class="ico iPText"></span><span>Paragraph Text</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('radio');"><span class="ico iRadio"></span><span>Multiple Choice</span></a></li>
      <li><a href="#"><span class="ico iName"></span><span>Name</span></a></li>
    </ul>
    <ul class="mw-quick-links left">
      <li><a href="#"><span class="ico iPhone"></span><span>Phone</span></a></li>
      <li><a href="#"><span class="ico iWebsite"></span><span>Web Site</span></a></li>
      <li><a href="#"><span class="ico iEmail"></span><span>E-mail</span></a></li>
      <li><a href="#"><span class="ico iUpload"></span><span>File Upload</span></a></li>
    </ul>
    <ul class="mw-quick-links left">
      <li><a href="javascript:;" onclick="mw.custom_fields.create('number');"><span class="ico iNumber"></span><span>Number</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('checkbox');"><span class="ico iChk"></span><span>Checkbox</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('dropdown');"><span class="ico iDropdown"></span><span>Dropdown</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('date');"><span class="ico iDate"></span><span>Date</span></a></li>
    </ul>
    <ul class="mw-quick-links left">
      <li><a href="#"><span class="ico iTime"></span><span>Time</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('address');"><span class="ico iAddr"></span><span>Adress</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('price');"><span class="ico iPrice"></span><span>Price</span></a></li>
      <li><a href="javascript:;" onclick="mw.custom_fields.create('hr');"><span class="ico iSpace"></span><span>Section Break</span></a></li>
    </ul>
  </div>
  <div class="custom-field-table semi_hidden" id="create-custom-field-table">
    <div class="custom-field-table-tr active">
      <div class="custom-field-preview-cell"></div>
      <div class="second-col">
        <div class="custom-fields-form-wrap custom-fields-form-wrap-<? print $rand ?>" id="custom-fields-form-wrap-<? print $rand ?>"> </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">


mw.custom_fields.create = function($type, $copy, callback){
      var copy_str = '';
      if($copy !== undefined){
        var copy_str = '/copy_from:'+ $copy;
      }
      mw.$('#custom-fields-form-wrap-<? print $rand ?>').load('<? print site_url('api_html/make_custom_field/settings:y/basic:y/for_module_id:') ?><? print $module_id; ?>/for:<? print $for  ?>/custom_field_type:'+$type + copy_str, function(){
        mw.is.func(callback) ? callback.call($type) : '';
        mw.$("#create-custom-field-table").removeClass("semi_hidden");
      });

  }




$(document).ready(function(){
<? if($copy_from != false): ?>
    mw.custom_fields.create('', '<? print $copy_from ?>');
<? endif; ?>
        //make_new_field()

<? if(isset($params['content-subtype']) != false and trim($params['content-subtype'] == 'product') and intval($module_id) == 0): ?>
mw.custom_fields.create('price');
    //    mw.$(".custom_fields_selector").show();

<? endif; ?>



    });
</script> 
  <script type="text/javascript">

mw.custom_fields.save = function(id){
    var obj = mw.form.serialize("#"+id);
    $.post("<? print site_url('api_html/save_custom_field') ?>", obj, function(data) {
       $cfadm_reload = false;
	 
	   
	    if(obj.cf_id === undefined){
             mw.reload_module('.edit [data-parent-module="custom_fields"]');
			  mw.reload_module('custom_fields/list');
			//  $('#create-custom-field-table').addClass('semi_hidden');
			// $("#"+id).hide();
			  mw.$("#create-custom-field-table").addClass("semi_hidden");
			$("#mw_custom_fields_list_<? print $params['id']; ?>").show();
			
        }
        else {
			
			if(obj.copy_to_table_id === undefined){
				
            $("#"+id).parents('.custom-field-table-tr').first().find('.custom-field-preview-cell').html(data);
				
			} else {
			 $cfadm_reload = true;   
			   mw.reload_module('custom_fields/list');
			}
			
			
			
        }
		
		
         mw.$(".mw-live-edit [data-type='custom_fields']").each(function(){
         if(!mw.tools.hasParentsWithClass(this, 'mw_modal') && !mw.tools.hasParentsWithClass(this, 'is_admin')){
			// if(!mw.tools.hasParentsWithClass(this, 'mw_modal') ){
               mw.reload_module(this);
           } else {
			$cfadm_reload = true;   
		   }
        });
		
		
		if($cfadm_reload  == true){
	        mw.reload_module('custom_fields/admin');
		}

		
		
    });
}

mw.custom_fields.del = function(id){
    var q = "Are you sure you want to delete this?";
    mw.tools.confirm(q, function(){
      var obj = mw.form.serialize("#"+id);
      $.post("<? print site_url('api/remove_field') ?>",  obj, function(data){
        $("#"+id).parents('.custom-field-table-tr').first().remove();
      });
    });

}


$(document).ready(function(){
  
});

mw.custom_fields.sort_rows = function(){
  if(!mw.$("#custom-field-main-table").hasClass("ui-sortable")){
   mw.$("#custom-field-main-table").sortable({
       items: '.custom-field-table-tr',
       axis:'y',
       handle:'.custom-field-handle-row',
       update:function(){
         var obj = {cforder:[]}
         $(this).find('.custom-field-table-tr').each(function(){
            var id = this.attributes['data-field-id'].nodeValue;
            obj.cforder.push(id);
         });

         $.post("<?php print site_url('api/reorder_custom_fields'); ?>", obj, function(){});
       },
       start:function(a,ui){
              $(this).height($(this).outerHeight());
              $(ui.placeholder).height($(ui.item).outerHeight())
              $(ui.placeholder).width($(ui.item).outerWidth())
       },
       scroll:false,

       placeholder: "custom-field-main-table-placeholder"
    });

  }
}


</script>
  <module data-type="custom_fields/list" <? print $hide_preview  ?>  for="<? print $for  ?>" for_module_id="<? print $module_id ?>" <? if(isset($params['to_table_id'])): ?> to_table_id="<? print $params['to_table_id'] ?>"  <? endif; ?> id="mw_custom_fields_list_<? print $params['id']; ?>" />
</div>
