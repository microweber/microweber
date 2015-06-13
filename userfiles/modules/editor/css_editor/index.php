<?php $custom_css = get_option("custom_css", "template"); 
?>
<?php only_admin_access(); ?>
<script type="text/javascript">
    mw.require('options.js');
</script>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('#<?php print $params['id'] ?>', function () {
            if (mw.notification != undefined) {
                mw.notification.success('CSS Updated');
            }
			if(typeof(window.parent.mw.wysiwyg) != 'undefined'){
				var custom_fonts_stylesheet = window.parent.document.getElementById("mw-custom-user-css");
				if(custom_fonts_stylesheet != null){
					var custom_fonts_stylesheet_restyled = '<?php print api_url('template/print_custom_css') ?>?v='+Math.random(0,10000);
					custom_fonts_stylesheet.href = custom_fonts_stylesheet_restyled;

				}
			}
		
        });
        
    });
</script>
<textarea class="mw-ui-field w100 mw_option_field" name="custom_css" rows="30" option-group="template" placeholder="Type your CSS code here"><?php print $custom_css ?></textarea>
