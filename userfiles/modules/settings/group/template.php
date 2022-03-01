<?php must_have_access(); ?>

<script type="text/javascript">
    mw.require('options.js');
    mw.require('forms.js');
</script>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>

<div class="<?php print $config['module_class'] ?>">
    <?php $data = get_option('current_template', 'template', 1); ?>
    <?php
    if (!isset($data['id'])) {
        $data['id'] = 0;
    }
    if (!isset($data['option_value'])) {
        $data['option_value'] = 'default';
    }
    if (!isset($data['option_key'])) {
        $data['option_key'] = 'current_template';
    }
    ?>


    <script type="text/javascript">
        function mw_set_default_template() {
            var changeTemplateDialog = mw.dialog({
                content: '<div id="mw_admin_change_template_modal_content"></div>',
                title: 'Change Template',
                width: 700,
                id: 'mw_admin_change_template_modal'
            });
            var params = {};
            mw.load_module('settings/group/template_install_modal', '#mw_admin_change_template_modal_content', function() {
                changeTemplateDialog.center();
            }, params);
        }

        $(document).ready(function () {
            $(window).on('templateSelected', function () {
                $(".mw-site-theme-selector").find("[name='active_site_template'] option:selected").each(function (index) {
                    $("#mw_curr_theme_val").val($(this).val());
                });
            });
        });
    </script>

    <?php

    $selected_template =  $data['option_value'];

    $change_template = false;
    if(isset($_GET['template'])){
        $change_template = true;
        $selected_template = $_GET['template'];
    }

    ?>
<?php if($change_template){ ?>
    <div class="alert alert-dismissible alert-primary">
        <?php _e("You must click the apply template button to change your template"); ?>

    </div>

    <?php } ?>


    <div class="mw-site-theme-selector">
        <input id="mw_curr_theme_val" name="current_template" class="mw_option_field mw-ui-field" type="hidden" option-group="template" value="<?php print  $selected_template; ?>" data-id="<?php print  $data['id']; ?>"/>
        <module type="content/views/layout_selector" show_allow_multiple_template="true" show_save_changes_buttons="true" show_full="true" data-active-site-template="<?php print $selected_template ?>" autoload="1" xxlive_edit_styles_check="1" no-default-name="true"/>
    </div>
</div>
