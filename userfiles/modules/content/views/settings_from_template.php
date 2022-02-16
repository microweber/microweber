<?php

if (!isset($params['content-type'])) {
    return;
}

$template_config = mw()->template->get_config();
$data_fields_conf = false;
$data_fields_values = false;

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
    }
} ?>
<?php if (is_array($data_fields_conf)): ?>
    <div class="card style-1 mb-3 fields">
        <div class="card-header no-border">
            <label class="control-label"><?php _e("Template settings"); ?></label>
            <a href="javascript:;" class="btn btn-link btn-sm" data-toggle="collapse" data-target="#template-settings"><span class="collapse-action-label"><?php _e('Show') ?></span>&nbsp; <?php _e('Template settings') ?></a>
        </div>
        <div class="card-body py-0">
            <div class="collapse" id="template-settings">

                <small class="text-muted"><?php _e("Best product labels examples are: Sale, Promo, Top Offer etc."); ?></small>
                <hr class="thin no-padding">
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
                                                    '|', 'link', 'unlink', 'wordPaste', 'table', 'removeFormat', 'editSource'
                                                ],
                                            ]
                                        });
                                    </script>

                                <?php } else if ($type == 'textarea') { ?>
                                    <textarea name="content_data[<?php print $name; ?>]" class="form-control" placeholder="<?php print $default_value ?>"><?php print $value ?></textarea>
                                <?php } else if ($type == 'color') { ?>
                                <input name="content_data[<?php print $name; ?>]" class="form-control mw-ui-color-picker w100" type="text" placeholder="<?php print $default_value ?>" value="<?php print $value ?>">
                                <?php } else if ($type == 'checkbox') { ?>

                                <label>
                                    <input type="checkbox" name="content_data[<?php print $name; ?>]" <?php if ($value ==1):?> checked="checked" <?php endif;?> value="1" />
                                </label>

                                <?php } else if ($type == 'select') { ?>
                                    <select name="content_data[<?php print $name; ?>]" class="form-control" placeholder="<?php print $default_value ?>">
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
//                                        alert(data.src);
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
                                    <span id="data_<?php print $name; ?>" class="mw-ui-btn"><span class="ico iupload"></span><span>Upload file<span id="data_<?php print $name; ?>_upload_info"></span></span></span>
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
