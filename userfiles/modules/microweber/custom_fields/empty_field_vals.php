<?php
if (!isset($data['id'])) {
    $data['id'] = 0;
}
if (!isset($data['name'])) {
    $data['name'] = '';
}
if (!isset($field_type)) {
    $field_type = 'text';
}
if (!isset($data['custom_field_required'])) {
    $data['custom_field_required'] = 'n';
}
if (!isset($data['custom_field_is_active'])) {
    $data['custom_field_is_active'] = 'y';
}
if (!isset($data['custom_field_help_text'])) {
    $data['custom_field_help_text'] = '';
}
if (!isset($data['value'])) {
    $data['value'] = '';
}
if (!isset($data['values'])) {
    $data['values'] = '';
}

if (isset($params['for_module_id'])) {
    $for_module_id = $params['for_module_id'] ;
} else {
 $for_module_id = false ;	
}
if (isset($params['input-class'])) {
     $data['input_class'] = $params['input-class'];
} elseif (isset($params['input_class'])) {
     $data['input_class'] = $params['input_class'];
} else {
	  $data['input_class'] = ' ';
	
}