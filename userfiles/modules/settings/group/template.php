<script  type="text/javascript">
$(document).ready(function(){
	
  mw.options.form('.<? print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("All changes are saved"); ?>.");
    });
});
</script>

<div class="<? print $config['module_class'] ?>">
<? $data = get_option('curent_template', 'template',1);

 
 ?>
<script  type="text/javascript">


function mw_set_default_template(){



			 var el1 =  mw.$('.mw-site-theme-selector').find("[name='<? print  $data['option_key']; ?>']")[0];




				   mw.options.save(el1, function(){
      mw.notification.success("<?php _e("Template settings are saved"); ?>.");
    });
}



$(document).ready(function(){
	
	
	 $(window).bind('templateChanged', function(){
		 
		
  $(".mw-site-theme-selector").find("[name='active_site_template']").each(function( index ) {
  $("#mw_curr_theme_val").val($(this).val());
});
	
	});






	
	
	
/* $("[name='active_site_template']").live('change',function(){
      $("#mw_curr_theme_val").val($(this).val());
    })*/
 });

</script>
<?  //d($data); ?>

<div class="mw-site-theme-selector">
  <label class="control-label-title"> Website template </label>
 
  <input id="mw_curr_theme_val" name="<? print  $data['option_key']; ?>"   class="mw_option_field mw-ui-field"   type="hidden" option-group="<? print  $data['option_group']; ?>"  value="<? print  $data['option_value']; ?>" data-id="<? print  $data['id']; ?>" />
  <module type="content/layout_selector" data-active-site-template="<? print $data['option_value'] ?>" autoload="1"  />
  <button class="mw-ui-btn mw-action-change-template" onClick="mw_set_default_template()">Apply Template</button>
</div>
</div>