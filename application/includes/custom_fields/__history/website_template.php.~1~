<script  type="text/javascript">


function mw_set_default_template(){
	
	



	
	    var items = mw.$('.mw-site-theme-selector').find("[name='active_site_template']").first();
		 	 var v = items.val();
			 var el1 =  mw.$('.mw-site-theme-selector').find("[name='<? print  $data['option_key']; ?>']")[0];
			 
		 
			 
				  el1.value = v;
				  
				   mw.options.save(el1, function(){
      mw.notification.success("<?php _e("All changes are saved"); ?>.");
    });
}

 
 
$(document).ready(function(){
	
  mw.templatePreview.generate();
});
 
</script>
<?  //d($data); ?>

<div class="mw-site-theme-selector">
  <label class="control-label-title"> Website template </label>
  <!--  <button class="mw-ui-btn mw-action-delete-template">Delete Template</button>
-->
  
  <input name="<? print  $data['option_key']; ?>" style="display:none;" class="mw_option_field mw-ui-field"   type="text" option-group="<? print  $data['option_group']; ?>"  value="<? print  $data['option_value']; ?>" />
  <module type="content/layout_selector" data-active-site-template="<? print $data['option_value'] ?>"  />
  <button class="mw-ui-btn mw-action-change-template" onClick="mw_set_default_template()">Apply Template</button>
</div>
