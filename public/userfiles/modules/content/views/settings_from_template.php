<?php

if (!isset($params['content-type'])) {
    return;
}

$template_config = mw()->template->get_config();
$data_fields_conf = false;
$data_fields_values = false;

$edit_fields_from_template_conf = [];
$edit_fields_from_template_conf_ready = [];

if (!empty($template_config)) {
    if (isset($params['content-type'])) {
        if (isset($template_config['data-fields-' . $params['content-type']]) and is_array($template_config['data-fields-' . $params['content-type']])) {
            $data_fields_conf = $template_config['data-fields-' . $params['content-type']];

            if (isset($params['content-id'])) {
                $data_fields_values = content_data($params['content-id']);


            } else if (isset($params['category-id'])) {
                $data_fields_values = mw()->data_fields_manager->get_values('rel_type=category&rel_id=' . $params['category-id']);
            }
        }


        if (isset($template_config['edit-fields-' . $params['content-type']]) and is_array($template_config['edit-fields-' . $params['content-type']])) {
            $edit_fields_from_template_conf_items = $template_config['edit-fields-' . $params['content-type']];
            $edit_filed_values = [];

            foreach ($edit_fields_from_template_conf_items as $edit_fields_from_template_conf) {
                if (isset($edit_fields_from_template_conf['name'])) {
                    if (isset($data['id']) and $data['id'] != 0) {
                        $get_edit_field_values = [];
                        $get_edit_field_values['rel_id'] = $data['id'];
                        $get_edit_field_values['rel_type'] = 'content';
                        $get_edit_field_values['name'] = $edit_fields_from_template_conf['name'];
                        $edit_filed_values = get_content_field($get_edit_field_values);
                        $edit_fields_from_template_conf['value'] = $edit_filed_values;
                    } else {
                        $edit_fields_from_template_conf['value'] = '';
                        $edit_fields_from_template_conf_ready[] = $edit_fields_from_template_conf;

                    }
                }
            }

        }
    }
}

 ?>
