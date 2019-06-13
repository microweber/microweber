<?php

function get_field_size_class($field_size = false){
	
	$css_framework = get_template_framework();
	
	if (!$field_size) {
		if ($css_framework == 'mw-ui') {
			$field_size = 'mw-flex-col-md-12';
		}
		if ($css_framework == 'bootstrap3') {
			$field_size = 'col-md-12';
		}
		if ($css_framework == 'bootstrap4') {
			$field_size = 'col-12';
		}
	}

	return $field_size; 
}

function get_default_field_size_option() {
	
	$css_framework = get_template_framework();
	
	if ($css_framework == 'mw-ui') {
		return 'mw-flex-col-md-6';
	}
	
	if ($css_framework == 'bootstrap3') {
		return 'col-md-6';
	}
	
	if ($css_framework == 'bootstrap4') {
		return 'col-6';
	}
	
}

function get_field_size_options() {
	
	$css_framework = get_template_framework();

	if ($css_framework == 'mw-ui') {
		for ($i = 1; $i <= 12; $i++) {
			$options['mw-flex-col-md-' . $i] = 'mw-flex-col-md-' . $i;
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