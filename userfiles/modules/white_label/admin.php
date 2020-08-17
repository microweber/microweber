<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
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


        <div>
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
            $marketplace_repositories_urls = false;

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

            if (isset($settings['marketplace_repositories_urls']) and $settings['marketplace_repositories_urls'] != false) {
                $marketplace_repositories_urls = $settings['marketplace_repositories_urls'];
            }
            ?>

            <script type="text/javascript">
                $(document).ready(function () {
                    var form = $("#white_label_settings_holder");
                    form.submit(function () {
                        var url = "<?php print api_url() ?>save_white_label_config"; // the script where you handle the form input.

                        console.log(mw.serializeFields(this));

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
                        if (this.name == 'enable_service_links') {
                            $("[name='custom_support_url']")[0].disabled = !this.checked
                        } else if (this.name == 'disable_powered_by_link') {
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
                        var span = mwd.createElement('button');
                        span.className = 'btn btn-primary mb-2';
                        span.innerHTML = 'Upload';
                        $(this).next().html(span);
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
            <script>
                mw.lib.require('mwui_init');
            </script>
            <div class="module-live-edit-settings">
                <form id="white_label_settings_holder">
                    <p class="mb-4">
                        Please fill in the form below to take full advantage of your White Label.
                        For instructions use this short guide <a href="https://microweber.com/how-to-activate-your-white-label-license-key" class="mw-ui-link mw-blue" target="_blank">White label user guide</a>.
                    </p>

                    <div class="row">
                        <div class="col-sm-6 mw-fields mw-fields-upload-buttons">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Brand Name'); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e('Enter the name of your company'); ?></small>
                                <input name="brand_name" option-group="whitelabel" placeholder="" class="form-control" type="text" value="<?php print  $brand_name; ?>"/>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php _e('Admin login - White Label URL'); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e('Enter website url of your company'); ?></small>
                                <input name="admin_logo_login_link" option-group="whitelabel" placeholder="" class="form-control" type="text" value="<?php print  $admin_logo_login_link; ?>"/>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="enable_service_links" id="enable_service_links" class="mw_option_field custom-control-input" <?php if ($enable_service_links == '1'): ?>checked<?php endif; ?> data-value-checked="1" data-value-unchecked="0" value="1"/>
                                    <label class="custom-control-label" for="enable_service_links"><?php _e('Enable support links'); ?></label>
                                </div>

                                <small class="text-muted d-block mb-2"><?php _e('Enter url of your contact page'); ?></small> 
                                <input name="custom_support_url" option-group="whitelabel" placeholder="" class="form-control" type="url" <?php if ($enable_service_links == '0'): ?> disabled <?php endif; ?> value="<?php print $custom_support_url; ?>"/>
                            </div>

                            <div class="form-group">
                                <script>
                                    $(document).ready(function () {
                                        mw.editor({
                                            element: '#powered_by_link_text',
                                            height: '300px',
                                            hideControls: ['fontfamily', 'fontsize', 'image', 'format', 'alignment', 'ol', 'ul'],
                                            ready: function () {
                                                <?php if ($disable_powered_by_link): ?>
                                                $(mwd.querySelector('.pw-editor-holder')).addClass('disabled')
                                                <?php endif; ?>
                                            }
                                        });
                                    });
                                </script>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="disable_powered_by_link" id="disable_powered_by_link" class="mw_option_field custom-control-input" <?php if ($disable_powered_by_link == '0'): ?>checked<?php endif; ?> data-value-checked="0" data-value-unchecked="1" value="1"/>
                                    <label class="custom-control-label" for="disable_powered_by_link"><?php _e('Enable'); ?> "<?php _e('Powered By'); ?>"</label>
                                </div>

                                <small class="text-muted d-block mb-2"><?php _e('Enter the text you would like to see displayed in the footer of your website. Usually the text is "Powered by" followed by your company or brand name.'); ?></small>

                                <div class="pw-editor-holder">
                                    <textarea name="powered_by_link" id="powered_by_link_text" option-group="whitelabel" placeholder="<?php _e('HTML code for template footer link'); ?>" class="mw-ui-field" type="text"><?php print $powered_by_link; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 mw-fields mw-fields-upload-buttons">
                            <div class="form-group">
                                <label class="control-label"><?php _e('Logo for Admin panel'); ?></label>
                                <small class="text-muted d-block mb-2">Recommended size: 180x35 px</small>


                                <div class="input-group mb-3">
                                    <input name="logo_admin" option-group="whitelabel" placeholder="<?php _e('Upload your logo'); ?>" class="form-control up" type="text" value="<?php print  $logo_admin; ?>"/>
                                    <div class="input-group-append"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php _e('Logo for Live-Edit toolbar'); ?></label>
                                <small class="text-muted d-block mb-2">Recommended size: 50x50 px</small>
                                <div class="input-group mb-3">
                                    <input name="logo_live_edit" option-group="whitelabel" placeholder="<?php _e('Upload your logo'); ?>" class="form-control up" type="text" value="<?php print  $logo_live_edit; ?>"/>
                                    <div class="input-group-append"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php _e('Logo for Login screen'); ?></label>
                                <small class="text-muted d-block mb-2">Recommended size: max width 290px</small>
                                <div class="input-group mb-3">
                                    <input name="logo_login" option-group="whitelabel" placeholder="<?php _e('Upload your logo'); ?>" class="form-control up" type="text" value="<?php print  $logo_login; ?>"/>
                                    <div class="input-group-append"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="disable_marketplace" id="disable_marketplace" class="mw_option_field custom-control-input" <?php if ($disable_marketplace == '0'): ?>checked<?php endif; ?> data-value-checked="0" data-value-unchecked="1" value="1"/>
                                    <label class="custom-control-label" for="disable_marketplace">Microweber Marketplace</label>
                                </div>
                                <small class="text-muted d-block mb-2"><?php _e('Allow users to see Microweber Marketplace'); ?></small>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php _e('Custom Marketplace Package Manager URL'); ?></label>
                                <input name="marketplace_repositories_urls" option-group="marketplace_repositories_urls" placeholder="<?php _e('URL'); ?>" class="form-control" type="text" value="<?php print  $marketplace_repositories_urls; ?>"/>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="text-right">
            <a href="javascript:;" onclick="mw.show_licenses_modal();">My Licenses</a>
            <script>
                mw.show_licenses_modal = function () {
                    var data = {}
                    licensesModal = mw.tools.open_module_modal('settings/group/licenses', data, {
                        title: 'Licenses',
                        skin: 'simple'
                    })
                }
            </script>
        </div>
    </div>
</div>

