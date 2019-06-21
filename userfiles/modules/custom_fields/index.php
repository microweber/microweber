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
    } else if (isset($params['data-id'])) {
        $for_id = $params['data-id'];
    } else if (isset($params['id'])) {
        $for_id = $params['id'];
    }

    //$for_id =$params['id'];
    if (isset($params['rel_id'])) {
        $for_id = $params['rel_id'];
    }
}

if (((!isset($for_id)) and isset($params['data-id']))) {
    $for_id = $params['data-id'];

}

if (isset($params['default-fields']) and isset($params['parent-module-id'])) {
    $data = mw()->fields_manager->get($for, $for_id, 1);
    if (!$data) {
        mw()->fields_manager->make_default($for, $for_id, $params['default-fields']);
    }
}


$data = mw()->fields_manager->get($for, $for_id, 1);

$groupFields = array();
$groupI = 0;
if (!empty($data)) {
	foreach ($data as $field) {
		
		if($field['type'] == 'breakline') {
			$groupI++;
			continue;
		}
		
		$field['options']['field_size_class'] = get_default_field_size_option($field);
		
		if (isset($field['options']['field_size']) && is_array($field['options']['field_size'])) {
			$field['options']['field_size_class'] = get_field_size_class($field['options']['field_size'][0]);
		}
		
		if (isset($field['options']['field_size']) && is_string($field['options']['field_size'])) {
			$field['options']['field_size_class'] = get_field_size_class($field['options']['field_size']);
		}
		
		$groupFields[$groupI][] = $field;
	}
}

$prined_items_count = 0;

$template_file = false;

if (isset($params['template'])) {
    $module_template = $params['template'];
    $template_file = module_templates($config['module'], $module_template);

}

if ($template_file == false) {
    $template_file = module_templates($config['module'], 'default');
}


if ($template_file != false and is_file($template_file) != false) {

    include($template_file);
}
 
