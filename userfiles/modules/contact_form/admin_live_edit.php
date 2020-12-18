<?php only_admin_access(); ?>


<script>
    initEditor = function () {
        if (!window.editorLaunced) {
            editorLaunced = true;
            mw.editor({
                element: mwd.getElementById('editorAM'),
                hideControls: ['format', 'fontsize', 'justifyfull']
            });
        }
    }

    $(document).ready(function () {
        mw.tabs({
            nav: ".mw-ui-btn-nav-tabs a",
            tabs: ".tab",
            onclick: function () {
                if (this.id === 'form_options') {
                    initEditor()
                }
            }
        });
    });

</script>


<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title" id="form_options">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-contact-form-settings">

                <div class="mw-ui-field-holder add-new-button text-right">
                    <a class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium" href="<?php print admin_url('view:') . $params['module'] ?>" target="_blank"><?php _e("Check your Inbox"); ?></a>
                </div>

                <module type="settings/list" for_module="<?php print $config['module'] ?>" for_module_id="<?php print $params['id'] ?>"/>

                <module type="contact_form/settings" for_module_id="<?php print $params['id'] ?>"/>

            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-pricefields"></i> Custom Fields
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-contact-form-settings">

                <module type="contact_form/manager/assign_list_to_module" data-for-module="<?php print $config['module_name'] ?>" data-for-module-id="<?php print $params['id'] ?>"/>
                <hr/>

                <h6><?php _e("Contact Form Fields"); ?></h6>
                <module type="custom_fields" view="admin" data-for="module" for-id="<?php print $params['id'] ?>"/>

            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>