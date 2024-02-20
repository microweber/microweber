<?php must_have_access(); ?>
<script src="<?php print modules_url() ?>editor/html_editor/html_editor.js"></script>
<script>
    function reset() {
        var also = $('#also_reset_modules').is(':checked');
        var txt = "<?php _ejs('Are you sure you want to reset the content?');?>";
        if (also){
            txt = "<?php _ejs('Are you sure you want to reset the content and modules?');?>";
        }
        mw.confirm(txt, function () {
            mw.html_editor.reset_content(also)
        });
    }
    $(document).ready(function () {
        var fields = mw.html_editor.get_edit_fields(true);
        <?php if(isset($params['root_element_id']) and $params['root_element_id']){ ?>
            fields = mw.html_editor.get_edit_fields(true, '#<?php print $params['root_element_id'] ?>');
        <?php } ?>




        mw.html_editor.build_dropdown(fields, false);
        mw.html_editor.populate_editor();
    })
    mw.require('<?php print modules_url()?>editor/selector.css');
</script>
<style>

    .mw-ui-box{
        margin: 20px 0;
    }

</style>


<div class="d-flex align-items-center gap-4 mb-4">
    <div>
        <span class="mdi mdi-alert mw-color-important" style="font-size:45px"></span>
    </div>

   <div>
       <h3 class="mb-1"> <?php _e('Warning'); ?></h3>
       <p class="mb-0"><?php _e('This will reset the content of the selected element!'); ?></p>
   </div>
</div>

<div data-mwcomponent="accordion" class="mw-ui-box mw-accordion">
    <div class="mw-ui-box-header">
        <label class="mw-ui-check">
            <input type="checkbox" id="also_reset_modules" name="also_reset_modules" value="1" checked>
            <span></span><span> <?php _e('Also reset modules inside the selected edit field'); ?></span>
        </label>
        <span></span>
    </div>



    <div id="select_edit_field_container">
        <div id="select_edit_field_wrap"></div>
    </div>




</div>
<div id="save-toolbar" class="text-center">
    <button onclick="reset();" class="  btn btn-danger"><?php _e('Reset content'); ?></button>
</div>

