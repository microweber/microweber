<?php must_have_access(); ?>

<?php

return view('microweber-module-custom-fields::admin-module',
    ['params'=>$params]
);

$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card mb-5">
    <div class="card-body <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">

        <div class=" row">
            <div class="card-header px-0">
                <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
            </div>
            <div class="settings-wrapper px-0">
                <script type="text/javascript">
                    mw.require("custom_fields.js", true);
                    //mw.require("options.js", true);
                    mw.require("admin.js", true);
                    mw.require("admin_custom_fields.js", true);
                </script>

                <?php
                $for = 'module';
                $for_id = false;

                $copy_from = false;
                $suggest_from_rel = false;
                $list_preview = false;

                if (isset($params['for'])) {
                    $for = $params['for'];
                }

                if (isset($params['copy_from'])) {
                    $copy_from = $params['copy_from'];
                }

                $hide_preview = '';
                if (isset($params['live_edit'])) {
                    $hide_preview = " live_edit=true ";
                }

                if (isset($params['suggest-from-related']) and $params['suggest-from-related'] != 'false') {
                    $suggest_from_rel = true;
                }

                if (isset($params['list-preview']) and $params['list-preview'] != 'false') {
                    $list_preview = true;
                }

                if (isset($params['data-content-id'])) {
                    $for_id = $params['data-content-id'];
                    $for = 'content';
                } else if (isset($params['content-id'])) {
                    $for_id = $for_module_id = $params['rel_id'] = $params['content-id'];
                    $for = 'content';
                    $for = 'content';
                } elseif (isset($params['content_id'])) {
                    $for_id = $params['content_id'];
                    $for = 'content';
                } elseif (isset($params['for_id'])) {
                    $for_id = $params['for_id'];
                } elseif (isset($params['rel_id'])) {
                    $for_id = $module_id = $params['rel_id'];
                } else if (isset($params['for_id'])) {
                    $for_id = $params['for_id'];
                } else if (isset($params['for-id'])) {
                    $for_id = $params['for-id'];
                } else if (isset($params['parent-module-id'])) {
                    $for_id = $params['parent-module-id'];
                } else if (isset($params['data-id'])) {
                    $for_id = $params['data-id'];
                } else if (isset($params['id'])) {
                    $for_id = $params['id'];
                } elseif (isset($params['data-id'])) {
                    $for_id = $module_id = $params['data-id'];
                }

                $fields = mw()->ui->custom_fields();
                ?>

                <script>


                    function addCustomFieldByVal(fieldName) {
                        $('.js-cf-options').val(fieldName);
                        $('.js-cf-options ').trigger('change');
                    }


                    function addCustomFieldByExisting(fieldId) {

                        var make_field = {}
                        make_field.rel_type = '<?php print $for; ?>';
                        make_field.rel_id = '<?php print $for_id; ?>';
                        make_field.copy_of = fieldId;
                        //   mw.custom_fields.copy_field_by_id(fieldId, '<?php print $for; ?>', '<?php print $for_id; ?>');

                        mw.custom_fields.create(make_field, mw_custom_fileds_changed_callback);
                        mw_cf_toggle_edit_window()
                        mw.notification.success("<?php _ejs("Custom fields are saved"); ?>");
                    }


                    $(document).ready(function () {
                        mw.dropdown();
                        mw.$('.js-cf-options').on('change', function () {
                            var val = $(this).val();
                            var copyof = mw.$('.js-cf-options li[value="' + val + '"][data-copyof]').dataset('copyof');
                            copyof = false;
                            if (copyof == false) {
                                var make_field = {}
                                make_field.rel_type = '<?php print $for; ?>';
                                make_field.rel_id = '<?php print $for_id; ?>';
                                make_field.type = val;
                                mw.custom_fields.create(make_field, mw_custom_fileds_changed_callback);
                                mw_cf_toggle_edit_window()
                                mw.notification.success("<?php _ejs("Custom fields are saved"); ?>");
                            } else {

                                // mw.custom_fields.copy_field_by_id(copyof, '<?php print $for; ?>', '<?php print $for_id; ?>');
                            }
                        });
                    });
                    mw_cf_toggle_edit_window = function () {
                        $('#add-field-select').toggleClass('collapse');
                        $(this).parent().toggleClass('card-closed');
                        $(this).find('.d-flex').toggleClass('justify-content-between');
                    }
                    mw_custom_fileds_changed_callback = function (el) {
                        mw.tools.loading('#quick-add-post-options-items-holder-container');
                        mw.reload_module('#mw_custom_fields_list_preview', function () {
                            mw.admin.custom_fields.initValues();
                            mw.tools.loading('#quick-add-post-options-items-holder-container', false);
                        });
                        mw.custom_fields.after_save();
                    }
                    if (!!window.thismodal) {
                        thismodal.resize(800)
                    }
                </script>

                <div class="module-live-edit-settings">
                    <div id="custom-field-editor" class="card" style="display: none">
                        <label class="mw-ui-label">
                            <small><?php _e("Edit"); ?> <b id="which_field"></b> <?php _e("Field"); ?></small>
                        </label>
                        <div class="custom-field-edit">
                            <div class="custom-field-edit-header">
                                <span class="custom-field-edit-title"></span>
                                <span class="custom-field-edit-title-head right" style="cursor:pointer;"><?php _e('close'); ?>
                                <span class="mw-ui-arr mw-ui-arr-down" style="opacity:0.6;"></span>
                            </span>
                            </div>
                            <div class="mw-admin-custom-field-edit-item-wrapper">
                                <div class="mw-admin-custom-field-edit-item mw-admin-custom-field-edit-<?php print $params['id']; ?>"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <?php
                        $getExistingFields = \MicroweberPackages\CustomField\Models\CustomField::where('rel_type', $for)
                            ->groupBy('name_key')
                            ->orderBy('created_at','desc')
                            ->get();

                        $exiisting_fields = [];
                        if ($getExistingFields != null){
                            $exiisting_fields = $getExistingFields->toArray();
                        }
                        ?>

                        <?php // $exiisting_fields = false; //TODO ?>

                        <div>
                            <div class="card-closed">
                                <div class="card-header no-border js-add-custom-field px-0" style="cursor:pointer" onClick="javascript:mw_cf_toggle_edit_window()">
                                    <div class="d-flex align-items-center btn btn-outline-dark ">

                                        <svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"/></svg>
                                        <span><?php _e("Add new field"); ?></span>
                                    </div>
                                </div>
                                <div class="card-body collapse mt-3" id="add-field-select">
                                    <div class="custom-fields-add-buttons">
                                        <?php if (is_array($exiisting_fields)): ?>

                                            <script>
                                                $(document).ready(function () {
                                                    var cf_existing_search_items = mw.$('.mw-custom-field-existing-item-btn', '.custom-fields-add-buttons');

                                                    mw.$('#cf-add-existing-search', '.custom-fields-add-buttons').on('input', function () {
                                                        mw.tools.search(this.value, cf_existing_search_items, function (found) {
                                                            $(this)[found?'show':'hide']();
                                                        });
                                                    });
                                                });
                                            </script>

                                            <div class="row p-0">
                                                <div class="col-xl-8 col-md-6 col-12">
                                                    <label class="form-label font-weight-bold"><?php _e("Existing fields"); ?></label>
                                                    <small class="d-block mb-2 text-muted"><?php _e("Choose from your existing fields bellow"); ?></small>
                                                </div>
                                                <div class="col-xl-4 col-md-6 col-12">
                                                    <input type="text" class="form-control form-control-sm" aria-label="Search" id="cf-add-existing-search" placeholder="Search">
                                                </div>
                                            </div>

                                            <hr class="w-100 h-100 thin my-2">

                                            <div class="row px-0 py-3">
                                                <?php foreach ($exiisting_fields as $item): ?>
                                                    <div class="col-xl-3 col-md-4 col-6 hover-bg-light text-center py-4">
                                                        <button type="button" class="btn btn-link mx-auto text-decoration-none mw-custom-field-existing-item-btn"
                                                                onclick="javascript:addCustomFieldByExisting('<?php print $item['id']; ?>','<?php print $item['name']; ?>');" style="display: block;">

                                                            <span class="mw-custom-field-icon-text mw-custom-field-icon-<?php print $item['type']; ?>"></span>
                                                            <span class="mw-custom-field-title  small" title="<?php print htmlspecialchars($item['name']); ?>"><?php print $item['name']; ?></span>
                                                            <span class="d-none"><?php print $item['name_key']; ?></span>
                                                        </button>
                                                    </div>

                                                <?php endforeach; ?>
                                            </div>
                                            <hr class="w-100 h-100 thin my-2">
                                        <?php endif; ?>


                                        <select class="js-cf-options" data-live-search="true" data-size="7" style="display: none;">
                                            <?php if (is_array($exiisting_fields)): ?>
                                                <?php foreach ($exiisting_fields as $item): ?>
                                                    <option data-copyof="<?php print $item['id'] ?>" value="<?php print $item['type']; ?>">
                                                        <span class="mw-custom-field-icon-text mw-custom-field-icon-<?php print $item['type']; ?>"></span>
                                                        <span class="mw-custom-field-title" title="<?php print htmlspecialchars($item['name']); ?>"><?php print $item['name']; ?></span>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            <?php foreach ($fields as $field => $value): ?>
                                                <option value="<?php print $field; ?>">
                                                    <span class="mw-custom-field-icon-<?php print $field; ?>"></span>
                                                    <span class="mw-custom-field-title"><?php _e($value); ?></span>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>


                                        <div class="row px-0 py-3">
                                            <label class="form-label font-weight-bold"><?php _e(" Add new fields"); ?></label>
                                            <small class="d-block mb-2 text-muted"><?php _e("Add new custom field from list bellow"); ?></small>
                                        </div>

                                        <div class="d-flex flex-wrap align-items-center">
                                            <?php foreach ($fields as $field => $value): ?>

                                                <div class="col-xl-3 col-md-4 col-6 hover-bg-light text-center py-4">
                                                    <button type="button" class="btn btn-link mx-auto text-decoration-none js-add-custom-field-<?php print $field; ?>" onclick="javascript:addCustomFieldByVal('<?php print $field; ?>');" style="display: block; white-space: normal;">
                                                        <span class="mw-custom-field-icon-<?php print $field; ?>"></span>
                                                        <span class="mw-custom-field-title text-break-line-1 text-center"><?php _e($value); ?></span>
                                                    </button>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="thin">

                    <div class="row p-0">
                        <label class="form-label font-weight-bold"><?php _e("Your fields"); ?></label>
                        <small class="d-block mb-2 text-muted"><?php _e("List of your added custom fields"); ?></small>
                    </div>

                    <div id="custom-fields-box">
                        <?php if (isset($params['live_edit'])): ?>
                            <module type="admin/modules/templates" simple="true"/>
                            <br/>
                        <?php endif; ?>

                        <module data-type="custom_fields/list"
                                for="<?php print $for ?>" <?php if (isset($for_id)): ?> rel_id='<?php print $for_id; ?>'  <?php endif; ?>
                                list-preview="true" id="mw_custom_fields_list_preview"/>
                    </div>


                </div>
            </div>


        </div>
    </div>
</div>
