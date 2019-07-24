<?php

function get_field_size_class($field_size = false){
	
	$css_framework = get_template_framework();
	
	if (!$field_size) {
		return get_default_field_size_option();
	}

	return $field_size; 
}

function get_default_field_size_option($field = array()) {
	
	$css_framework = get_template_framework();
	
	if ($css_framework == 'mw-ui') {
		return 'mw-flex-col-md-12 mw-flex-col-sm-12 mw-flex-col-xs-12';
	}
	
	if ($css_framework == 'bootstrap3') {
		return 'col-md-12 col-sm-12 col-xs-12';
	}
	
	if ($css_framework == 'bootstrap4') {
		return 'col-12 col-sm-12 col-xs-12';
	}
	
}

function get_field_size_options() {
	
	$css_framework = get_template_framework();

	if ($css_framework == 'mw-ui') {
		for ($i = 1; $i <= 12; $i++) {
			$options['mw-flex-col-md-' . $i . ' mw-flex-col-sm-12 mw-flex-col-xs-12'] = 'mw-flex-col-md-' . $i;
		}
	}
	
	if ($css_framework == 'bootstrap3') {
		$options['col'] = 'col';
		for ($i = 2; $i <= 12; $i++) {
			$options['col-md-' . $i] = 'col-md-' . $i;
		}
	}
	
	if ($css_framework == 'bootstrap4') {
		$options['col'] = 'col';
		for ($i = 2; $i <= 12; $i++) {
			$options['col-' . $i] = 'col-' . $i;
		}
	}
	
	return $options;
}

function get_template_framework() {
	
	$css_framework = 'mw-ui';
	
	if(isset(mw()->template->get_config()['framework'])) {
		$css_framework = mw()->template->get_config()['framework'];
	}
	
	return $css_framework;
}

function get_template_row_class() {
	
	$css_framework = get_template_framework();
	
	if ($css_framework == 'mw-ui') {
		return 'mw-flex-row';
	}
	
	if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4') {
		return 'row';
	}
}



function get_template_input_field_class() {

    $css_framework = get_template_framework();

    if ($css_framework == 'mw-ui') {
        return 'mw-ui-field';
    }

    if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4') {
        return 'form-control';
    }
}