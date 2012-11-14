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
        }
        else {
            $("#"+id).parents('tr').first().find('.custom-field-preview-cell').html(data);
        }
          mw.$(".mw-live-edit [data-type='custom_fields']").each(function(){
         if(!mw.tools.hasParentsWithClass(this, 'mw_modal') && !mw.tools.hasParentsWithClass(this, 'is_admin')){
               mw.reload_module(this);
           }
        });
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



</script>
<? if(isset($params['for_module_id'])): ?>
<?	$more = get_custom_fields($for,$params['for_module_id'],1,false);    ?>
	<? if(!empty( $more)):  ?>
    <table class="custom-field-table" cellpadding="0" cellspacing="0" >
      <? foreach( $more as $field): ?>
      <tr class="custom-field-table-tr">
        <td class="custom-field-preview-cell" onclick="$(this).parent().addClass('active')"><?  if(isset($params['hide-preview'])): ?>
          <h1>Hide preview alex</h1>
          <? else :  ?>
          <? endif; ?>
          <div class="custom-field-preview">
            <?  print  make_field($field); ?>
            <span class="edit-custom-field-btn" title="<?php _e("Edit this field"); ?>"></span></div></td>
        <td class="second-col"><div class="custom-field-set"><? print  make_field($field, false, 2); ?></div></td>
      </tr>
      <? endforeach; ?>
    </table>
    <? endif; ?>
<? endif; ?>
