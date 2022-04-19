<?php

function template_option_group()
{
    return 'mw-template-' . mw()->template->folder_name();
}
//
//api_expose('template_stylesheet_reset_and_get_link' , function (){
//
//    $template_settings = mw()->template->get_config();
//
//    $stylesheet = mw()->template->get_stylesheet($template_settings['stylesheet_compiler']['source_file'], $template_settings['stylesheet_compiler']['css_file'], false);
//
//
//    return $stylesheet;
//
//});
function template_stylesheet_url()
{
    $template_settings = mw()->template->get_config();

    $stylesheet_settings = false;
    if (isset($template_settings['stylesheet_compiler']) AND isset($template_settings['stylesheet_compiler']['settings'])) {
        $stylesheet_settings = $template_settings['stylesheet_compiler']['settings'];
    }

    if (!$stylesheet_settings) {
        return;
    }
    if(!isset($template_settings['stylesheet_compiler']['source_file'])){
        return;
    }
    if(!isset($template_settings['stylesheet_compiler']['css_file'])){
        return;
    }

    $stylesheet = mw()->template->get_stylesheet($template_settings['stylesheet_compiler']['source_file'], $template_settings['stylesheet_compiler']['css_file'], true);
    return $stylesheet;
}

function template_stylesheet()
{
    $stylesheet = template_stylesheet_url();
    if ($stylesheet) {
        return '<link href="' . $stylesheet . '" id="theme-style" rel="stylesheet" type="text/css" media="all"/>';
    }
}

function template_default_css()
{
    $template_settings = mw()->template->get_config();

    if (isset($template_settings['stylesheet_compiler']) AND isset($template_settings['stylesheet_compiler']['css_file'])) {
        return '<link href="' . template_url() . $template_settings['stylesheet_compiler']['css_file'] . '" id="theme-style" rel="stylesheet" type="text/css" media="all"/>';
    } else {
        return;
    }
}

function template_framework()
{

    $css_framework = 'mw-ui';

    if (isset(mw()->template->get_config()['framework'])) {
        $css_framework = mw()->template->get_config()['framework'];
    }

    return $css_framework;
}

function template_row_class()
{

    $css_framework = template_framework();

    if ($css_framework == 'mw-ui') {
        return 'mw-flex-row';
    }

    if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4' || $css_framework == 'bootstrap5') {
        return 'row';
    }
}

function template_form_row_class()
{
    $css_framework = template_framework();

    if ($css_framework == 'mw-ui') {
        return 'mw-flex-row';
    }

    if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4' || $css_framework == 'bootstrap5') {
        return 'form-row';
    }
}

function template_form_group_class()
{
    $css_framework = template_framework();

    if ($css_framework == 'mw-ui') {
        return '';
    }

    if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4' || $css_framework == 'bootstrap5') {
        return 'form-group';
    }
}

function template_form_group_label_class()
{
    $css_framework = template_framework();

    if ($css_framework == 'mw-ui') {
        return '';
    }

    if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4' || $css_framework == 'bootstrap5') {
        return 'control-label';
    }
}

function template_input_field_class()
{
    $css_framework = template_framework();

    if ($css_framework == 'mw-ui') {
        return 'mw-ui-field';
    }

    if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4'|| $css_framework == 'bootstrap5') {
        return 'form-control';
    }
}

function template_field_size_class($field_size = false)
{

    $css_framework = template_framework();

    // Get default field size
    if (!$field_size) {
        return template_default_field_size_option();
    }

    if ($css_framework == 'mw-ui') {
        return 'mw-flex-col-md-' . $field_size;
    }

    if ($css_framework == 'bootstrap3') {
        return 'col-md-' . $field_size;
    }

    if ($css_framework == 'bootstrap4') {
        return 'col-' . $field_size . ' ' . 'col-md-' . $field_size;
    }

    if ($css_framework == 'bootstrap5') {
        return 'col-' . $field_size . ' ' . 'col-md-' . $field_size;
    }

    return $field_size;
}

function template_default_field_size_option($field = array())
{
    $css_framework = template_framework();

    if ($css_framework == 'mw-ui') {
        return 'mw-flex-col-md-12 mw-flex-col-sm-12 mw-flex-col-xs-12';
    }

    if ($css_framework == 'bootstrap3') {
        return 'col-md-12 col-sm-12 col-xs-12';
    }

    if ($css_framework == 'bootstrap4') {
        return 'col-12 col-sm-12 col-xs-12';
    }

    if ($css_framework == 'bootstrap5') {
        return 'col-12 col-sm-12 col-xs-12';
    }

}

function get_template_default_field_size_option()
{
    return template_default_field_size_option();
}

function get_template_field_size_class()
{
    return template_field_size_class();
}

function get_template_input_field_class()
{
    return template_input_field_class();
}

function get_template_form_group_label_class()
{
    return template_form_group_label_class();
}

function get_template_form_group_class()
{
    return template_form_group_class();
}

function get_template_form_row_class()
{
    return template_form_row_class();
}

function get_template_row_class()
{
    return template_row_class();
}

function get_template_framework()
{
    return template_framework();
}

function get_template_default_css()
{
    return template_default_css();
}

function get_template_stylesheet()
{
    return template_stylesheet();
}

function get_template_option_group()
{
    return template_option_group();
}
