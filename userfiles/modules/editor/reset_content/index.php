<?php only_admin_access(); ?>

<script src="<?php print modules_url() ?>editor/html_editor/html_editor.js"></script>

<script>
    $(document).ready(function () {
        var fields = mw.html_editor.get_edit_fields(true);


    <?php if(isset($params['root_element_id']) and $params['root_element_id']){ ?>
        var fields = mw.html_editor.get_edit_fields(true, '#<?php print $params['root_element_id'] ?>');
    <?php } ?>

        mw.html_editor.build_dropdown(fields, false);
        mw.html_editor.populate_editor();

    })
    mw.require('<?php print modules_url()?>editor/selector.css');

</script>
<style>
    .mw-ui-box {
        margin: 20px;
        margin-bottom: 70px;
    }

    #save-toolbar {
        position: fixed;
        bottom: 0;
        left: 0;
        background: white;
        box-shadow: 0 -2px 2px rgba(0, 0, 0, .2);
        padding: 10px;
        text-align: right;
        z-index: 1;
        width: 100%;

    }

</style>
<div class="mw-ui-box   ">
    <div class="mw-ui-box-header">
        <span class="mw-icon-gear"></span><span><?php _e('Sections'); ?></span>
    </div>
    <div class="mw-ui-box-content">
        <div id="select_edit_field_wrap"></div>
    </div>
</div>


<script>
    function handle_reset_content_btn_click() {

        var also_modules = $('#also_reset_modules').is(':checked');

        var txt = "<?php _ejs('Are you sure you want to reset the content?');?>";
        if(also_modules){
            var txt = "<?php _ejs('Are you sure you want to reset the content and modules?');?>";

        }
        var r = confirm(txt);
        if (r == true) {
             mw.html_editor.reset_content(also_modules)
        }

    }
</script>



<div id="save-toolbar">

    <label for="also_reset_modules">
        <input type="checkbox" id="also_reset_modules" name="also_reset_modules" value="1">
           <?php _e('Also reset modules?'); ?>
    </label>


    <button onclick="handle_reset_content_btn_click();"
            class="mw-ui-btn mw-ui-btn-invert"><?php _e('Reset content'); ?></button>
</div>

