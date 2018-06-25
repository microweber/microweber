<?php only_admin_access(); ?>

<div class="settings-wrapper">
    <script type="text/javascript">
        mw.require("custom_fields.js", true);
        mw.require("options.js", true);
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
       // $for_id = $params['content-id'];
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

    }



   // $module_id = $for_id;
    //$rand = rand();
    $fields = mw()->ui->custom_fields();
    ?>



    <script type="text/javascript">
        $(document).ready(function () {
            mw.dropdown();
            mw.$('#dropdown-custom-fields').on('change', function () {
                var val = $(this).getDropdownValue();
                var copyof = mw.$('#dropdown-custom-fields li[value="' + val + '"][data-copyof]').dataset('copyof');
                copyof = false;
                // @todo copyof
                //mw.custom_fields.copy_field_by_id('1', '<?php print $for; ?>', '<?php print $for_id; ?>');
                if (copyof == false) {
                    var make_field = {}
                    make_field.rel = '<?php print $for; ?>';
                    make_field.rel_id = '<?php print $for_id; ?>';
                    make_field.type = val;
                    mw.custom_fields.create(make_field, mw_custom_fileds_changed_callback);
                } else {
                    mw.custom_fields.copy_field_by_id(copyof, '<?php print $for; ?>', '<?php print $for_id; ?>');
                }

                mw.$('.mw-dropdown-value', this).html($(this).dataset('default'))
            });
        });

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
        <div id="custom-field-editor" class="mw-ui-box mw-ui-box-content" style="display: none">
            <label class="mw-ui-label">
                <small>
                    <?php _e("Edit"); ?>
                    <b id="which_field"></b>
                    <?php _e("Field"); ?>
                </small>
            </label>
            <div class="custom-field-edit">
                <div class="custom-field-edit-header"><span class="custom-field-edit-title"></span> <span
                            onmousedown="mw_cf_close_edit_window()" class="custom-field-edit-title-head right"
                            style="cursor:pointer;"><?php _e('close'); ?> <span class="mw-ui-arr mw-ui-arr-down "
                                                                                style="opacity:0.6;"></span> </span>
                </div>
                <div class="mw-admin-custom-field-edit-item-wrapper">
                    <div class="mw-admin-custom-field-edit-item mw-admin-custom-field-edit-<?php print $params['id']; ?> "></div>
                </div>
            </div>
        </div>
        <div class="mw-dropdown mw-dropdown-default" id="dropdown-custom-fields" data-value="price"
             data-default="<?php _e("Add New Field"); ?>"><span
                    class="mw-dropdown-value mw-ui-btn mw-ui-btn-invert mw-dropdown-val">
    <?php _e("Add New Field"); ?>
    </span>
            <?php //$exiisting_fields = get_custom_fields('fields=name&rel=content&rel_id=>0&group_by=type,name&return_full=1',true);


            $ex = array();
            $ex['rel_type'] = $for;
            //$ex['rel_id'] = $for_id;
            $ex['group_by'] = 'type';
            $ex['order_by'] = 'created_at desc';
            $name_not_in = array();
            if (is_array($fields)) {
                foreach ($fields as $field => $value) {
                    $name_not_in[] = $field;
                }
            }
            if (!empty($name_not_in)) {
                //$ex['name'] = '[not_in]'.implode(',',$name_not_in);
            }

            //$exiisting_fields = mw()->fields_manager->get_all($ex);


            ?>



            <?php $exiisting_fields = false; //TODO ?>
            <div class="mw-dropdown-content">
                <ul>
                    <?php if (is_array($exiisting_fields)): ?>
                        <?php foreach ($exiisting_fields as $item): ?>

                            <li data-copyof="<?php print $item['id'] ?>" value="<?php print $item['type']; ?>"><span
                                        class="mw-custom-field-icon-text mw-custom-field-icon-<?php print $item['type']; ?>"></span><span
                                        class="mw-custom-field-title"><?php print $item['name']; ?></span></li>

                        <?php endforeach; ?>
                    <?php endif; ?>



                    <?php


                    foreach ($fields as $field => $value) { ?>

                        <li value="<?php print $field; ?>"><span
                                    class="mw-custom-field-icon-<?php print $field; ?>"></span><span
                                    class="mw-custom-field-title"><?php _e($value); ?></span></li>
                    <?php } ?>


                </ul>
            </div>
        </div>
        <hr>
        <div id="custom-fields-box">


            <module data-type="custom_fields/list"   for="<?php print $for ?>" <?php if (isset($for_id)): ?> rel_id='<?php print $for_id; ?>'  <?php endif; ?>  list-preview="true" id="mw_custom_fields_list_preview" />
        </div>
    </div>
</div>


