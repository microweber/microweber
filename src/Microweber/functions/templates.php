<?php
function get_template_option_group()
{
    return 'mw-template-' . mw()->template->folder_name();
}

function get_template_stylesheet()
{
    $template_settings = mw()->template->get_config();
    $stylesheet_settings = false;
    if (isset($template_settings['stylesheet_compiler']) AND isset($template_settings['stylesheet_compiler']['settings'])) {
        $stylesheet_settings = $template_settings['stylesheet_compiler']['settings'];
    }

    if (!$stylesheet_settings) {
        return;
    }

    return '<link href="' . mw()->template->get_stylesheet($template_settings['stylesheet_compiler']['source_file'], $template_settings['stylesheet_compiler']['css_file'], true) . '" id="theme-style" rel="stylesheet" type="text/css" media="all"/>';
}

function get_template_default_css()
{
    $template_settings = mw()->template->get_config();
    if (isset($template_settings['stylesheet_compiler']) AND isset($template_settings['stylesheet_compiler']['css_file'])) {

    } else {
        return;
    }

    return '<link href="' . template_url() . $template_settings['stylesheet_compiler']['css_file'] . '" id="theme-style" rel="stylesheet" type="text/css" media="all"/>';
}

function get_template_framework()
{
	
	$css_framework = 'mw-ui';
	
	if(isset(mw()->template->get_config()['framework'])) {
		$css_framework = mw()->template->get_config()['framework'];
	}
	
	return $css_framework;
}

function get_template_row_class()
{
	
	$css_framework = get_template_framework();
	
	if ($css_framework == 'mw-ui') {
		return 'mw-flex-row';
	}
	
	if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4') {
		return 'row';
	}
}

function get_template_form_row_class()
{
	$css_framework = get_template_framework();
	
	if ($css_framework == 'mw-ui') {
		return 'mw-flex-row';
	}
	
	if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4') {
		return 'form-row';
	}
}

function get_template_form_group_class()
{
	$css_framework = get_template_framework();
	
	if ($css_framework == 'mw-ui') {
		return '';
	}
	
	if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4') {
		return 'form-group';
	}
}

function get_template_form_group_label_class()
{
	$css_framework = get_template_framework();
	
	if ($css_framework == 'mw-ui') {
		return '';
	}
	
	if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4') {
		return 'control-label';
	}
}

function get_template_input_field_class()
{
	$css_framework = get_template_framework();
	
	if ($css_framework == 'mw-ui') {
		return 'mw-ui-field';
	}
	
	if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4') {
		return 'form-control';
	}
}

function get_field_size_class($field_size = false)
{
	
	$css_framework = get_template_framework();
	
	// Get default field size
	if (!$field_size) {
		return get_default_field_size_option();
	}
	
	if ($css_framework == 'mw-ui') {
		return 'mw-flex-col-md-'.$field_size;
	}
	
	if ($css_framework == 'bootstrap3') {
		return 'col-md-'.$field_size;
	}
	
	if ($css_framework == 'bootstrap4') {
		return 'col-'.$field_size . ' ' . 'col-md-'.$field_size;
	}
	
	return $field_size;
}

function get_default_field_size_option($field = array()) 
{
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
