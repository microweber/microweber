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
<script type="text/javascript">

mw.custom_fields.save = function(id){
    var obj = mw.form.serialize("#"+id);
    $.post("<? print site_url('api_html/save_custom_field') ?>", obj, function(data) {
       
	 
	   
	    if(obj.id === undefined){
             mw.reload_module('[data-parent-module="custom_fields"]');
			  mw.reload_module('custom_fields/list');
			 $("#"+id).hide();
        }
        else {
            $("#"+id).parents('tr').first().find('.custom-field-preview-cell').html(data);
        }
		
		$cfadm_reload = false;
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
        $("#"+id).parents('tr').first().remove();
      });
    });

}


$(document).ready(function(){
  mw.$("table#custom-field-main-table tbody").sortable({
     items: 'tr',
     axis:'y',
     handle:'.custom-field-handle-row',
     update:function(){
       var obj = {cforder:[]}
       $(this).find('tr').each(function(){
          var id = this.attributes['data-field-id'].nodeValue;
          obj.cforder.push(id);
       });

       $.post("<?php print site_url('api/reorder_custom_fields'); ?>", obj, function(){});
     },
     start:function(a,ui){
            $(ui.placeholder).height($(ui.item).outerHeight())
            $(ui.placeholder).width($(ui.item).outerWidth())
     },
     scroll:false,
     placeholder: "custom-field-main-table-placeholder"
  });
});



</script>
<? if(isset($params['for_module_id'])): ?>
<?	$more = get_custom_fields($for,$params['for_module_id'],1,false);    ?>
<? if(!empty( $more)):  ?>

<table class="custom-field-table" id="custom-field-main-table" cellpadding="0" cellspacing="0" >
<tbody>
  <? foreach( $more as $field): ?>
  <tr class="custom-field-table-tr" data-field-id="<? print $field['id'] ?>">
    <td class="custom-field-preview-cell" onclick="$(this).parent().addClass('active')">
      <div class="custom-field-preview">
        <?  print  make_field($field); ?>
        <span class="edit-custom-field-btn" title="<?php _e("Edit this field"); ?>"></span></div>
      </td>
    <td class="second-col">
        <div class="custom-field-set-holder">
          <span class="ico iMove custom-field-handle-row right"></span>
          <div class="custom-field-set">
              <? print  make_field($field, false, 2); ?>
          </div>
        </div>

    </td>
  </tr>
  <? endforeach; ?>
  </tbody>
</table>
<? endif; ?>
<? endif; ?>
