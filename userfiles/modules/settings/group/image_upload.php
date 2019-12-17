<?php only_admin_access(); ?>
<script  type="text/javascript">
    $(document).ready(function(){

        mw.options.form('.<?php print $config['module_class'] ?>', function(){
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>

<?php

if(isset($params['show_description_text']) and $params['show_description_text'] ){ ?>


    <h3><?php _e("Looks like you are trying to upload big images"); ?></h3>
    <h4><?php _e("For best experience you may want to enable the Automatic image resize"); ?></h4>


<?php

}

?>

<div class="mw-ui-field-holder">
    <label class="mw-ui-label">
        <?php _e("Enable automatic image resize on upload?"); ?>
    </label>
    <?php
    $automatic_image_resize_on_upload = get_option('automatic_image_resize_on_upload','website');

    ?>


    <input  class="mw_option_field" type="radio" id="img_resize_choice1"
           name="automatic_image_resize_on_upload" <?php if($automatic_image_resize_on_upload == 'y'): ?> checked <?php endif; ?> value="y" option-group="website">
    <label for="img_resize_choice1"><?php _e("Yes"); ?></label>

    <input  class="mw_option_field" type="radio" id="img_resize_choice2"
           name="automatic_image_resize_on_upload" <?php if(!$automatic_image_resize_on_upload or $automatic_image_resize_on_upload == 'n'): ?> checked <?php endif; ?> value="n" option-group="website">
    <label for="img_resize_choice2"><?php _e("No"); ?></label>

    <input  class="mw_option_field" type="radio" id="img_resize_choice3"
           name="automatic_image_resize_on_upload" <?php if($automatic_image_resize_on_upload =='d'): ?> checked <?php endif; ?> value="d" option-group="website">
    <label for="img_resize_choice3"><?php _e("Disable notification"); ?></label>




</div>
