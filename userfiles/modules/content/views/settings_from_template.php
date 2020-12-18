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
                $data_fields_values = mw()->data_fields_manager->get_values('rel_type=categories&rel_id=' . $params['category-id']);
            }
        }
    }
} ?>
<?php if (is_array($data_fields_conf)): ?>

    <div class="mw-ui-row">
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <?php foreach ($data_fields_conf as $item): ?>
                    <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                    <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                    <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                    <?php $type = (isset($item['type'])) ? ($item['type']) : 'text'; ?>
                    <?php $default_value = (isset($item['default_value'])) ? ($item['default_value']) : ''; ?>
                    <?php $type = (isset($item['type'])) ? ($item['type']) : ''; ?>
                    <?php $name = (isset($item['name'])) ? ($item['name']) : url_title($item['title']); ?>
                    <?php $value = (isset($item['value'])) ? ($item['value']) : false; ?>
                    <?php

                    $select_options = false;
                    if (isset($item['options'])) {
                        $select_options = $item['options'];
                    }
                    if (is_array($data_fields_values) and isset($data_fields_values[$name])) {
                        $value = $data_fields_values[$name];
                    }

                    ?>
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"> <?php print $title; ?> </label>
                        <?php if ($type == 'textarea') { ?>
                            <textarea name="data_<?php print $name; ?>" class="mw-ui-field w100" placeholder="<?php print $default_value ?>"><?php print $value ?></textarea>
                        <?php } else if ($type == 'color') { ?>
                        <input name="data_<?php print $name; ?>" class="mw-ui-field mw-ui-color-picker w100" type="text" placeholder="<?php print $default_value ?>" value="<?php print $value ?>">
                        <?php } else if ($type == 'select') { ?>
                            <select name="data_<?php print $name; ?>" class="mw-ui-field w100" placeholder="<?php print $default_value ?>">
                                <?php if ($select_options): ?>
                                    <?php foreach ($select_options as $key => $option): ?>
                                        <option value="<?php echo $key; ?>" <?php if ($value == $key): ?>selected<?php endif; ?>><?php echo $option; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        <?php } else if ($type == 'upload') { ?>
                        <input name="data_<?php print $name; ?>" class="mw-ui-field w100" type="text" placeholder="<?php print $default_value ?>" value="<?php print $value ?>">

                            <script type="text/javascript">

                                $(document).ready(function () {

                                    var uploader = mw.uploader({
                                        filetypes: "images",
                                        multiple: false,
                                        element: "#data_<?php print $name; ?>"
                                    });

                                    $(uploader).bind("FileUploaded", function (event, data) {
                                        mw.$("#data_<?php print $name; ?>_loading").hide();
                                        mw.$("#data_<?php print $name; ?>").show();
                                        mw.$("#data_<?php print $name; ?>_upload_info").html("");
//                                        alert(data.src);
                                        mw.$('input[name="data_<?php print $name; ?>"]').val(data.src);
                                    });

                                    $(uploader).bind('progress', function (up, file) {
                                        mw.$("#data_<?php print $name; ?>").hide();
                                        mw.$("#data_<?php print $name; ?>_loading").show();
                                        mw.$("#data_<?php print $name; ?>_upload_info").html(file.percent + "%");
                                    });

                                    $(uploader).bind('error', function (up, file) {
                                        mw.notification.error("The file is not uploaded.");
                                    });

                                });
                            </script>
                            <span id="data_<?php print $name; ?>" class="mw-ui-btn"><span class="ico iupload"></span><span>Upload file<span id="data_<?php print $name; ?>_upload_info"></span></span></span>
                        <?php } else { ?>
                        <input name="data_<?php print $name; ?>" class="mw-ui-field w100" type="text" placeholder="<?php print $default_value ?>" value="<?php print $value ?>">
                        <?php } ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
