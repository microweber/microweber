<?php
// include 'functions.php';

$skip_types = array();
$for = 'module';
if (isset($params['for'])) {
    $for = $params['for'];
}

if (isset($params['data-skip-type'])) {
    $skip_types = explode(',', $params['data-skip-type']);
    $skip_types = array_trim($skip_types);
}

if (isset($params['content-id'])) {
    $for_id = $params['content-id'];
    $for = 'content';
} elseif (isset($params['content_id'])) {
    $for_id = $params['content_id'];
    $for = 'content';
} else {

    if (isset($params['for_id'])) {
        $for_id = $params['for_id'];
    } else if (isset($params['for-id'])) {
        $for_id = $params['for-id'];
    }  else if (isset($params['parent-module-id'])) {
    $for_id = $params['parent-module-id'];
}  else if (isset($params['data-id'])) {
        $for_id = $params['data-id'];
    } else if (isset($params['id'])) {
        $for_id = $params['id'];
    }

    //$for_id =$params['id'];
    if (isset($params['rel_id'])) {
        $for_id = $params['rel_id'];
    }
}

if (isset($params['default-fields']) and isset($params['parent-module-id'])) {
    $is_cf_created  = get_option('data-default-custom-fields-are-created', $params['id']);
    if (!$is_cf_created) {
        $data = mw()->fields_manager->get(['rel_type' => $for, 'rel_id' => $for_id, 'return_full' => true]);

        if (empty($data)) {
            mw()->fields_manager->makeDefault($for, $for_id, $params['default-fields']);
        }

        if (is_admin()) {
            $option = array();
            $option['option_value'] = '1';
            $option['option_key'] = 'data-default-custom-fields-are-created';
            $option['option_group'] = $params['id'];
            save_option($option);
        }
    }
}

$data = mw()->fields_manager->get(['rel_type'=>$for,'rel_id'=>$for_id, 'return_full'=>true]);

$formHasUpload = false;
$fields_group = array();
$group_i = 0;
if (!empty($data)){
    foreach ($data as $field) {

        if ($field['type'] == 'breakline') {
            $group_i++;
            continue;
        }

        if ($field['type'] == 'upload') {
            $formHasUpload = true;
        }

        $field['options']['field_size_class'] = template_default_field_size_option($field);

        if (isset($params['input_class'])) {
            $field['input_class'] = $params['input_class'];
        }

        if (isset($field['options']['field_size']) && is_array($field['options']['field_size'])) {
            $field['options']['field_size'] = $field['options']['field_size'][0];
            $field['options']['field_size_class'] = template_field_size_class($field['options']['field_size'][0]);
        }

        if (isset($field['options']['field_size']) && is_string($field['options']['field_size'])) {
            $field['options']['field_size'] = $field['options']['field_size'];
            $field['options']['field_size_class'] = template_field_size_class($field['options']['field_size']);
        }

        $fields_group[$group_i][] = $field;
    }
}

$prined_items_count = false;
$ready_fields_group = array();
foreach ($fields_group as $field_group_key => $fields) {

    $ready_fields = array();
    // $default_fields = array();
    $price_fields = array();
    foreach ($fields as $field) {
        if (!in_array($field['type'], $skip_types)) {
            if (isset($field['type']) and $field['type'] == 'price') {
                $price_fields[] = $field;
            } else {
                $field['params'] = $params;
                $ready_fields[] = array('html' => mw()->fields_manager->make($field));
            }
        }
    }

    if (!in_array('price', $skip_types) and is_array($price_fields)) {

        $field_html = '';

        $price_fields_count = count($price_fields);

        if ($price_fields_count > 1) {


            $field_html .= '<select name="price">';
        }

        foreach ($price_fields as $field) {
            $field['make_select'] = false;
            if ($price_fields_count > 1) {
                $field['make_select'] = true;
            }
            $field_html .= mw()->fields_manager->make($field);
        }

        if ($price_fields_count > 1) {
            $field_html .= '</select>';
        }

        $ready_fields[] = array(
            'html' => $field_html
        );
    }

    $ready_fields_group[$field_group_key] = $ready_fields;
}

$fields_group = $ready_fields_group;

$template_file = get_option('data-template', $params['id']);
if (!$template_file) {
    $template_file = normalize_path(__DIR__.DS.'templates'.DS.template_framework() . '/index.php', false);
}


if (isset($params['template'])) {
    $module_template = $params['template'];
    $template_file = module_templates($config['module'], $module_template);

    if ($template_file == false) {

        $template_index_file = module_dir($config['module']) . 'templates'. DS . $module_template . DS . 'index.php';

        if (is_file($template_index_file)) {
            $template_file = $template_index_file;
        }
    }
}


if ($template_file == false) {
    $template_file = module_templates($config['module'], 'default');

} elseif (is_file($template_file) ) {

} else {
    $template_file = module_templates($config['module'], $template_file);
}

if (!isset($params['no-for-fields'])) {
    echo '<input type="hidden" name="for_id" value="' . $for_id . '"/>';
    echo "\n";
    echo '<input type="hidden" name="for" value="' . $for . '"/>';
}

if ($formHasUpload) {
    echo '
    <script>
        /**
         * Add enctype="multipart/form-data"
         * add method="post"
         */
        (function() {
            var checkForm = $(\'#'.$params['id'].'\').closest("form");
            checkForm.attr(\'enctype\', \'multipart/form-data\');
            checkForm.attr(\'method\', \'post\');
        })();
    </script>
';
}


if ($template_file != false and is_file($template_file) != false) {
    include($template_file);
}