<?php if (is_array($data_fields_conf)): ?>
    <div class="card-body mb-3 fields">
        <div class="card-header no-border  d-flex align-items-center gap-3 mb-3">
            <a href="javascript:;" class="mw-admin-action-links mw-adm-liveedit-tabs d-flex align-items-center" data-bs-toggle="collapse" data-bs-target="#template-settings"><span class="collapse-action-label"></span>&nbsp;
                <?php _e('Show template settings') ?>
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"/></svg>
            </a>
        </div>
        <div class="card-body py-0">
            <div class="collapse" id="template-settings">

                <small class="text-muted"><?php _e("Best product labels examples are: Sale, Promo, Top Offer etc."); ?></small>
                <br>
                <small class="text-muted"><?php _e("If you choose the Percent from the select field, it will be calculated automatically from the Price and Offer price of the product."); ?></small>
                <hr class="thin">
                <div class="row">
                        <?php foreach ($data_fields_conf as $item): ?>
                            <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                            <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                            <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                            <?php $type = (isset($item['type'])) ? ($item['type']) : 'text'; ?>
                            <?php $default_value = (isset($item['default_value'])) ? ($item['default_value']) : ''; ?>
                            <?php $type = (isset($item['type'])) ? ($item['type']) : ''; ?>
                            <?php $name = (isset($item['name'])) ? ($item['name']) : url_title($item['title']); ?>
                            <?php $value = (isset($item['value'])) ? ($item['value']) : false; ?>
                            <?php $help = (isset($item['help'])) ? ($item['help']) : false; ?>
                            <?php

                            $select_options = false;
                            if (isset($item['options'])) {
                                $select_options = $item['options'];
                            }
                            if (is_array($data_fields_values) and isset($data_fields_values[$name])) {
                                $value = $data_fields_values[$name];
                            }

                            ?>
                            <div class="form-group col-12">
                                <label> <?php _e($title); ?></label>
                                <?php if ($help) { ?>
                                    <small class="text-muted d-block mb-2"><?php _e($help); ?></small>
                                <?php } ?>

                                <?php if ($type == 'mw_editor') { ?>

                                    <?php
                                    $uniqid = uniqid();
                                    ?>

                                    <textarea name="content_data[<?php print $name; ?>]" id="mw-edit-<?php echo $uniqid; ?>" class="form-control" placeholder="<?php print $default_value ?>"><?php print $value ?></textarea>

                                    <script type="text/javascript">
                                        mw.require('editor.js');

                                        mw.Editor({
                                            selector: '#mw-edit-<?php echo $uniqid; ?>',
                                            mode: 'div',
                                            smallEditor: false,
                                            minHeight: 250,
                                            maxHeight: '70vh',
                                            controls: [
                                                [
                                                    'undoRedo', '|', 'image', '|',
                                                    {
                                                        group: {
                                                            controller: 'bold',
                                                            controls: ['italic', 'underline', 'strikeThrough']
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
                                                    '|', 'link', 'unlink', 'wordPaste',  'removeFormat'/*, 'editSource'*/
                                                ],
                                            ]
                                        });
                                    </script>

                                <?php } else if ($type == 'textarea') { ?>
                                    <textarea name="content_data[<?php print $name; ?>]" class="form-control" placeholder="<?php print $default_value ?>"><?php print $value ?></textarea>
                                <?php } else if ($type == 'color') { ?>
                                <input name="content_data[<?php print $name; ?>]" class="form-control mw-ui-color-picker w100" type="text" placeholder="<?php print $default_value ?>" value="<?php print $value ?>">
                                <?php } else if ($type == 'checkbox') { ?>

                                <input type="checkbox" name="content_data[<?php print $name; ?>]" <?php if ($value ==1):?> checked="checked" <?php endif;?> value="1" />


                                <?php } else if ($type == 'select') { ?>
                                    <select name="content_data[<?php print $name; ?>]" class="form-select" placeholder="<?php print $default_value ?>">
                                        <?php if ($select_options): ?>
                                            <?php foreach ($select_options as $key => $option): ?>
                                                <option value="<?php echo $key; ?>" <?php if ($value == $key): ?>selected<?php endif; ?>><?php echo $option; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                <?php } else if ($type == 'upload') { ?>
                                <input name="content_data[<?php print $name; ?>]" class="form-control" type="text" placeholder="<?php print $default_value ?>" value="<?php print $value ?>">

                                    <script type="text/javascript">

                                        $(document).ready(function () {

                                            var uploader = mw.uploader({
                                                filetypes: "images",
                                                multiple: false,
                                                element: "#data_<?php print $name; ?>"
                                            });

                                            $(uploader).on("FileUploaded", function (event, data) {
                                                mw.$("#data_<?php print $name; ?>_loading").hide();
                                                mw.$("#data_<?php print $name; ?>").show();
                                                mw.$("#data_<?php print $name; ?>_upload_info").html("");

                                                mw.$('input[name="data_<?php print $name; ?>"]').val(data.src);
                                            });

                                            $(uploader).on('progress', function (up, file) {
                                                mw.$("#data_<?php print $name; ?>").hide();
                                                mw.$("#data_<?php print $name; ?>_loading").show();
                                                mw.$("#data_<?php print $name; ?>_upload_info").html(file.percent + "%");
                                            });

                                            $(uploader).on('error', function (up, file) {
                                                mw.notification.error("The file is not uploaded.");
                                            });
                                        });
                                    </script>
                                    <span id="data_<?php print $name; ?>" class="  btn btn-primary"><span class="ico iupload"></span><span>Upload file<span id="data_<?php print $name; ?>_upload_info"></span></span></span>
                                <?php } else { ?>
                                <input name="content_data[<?php print $name; ?>]" class="form-control" type="text" placeholder="<?php print $default_value ?>" value="<?php print $value ?>">
                                <?php } ?>
                            </div>
                        <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($edit_fields_from_template_conf_ready)): ?>
<div class="card-body mb-3 fields">
    <div class="card-header no-border">
        <label class="form-label"><?php _e("Template Edit Fields"); ?></label>
        <button  type="button" class="btn btn btn-sm btn-outline-primary btn-link-to-bordered" data-bs-toggle="collapse" data-bs-target="#template-edit-fields"><span class="collapse-action-label">

                <?php _e('Show Template Edit Fields') ?>


        </button>
    </div>
    <div class="card-body pb-4">
        <div class="collapse" id="template-edit-fields">
            <div class="row">
                <?php

                $formBuilder = App::make(\MicroweberPackages\FormBuilder\FormElementBuilder::class);
                foreach($edit_fields_from_template_conf_ready as $field) {

                    $relType = 'content';
                    if ($params['content-type'] == 'category') {
                        $relType = 'categories';
                    }

                    $contentFieldModel = \MicroweberPackages\ContentField\Models\ContentField::where('rel_type', $relType)
                        ->where('rel_id', $params['content-id'])
                        ->where('field', $field['name'])
                        ->first();
                    ?>

                    <div class="col-md-12 mt-3">
                        <label><?php echo $field['title']; ?></label>
                        <?php
                        $value = '';
                        if ($contentFieldModel) {
                            $value = $contentFieldModel->value;
                        }

                        $readOnly = false;
                        if (isset($field['readonly']) && $field['readonly']) {
                            $readOnly = true;
                        }
                        if ($field['type'] =='richtext') {

                            echo $formBuilder->mwEditor('content_fields['.$field['name'].']')
                                ->setModel($contentFieldModel)
                                ->value($value)
                                ->readOnly($readOnly)
                                ->setReadValueFromModelField('value')
                                ->autocomplete('off');

                        } else {

                            echo $formBuilder->text('content_fields['.$field['name'].']')
                                ->setModel($contentFieldModel)
                                ->value($value)
                                ->readOnly($readOnly)
                                ->setReadValueFromModelField('value')
                                ->placeholder($field['title'])
                                ->autocomplete('off');
                        }
                        ?>
                    </div>

                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
