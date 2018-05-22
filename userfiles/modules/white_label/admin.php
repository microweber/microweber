<?php only_admin_access(); ?>
<style>

    .pw-editor-holder.disabled:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: block;
        background: rgba(255, 255, 255, .5);
    }

    .pw-editor-holder.disabled {
        position: relative;
    }

</style>
<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <img src="<?php print modules_url() ?>white_label/white_label.png" alt="" class="pull-left m-r-10"/>
        <h2 class="pull-left m-t-5"><?php _e("White label"); ?></h2>
        <a title="<?php print _e('Back'); ?>" onclick="history.go(-1)" class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium m-l-10 btn-back pull-left">
            <span class="mw-icon-arrowleft"></span><span><?php print _e('Back'); ?></span>
        </a>
    </div>
</div>
<div class="admin-side-content">
    <?php if (!have_license('modules/white_label')): ?>
        <style>
            .mw-lssssicense-key-activate {
                margin-top: 10%;
                margin-left: 30%;
                margin-right: 30%;
                width: auto;
            }
        </style>

        <div class="module-live-edit-settings">
            <h2><?php _e('Enter the license key to activate White Label'); ?></h2>
            <module type="admin/modules/activate" prefix="modules/white_label"/>
        </div>
        <?php return; ?>
    <?php endif; ?>
    <?php
    $logo_admin = false;
    $logo_live_edit = false;
    $logo_login = false;
    $powered_by_link = false;
    $powered_by_link = false;
    $brand_name = false;
    $disable_marketplace = false;
    $disable_powered_by_link = false;
    $custom_support_url = false;
    $enable_service_links = true;
    $admin_logo_login_link = false;

    $settings = get_white_label_config();
    if (isset($settings['logo_admin'])) {
        $logo_admin = $settings['logo_admin'];
    }
    if (isset($settings['logo_live_edit'])) {
        $logo_live_edit = $settings['logo_live_edit'];
    }
    if (isset($settings['logo_login'])) {
        $logo_login = $settings['logo_login'];
    }

    if (isset($settings['admin_logo_login_link'])) {
        $admin_logo_login_link = $settings['admin_logo_login_link'];
    }


    if (isset($settings['powered_by_link'])) {
        $powered_by_link = $settings['powered_by_link'];
    }
    if (isset($settings['disable_marketplace']) and $settings['disable_marketplace'] != false) {
        $disable_marketplace = $settings['disable_marketplace'];
    }
    if (isset($settings['disable_powered_by_link']) and $settings['disable_powered_by_link'] != false) {
        $disable_powered_by_link = $settings['disable_powered_by_link'];
    }

    if (isset($settings['enable_service_links'])) {
        $enable_service_links = $settings['enable_service_links'];
    }

    if (isset($settings['brand_name']) and $settings['brand_name'] != false) {
        $brand_name = $settings['brand_name'];
    }

    if (isset($settings['custom_support_url']) and $settings['custom_support_url'] != false) {
        $custom_support_url = $settings['custom_support_url'];
    }


    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            var form = $("#white_label_settings_holder");
            form.submit(function () {
                var url = "<?php print api_url() ?>save_white_label_config"; // the script where you handle the form input.

                $.ajax({
                    type: "POST",
                    url: url,
                    data: mw.serializeFields(this), // serializes the form's elements.
                    success: function (data) {
                        mw.notification.success("White label saved");
                    }
                });

                return false; // avoid to execute the actual submit of the form.
            });

            wlFormTime = null;


            form.find('input, textarea').on('input change', function (e) {
                console.log(this.name)
                if (this.name == 'enable_service_links') {
                    $("[name='custom_support_url']")[0].disabled = !this.checked
                }
                else if (this.name == 'disable_powered_by_link') {
                    $(mwd.querySelector('.pw-editor-holder'))[!this.checked ? 'addClass' : 'removeClass']('disabled')
                }

                clearTimeout(wlFormTime);
                var time = 800;
                if (this.type == 'checkbox') {
                    time = 10;
                }
                wlFormTime = setTimeout(function () {
                    form.submit()
                }, time)
            })

            mw.$(".up").each(function () {
                var span = mwd.createElement('span');
                span.className = 'mw-ui-btn';
                span.innerHTML = 'Upload';
                $(this).after(span);
                var uploader = mw.uploader({
                    filetypes: "images",
                    multiple: false,
                    element: span
                });

                uploader.field = this;

                $(uploader).bind("FileUploaded", function (obj, data) {
                    uploader.field.value = data.src;
                    $(uploader.field).trigger('change')
                });
            });


        });
    </script>
    <div class="module-live-edit-settings">
        <form id="white_label_settings_holder">
            <h6 class="m-b-20 m-t-0">Please fill in the form below to take full advantage of your White Label.<br/>
                For instructions use this short guide <a href="https://microweber.com/how-to-activate-your-white-label-license-key" class="mw-ui-link mw-blue" target="_blank">White label user guide</a>.</h6>
            <div class="mw-ui-box">
                <div class="mw-ui-box-content">
                    <div class="mw-ui-row">
                        <div class="mw-ui-col  mw-fields-upload-buttons" style="width: 50%">
                            <div class="mw-ui-col-container mw-fields">
                                <div class="mw-ui-field-holder">
                                    <label class="mw-ui-label"><?php _e('Brand Name'); ?>
                                        <small data-help="Fill in your company or brand name." class="mw-help-tip">?</small>
                                    </label>
                                    <input name="brand_name" option-group="whitelabel" placeholder="<?php _e('Enter the name of your company'); ?>" class="mw-ui-field w100" type="text" value="<?php print  $brand_name; ?>"/>
                                </div>

                                <div class="mw-ui-field-holder">
                                    <label class="mw-ui-label"><?php _e('Admin login - White Label URL'); ?>
                                        <small data-help="Fill in company or brand website URL." class="mw-help-tip">?</small>
                                    </label>
                                    <input name="admin_logo_login_link" option-group="whitelabel" placeholder="<?php _e('Enter website url of your company'); ?>" class="mw-ui-field w100" type="text" value="<?php print  $admin_logo_login_link; ?>"/>
                                </div>


                                <div class="mw-ui-field-holder">
                                    <label class="mw-ui-label"><?php _e('Enable support links'); ?>
                                        <small data-help="Fill in your contact page URL." class="mw-help-tip">?</small>
                                        <div class="mw-switch pull-right inline-switch m-t-0 m-b-10" style="margin-right:10%;">
                                            <input type="checkbox" name="enable_service_links" class="mw_option_field" data-value-checked="1" data-value-unchecked="0" <?php if ($enable_service_links): ?> checked="" <?php endif; ?>>
                                            <span class="mw-switch-off">OFF</span>
                                            <span class="mw-switch-on">ON</span>
                                            <span class="mw-switcher"></span>
                                        </div>
                                    </label>
                                    <input name="custom_support_url" option-group="whitelabel" placeholder="<?php _e('Enter url of your contact page'); ?>" class="mw-ui-field w100" type="text" <?php if (!$enable_service_links): ?> disabled <?php endif; ?> value="<?php print$custom_support_url; ?>"/>
                                </div>

                                <div class="mw-ui-field-holder">
                                    <script>
                                        $(document).ready(function () {
                                            mw.editor({
                                                element: '#powered_by_link_text',
                                                height: 'auto',
                                                hideControls: ['fontfamily', 'fontsize', 'image', 'format', 'alignment', 'ol', 'ul'],
                                                ready: function () {
                                                    <?php if ($disable_powered_by_link): ?>
                                                    $(mwd.querySelector('.pw-editor-holder')).addClass('disabled')
                                                    <?php endif; ?>
                                                }
                                            });

                                        });
                                    </script>
                                    <label class="mw-ui-label bold">
                                        <?php _e('Enable'); ?> "<?php _e('Powered By'); ?>"
                                        <small data-help='Enter the text you would like to see displayed in the footer of your website. Usually the text is "Powered by" followed by your company or brand name.' class="mw-help-tip">?</small>

                                        <div class="mw-switch pull-right inline-switch m-t-0 m-b-10" style="margin-right:10%;">
                                            <input
                                                    type="checkbox"
                                                    name="disable_powered_by_link"
                                                    data-value-checked="0"
                                                    data-value-unchecked="1"
                                                <?php if (!$disable_powered_by_link): ?> checked="" <?php endif; ?>>
                                            <span class="mw-switch-off">OFF</span>
                                            <span class="mw-switch-on">ON</span>
                                            <span class="mw-switcher"></span>
                                        </div>
                                    </label>
                                    <div class="pw-editor-holder">
                                        <textarea name="powered_by_link" id="powered_by_link_text" option-group="whitelabel" placeholder="<?php _e('HTML code for template footer link'); ?>" class="mw-ui-field" type="text"><?php print $powered_by_link; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mw-ui-col mw-fields  mw-fields-upload-buttons" style="width: 50%">
                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label"><?php _e('Logo for Admin panel (recommended size: 180x35 px)'); ?></label>
                                <input name="logo_admin" option-group="whitelabel" placeholder="<?php _e('Upload your logo'); ?>" class="mw-ui-field up" type="text" value="<?php print  $logo_admin; ?>"/>
                            </div>

                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label"><?php _e('Logo for Live-Edit toolbar (recommended size: 50x50 px)'); ?></label>
                                <input name="logo_live_edit" option-group="whitelabel" placeholder="<?php _e('Upload your logo'); ?>" class="mw-ui-field up" type="text" value="<?php print  $logo_live_edit; ?>"/>
                            </div>

                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label"><?php _e('Logo for Login screen (max width 290px)'); ?></label>
                                <input name="logo_login" option-group="whitelabel" placeholder="<?php _e('Upload your logo'); ?>" class="mw-ui-field up" type="text" value="<?php print  $logo_login; ?>"/>
                            </div>

                            <div class="mw-ui-field-holder">
                                <ul class="mw-ui-inline-list">
                                    <li><span class="bold">Microweber Marketplace</span></li>
                                    <li>
                                        <label class="mw-switch">
                                            <input type="checkbox" data-value-checked="1" data-value-unchecked="0" name="disable_marketplace" <?php if (!$disable_marketplace): ?> checked <?php endif; ?>>
                                            <span class="mw-switch-off">Disabled</span>
                                            <span class="mw-switch-on">Enabled</span>
                                            <span class="mw-switcher"></span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

