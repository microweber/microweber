<?php
if(!isset($params['template'])){
	return;
}



$template = $params['template'];
?>
<script>

layout_selector_custom_css_clear_custom_style = function($template){
	
	
	var r=confirm("Are you sure you want to clean up your custom styles?");
if (r==true)
  {
   
   $.post("<?php print api_url('layouts/template_remove_custom_css') ?>", { template: $template, time: "2pm" }, function(data) {
	     if(self !== parent){
        var css = parent.mw.$("#mw-template-settings")[0];
		   
		if(css !== undefined && css !== null){
			$(css).remove();
		}
	   }
	   
	   
	   
	   
	   $('.layout_selector_custom_css_clear_custom_style').fadeOut();
	
	if(mw.notification != undefined){
		
		 mw.notification.msg(data);
	}
		
  if(mw.templatePreview != undefined){
	  mw.templatePreview.generate();
	  }
	  
	 
			
			
	  
	  mw.reload_module('#<?php print $params['id'];?>');
	   mw.reload_module('settings/template');
	  
	  
   
});
   
   
   
  }
 
	
	
		
	
	
	
    
  } 
  
  
  
  
  
  
 layout_selector_custom_css_return_custom_style = function($template){ 
  
  
  
  
  var r=confirm("Are you sure you want to return custom styles?");
if (r==true)
  {
   
   $.post("<?php print api_url('layouts/template_remove_custom_css') ?>", { template: $template, return_styles: true }, function(data) {
	   
	   $('.layout_selector_custom_css_clear_custom_style').fadeOut();
	
	if(mw.notification != undefined){
		
		 mw.notification.msg(data);
	}
		
  if(mw.templatePreview != undefined){
	  mw.templatePreview.generate();
	  }
	  	  mw.reload_module('#<?php print $params['id'];?>');

   
});

   

  }
  
   }  
</script>

<?php
if(mw()->layouts_manager->template_check_for_custom_css($template) != false): ?>

<a
    class="mw-ui-btn layout_selector_custom_css_clear_custom_style tip"
    data-tip="<?php _e("This template has custom styles, applied from the 'design' tool in live edit. Click here to clean them and return this template to its defalt design."); ?>"
    data-tipposition="top-center"
    href="javascript:layout_selector_custom_css_clear_custom_style('<?php print $template ?>')"><?php _e("Clear custom style"); ?></a>

<?php elseif(mw()->layouts_manager->template_check_for_custom_css($template,true) != false): ?>


<a
    class="mw-ui-btn layout_selector_custom_css_clear_custom_style tip"
    data-tip="<?php _e("You hae removed the custom styles. Click here to return them."); ?>"
    data-tipposition="top-center"
    href="javascript:layout_selector_custom_css_return_custom_style('<?php print $template ?>')"><?php _e("Return custom style"); ?></a>

<?php endif; ?>
