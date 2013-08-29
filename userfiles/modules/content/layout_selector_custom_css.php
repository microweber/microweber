<?php 
if(!isset($params['template'])){
	return;
}



$template = $params['template'];

if(mw('layouts')->template_check_for_custom_css($template) != false): ?>
<script>

layout_selector_custom_css_clear_custom_style = function($template){
	
	
	var r=confirm("Are you sure you want to clean up your custom styles?");
if (r==true)
  {
   
   $.post("<?php print api_url('layouts/template_remove_custom_css') ?>", { template: $template, time: "2pm" }, function(data) {
	   
	   $('.layout_selector_custom_css_clear_custom_style').fadeOut();
	
	if(mw.notification != undefined){
		
		 mw.notification.msg(data);
	}
		
  if(mw.templatePreview != undefined){
	  mw.templatePreview.generate();
	  }
   
});
   
   
   
  }
 
	
	
	
	
	
	
    
  } 
</script>

<small><a class="faded layout_selector_custom_css_clear_custom_style" title="This template have custom styles, applied from the 'design' tool in live edit. Click here to clean them and return this template to its defalt design." href="javascript:layout_selector_custom_css_clear_custom_style('<?php print $template ?>')">clear custom style</a></small>
<?php endif; ?>
