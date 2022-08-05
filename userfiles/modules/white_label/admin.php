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
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
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
            $hide_white_label_module_from_list = false;

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


            if (isset($settings['hide_white_label_module_from_list']) and $settings['hide_white_label_module_from_list'] != false) {
                $hide_white_label_module_from_list = $settings['hide_white_label_module_from_list'];
            }

            if (isset($settings['marketplace_repositories_urls']) and $settings['marketplace_repositories_urls'] != false) {
                $marketplace_repositories_urls = $settings['marketplace_repositories_urls'];
                if (is_array($marketplace_repositories_urls)) {
                    $marketplace_repositories_urls = implode(',', $marketplace_repositories_urls);
                }
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
                                mw.trigger('whiteLabelModuleSettingsUpdated', data);
                             }
                        });

                        return false; // avoid to execute the actual submit of the form.
                    });

                    wlFormTime = null;
                    form.find('input, textarea').on('input change', function (e) {
                        if (this.name == 'enable_service_links') {
                            $("[name='custom_support_url']")[0].disabled = !this.checked
                        } else if (this.name == 'disable_powered_by_link') {
                            $(document.querySelector('.pw-editor-holder'))[!this.checked ? 'addClass' : 'removeClass']('disabled')
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
                        var span = document.createElement('button');
                        span.className = 'btn btn-primary mb-2';
                        span.innerHTML = 'Upload';
                        $(this).next().html(span);
                        var uploader = mw.uploader({
                            filetypes: "images",
                            multiple: false,
                            element: span
                        });

                        uploader.field = this;

                        $(uploader).on("FileUploaded", function (obj, data) {
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
                        For instructions use this short guide <a href="https://microweber.com/how-to-activate-your-white-label-license-key" class="mw-ui-link mw-blue" target="_blank"> White label user guide</a>.
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
                                <script>mw.require('editor.js')</script>
                                <script>
                                    $(document).ready(function () {
                                        mweditor = mw.Editor({
                                            selector: '#powered_by_link_text',
                                            mode: 'div',
                                            smallEditor: false,
                                            minHeight: 150,
                                            maxHeight: '70vh',
                                            controls: [
                                                [
                                                    'undoRedo', '|', 'image', '|',
                                                    {
                                                        group: {
                                                            icon: 'mdi mdi-format-bold',
                                                            controls: ['bold', 'italic', 'underline', 'strikeThrough']
                                                        }
                                                    },
                                                    '|',
                                                    {
                                                        group: {
                                                            icon: 'mdi mdi-format-align-left',
                                                            controls: ['align']
                                                        }
                                                    },
                                                    '|', 'format',
                                                    {
                                                        group: {
                                                            icon: 'mdi mdi-format-list-bulleted-square',
                                                            controls: ['ul', 'ol']
                                                        }
                                                    },
                                                    '|', 'link', 'unlink', 'wordPaste', 'table'
                                                ],
                                            ]
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
                                <small class="text-muted d-block mb-2"><?php _e('Recommended size: 180x35 px'); ?></small>


                                <div class="input-group mb-3">
                                    <input name="logo_admin" option-group="whitelabel" placeholder="<?php _e('Upload your logo'); ?>" class="form-control up" type="text" value="<?php print  $logo_admin; ?>"/>
                                    <div class="input-group-append"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php _e('Logo for Live-Edit toolbar'); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e('Recommended size: 50x50 px'); ?></small>
                                <div class="input-group mb-3">
                                    <input name="logo_live_edit" option-group="whitelabel" placeholder="<?php _e('Upload your logo'); ?>" class="form-control up" type="text" value="<?php print  $logo_live_edit; ?>"/>
                                    <div class="input-group-append"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php _e('Logo for Login screen'); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e('Recommended size: max width 290px'); ?></small>
                                <div class="input-group mb-3">
                                    <input name="logo_login" option-group="whitelabel" placeholder="<?php _e('Upload your logo'); ?>" class="form-control up" type="text" value="<?php print  $logo_login; ?>"/>
                                    <div class="input-group-append"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="disable_marketplace" id="disable_marketplace" class="custom-control-input" <?php if ($disable_marketplace == '0'): ?>checked<?php endif; ?> data-value-checked="0" data-value-unchecked="1" value="1"/>
                                    <label class="custom-control-label" for="disable_marketplace"><?php _e('Microweber Marketplace'); ?></label>
                                </div>
                                <small class="text-muted d-block mb-2"><?php _e('Allow users to see Microweber Marketplace'); ?></small>
                            </div>


                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="hide_white_label_module_from_list" id="hide_white_label_module_from_list" class="custom-control-input" <?php if ($hide_white_label_module_from_list and $hide_white_label_module_from_list == '1'): ?>checked<?php endif; ?> data-value-checked="1" data-value-unchecked="0" value="1"/>
                                    <label class="custom-control-label" for="hide_white_label_module_from_list"><?php _e('Hide white label module from list'); ?></label>
                                </div>
                                <small class="text-muted d-block mb-2"><?php _e('Hide the white label module from list of modules'); ?></small>

                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php _e('Custom Marketplace Package Manager URL'); ?></label>
                                <input name="marketplace_repositories_urls" option-group="marketplace_repositories_urls" placeholder="<?php _e('URL'); ?>" class="form-control" type="text" value="<?php print  $marketplace_repositories_urls; ?>"/>
                            </div>
                        </div>
                    </div>
                    <div id="white_label-whmcs-admin-module-holder">
                    </div>

                </form>
            </div>
        </div>

      <div class="d-flex">
          <a href="<?php print admin_url() ?>view:modules/load_module:white_label__admin_colors"  class="btn btn-primary mx-1" >Color schemes</a>

          <a href="javascript:;" id="white_label-whmcs-admin-module-link" class="btn btn-outline-secondary mx-1" onclick="mw.show_white_label_whmcs_settings();">WHMCS settings</a>
          <script>
              mw.show_white_label_whmcs_settings = function () {

                  mw.load_module( 'white_label/whmcs/admin',"#white_label-whmcs-admin-module-holder");
                  $("#white_label-whmcs-admin-module-link").hide();
              }
          </script>



          <a href="javascript:;" class="btn btn-outline-secondary mx-1" onclick="mw.show_licenses_modal();">My Licenses</a>
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

        <div class="text-end text-right">
            <button form="white_label_settings_holder" type="submit" class="btn btn-lg btn-success btn-save " ><span>Save</span></button>
        </div>
    </div>
</div>

