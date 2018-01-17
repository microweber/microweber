<?php
$is_color = get_option('is_color', $params['id']);
if ($is_color === null OR $is_color === false OR $is_color == '') {
    $is_color = '';
}
?>

<script>

    $(window).load(function(){
        mw.colorPicker({
            element:'.color_picker_for_sidebar_settings',
            position:'bottom-left',
            onchange:function(color){
                $('.color_picker_for_sidebar_settings').trigger('change');
            }
        });

    });

</script>

<div class="is-color-select" style="padding-top: 15px;">
    <label class="mw-ui-label"><?php _lang("Color as overlay", "templates/dream"); ?> ?</label>
    <input type="text" name="is_color" placeholder="<?php _lang("Color name Or HEX", "templates/dream"); ?>" value="<?php print $is_color; ?>" class="mw-ui-field mw_option_field color_picker_for_sidebar_settings"/>
</div>