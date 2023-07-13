<?php
if(!isset($params['template'])){
	return;
}

$template = $params['template'];
?>
<script>

var layout_selector_custom_css_clear_custom_style = function($template){
    if (confirm("Are you sure you want to clean up your custom styles?")) {
         var spinner = mw.spinner({
            element: mw.dialog.get()?.container || document.body,
            size: 60,
            decorate: true
        });
        $.post("<?php print api_url('layouts/template_remove_custom_css') ?>", { template: $template, time: "2pm" }, function(data) {
            if(self !== parent){
                var css = mw.parent().$("#mw-template-settings");
                if(css.length){
                    css.remove();
                }
            }
            $('.layout_selector_custom_css_clear_custom_style').fadeOut();
            if(mw.notification){
                mw.notification.msg(data);
            }
            if(mw.templatePreview){
                mw.templatePreview.generate();
            }
            spinner.remove()
            mw.reload_modules(['#<?php print $params['id'];?>', 'settings/template'], function (){
            //    spinner.remove()
            });
            /*mw.reload_module('#<?php print $params['id'];?>');
            mw.reload_module('settings/template');*/
        });
    }
}

 var layout_selector_custom_css_return_custom_style = function($template){
    if (confirm("Are you sure you want to return custom styles?")) {
        var spinner = mw.spinner({
            element: mw.dialog.get()?.container || document.body,
            size: 60,
            decorate: true
        });
       $.post("<?php print api_url('layouts/template_remove_custom_css') ?>", { template: $template, return_styles: true }, function(data) {
           $('.layout_selector_custom_css_clear_custom_style').fadeOut();
            if(mw.notification){
                 mw.notification.msg(data);
            }
            if(mw.templatePreview){
                mw.templatePreview.generate();
            }
           mw.reload_module('#<?php print $params['id'];?>', function (){
               spinner.remove()
           });
        });
  }
}
</script>

<?php
if(mw()->layouts_manager->template_check_for_custom_css($template) != false): ?>

<span
    class="mw-ui-btn layout_selector_custom_css_clear_custom_style tip"
    data-tip="<?php _e("This template has custom styles, applied from the 'design' tool in live edit. Click here to clean them and return this template to its defalt design."); ?>"
    data-tipposition="top-center"
    onclick="layout_selector_custom_css_clear_custom_style('<?php print $template ?>')"><?php _e("Clear custom style"); ?></span>

<?php elseif(mw()->layouts_manager->template_check_for_custom_css($template,true) != false): ?>
<span
    class="mw-ui-btn layout_selector_custom_css_clear_custom_style tip"
    data-tip="<?php _e("You hae removed the custom styles. Click here to return them."); ?>"
    data-tipposition="top-center"
    onclick="layout_selector_custom_css_return_custom_style('<?php print $template ?>')"><?php _e("Return custom style"); ?></span>

<?php endif; ?>
