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

$fields_group = array();
$group_i = 0;
if (!empty($data)) {
	foreach ($data as $field) {
		
		if($field['type'] == 'breakline') {
			$group_i++;
			continue;
		}
		
		$field['options']['field_size_class'] = template_default_field_size_option($field);
		
        if(isset($params['input_class'])){
            $field['input_class'] = $params['input_class'];
        }

        if (isset($field['options']['field_size']) && is_array($field['options']['field_size'])) {
			$field['options']['field_size_class'] = template_field_size_class($field['options']['field_size'][0]);
		}
		
		if (isset($field['options']['field_size']) && is_string($field['options']['field_size'])) {
			$field['options']['field_size_class'] = template_field_size_class($field['options']['field_size']);
		}
		
		$fields_group[$group_i][] = $field;
	}
}

$ready_fields_group = array();
foreach($fields_group as $field_group_key=>$fields) {
	/* $field_html = '';
	
	if (!in_array($field['type'], $skip_types)) {
		if (isset($field['type']) and $field['type'] == 'price') {
			$field_html .= '<select name="price">';
			$field['make_select'] = true;
			$field_html .=  mw()->fields_manager->make($field);
			$field_html .= '</select>';
		} else {
			$field_html =  mw()->fields_manager->make($field);
		}
	}
	$field['params'] = $params;
	*/
	$ready_fields = array();
	foreach($fields as $field) {
		
		$ready_fields[] = $field;
	}
	
	$ready_fields_group[$field_group_key] = $ready_fields;
}


var_dump($ready_fields_group);
die();
$prined_items_count = 0;

$template_file =  get_option('data-template', $params['id']);;

if (isset($params['template'])) {
    $module_template = $params['template'];
    $template_file = module_templates($config['module'], $module_template);
}

if ($template_file == false) {
    $template_file = module_templates($config['module'], 'default');
} else {
	$template_file = module_templates($config['module'], $template_file);
}

if (!isset($params['no-for-fields'])) {
    echo '<input type="hidden" name="for_id" value="'.$for_id.'"/>';
    echo "\n";
    echo '<input type="hidden" name="for" value="'.$for.'"/>';
} 

if ($template_file != false and is_file($template_file) != false) {
    include($template_file);
}

//echo 1; die();