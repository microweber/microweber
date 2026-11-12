<?php

use MicroweberPackages\Format\Facades\Format;

use Illuminate\Support\Facades\Cookie;
use MicroweberPackages\MetaTags\Facades\FrontendMetaTags;

function template_option_group()
{
    return 'mw-template-' . app()->template_manager->folder_name();
}

function template_dir($param = false)
{
    return app()->template_manager->dir($param);
}

/**
 * Resolve a template folder name to its full directory path (trailing
 * separator included), searching the primary Templates/ root first and then
 * any extra template roots (config('microweber.extra_templates_paths'), e.g.
 * ExtraTemplates/). Falls back to the primary path when the folder is not
 * found anywhere, so behavior for Templates/ templates is unchanged and
 * missing-template code paths stay identical.
 */
function template_full_dir($name)
{
    $name = (string) $name;
    $primary = templates_dir() . $name . DIRECTORY_SEPARATOR;
    if ($name === '' || is_dir($primary)) {
        return $primary;
    }
    foreach ((array) config('microweber.extra_templates_paths', []) as $extra) {
        $candidate = rtrim((string) $extra, '/\\') . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;
        if (is_dir($candidate)) {
            return $candidate;
        }
    }
    return $primary;
}

function template_url($param = false)
{
    return app()->template_manager->url($param);
}

function template_name()
{
    return app()->template_manager->folder_name();
}

if (!function_exists('template_config')) {
    function template_config($templateName = false)
    {
        return app()->template_manager->get_config($templateName);
    }
}

function icon_html($icon)
{

    if (!is_string($icon) || empty($icon)) {
        return '';
    }
    if (str_starts_with($icon, '<i class="')) {
        return Format::clean_xss($icon);
    }
    if (str_starts_with($icon, '<svg')) {
        return Format::clean_xss($icon);
    }
    if (str_starts_with($icon, '<img')) {
        return Format::clean_xss($icon);
    }
    if (str_starts_with($icon, '<span class="')) {
        return Format::clean_xss($icon);
    }
    if (str_starts_with($icon, 'mdi-')) {
        return '<i class="mdi ' . $icon . '"></i>';
    }
    if (str_starts_with($icon, 'mw-')) {
        return '<i class="mw-icon ' . $icon . '"></i>';
    }
    if (str_starts_with($icon, 'icon-')) {
        return '<i class="icon ' . $icon . '"></i>';
    }
    if (str_starts_with($icon, 'fa-')) {
        // The site ships Font Awesome 4.7. AI agents (and copy-pasted snippets)
        // often use Font Awesome 5/6 icon names that don't exist in FA4 — those
        // render as an empty box. Map the common renamed ones back to their FA4
        // equivalent so the glyph shows. Unknown names pass through unchanged.
        static $fa5to4 = [
            'fa-chart-line' => 'fa-line-chart', 'fa-chart-bar' => 'fa-bar-chart',
            'fa-chart-pie' => 'fa-pie-chart', 'fa-chart-area' => 'fa-area-chart',
            'fa-shield-alt' => 'fa-shield', 'fa-shield-halved' => 'fa-shield',
            'fa-tachometer-alt' => 'fa-tachometer', 'fa-gauge' => 'fa-tachometer',
            'fa-gauge-high' => 'fa-tachometer', 'fa-sign-in-alt' => 'fa-sign-in',
            'fa-sign-out-alt' => 'fa-sign-out', 'fa-right-from-bracket' => 'fa-sign-out',
            'fa-arrow-right-from-bracket' => 'fa-sign-out',
            'fa-external-link-alt' => 'fa-external-link', 'fa-up-right-from-square' => 'fa-external-link',
            'fa-pencil-alt' => 'fa-pencil', 'fa-pen' => 'fa-pencil',
            'fa-pen-to-square' => 'fa-pencil-square-o', 'fa-edit' => 'fa-pencil-square-o',
            'fa-trash-alt' => 'fa-trash', 'fa-trash-can' => 'fa-trash',
            'fa-phone-alt' => 'fa-phone', 'fa-phone-flip' => 'fa-phone',
            'fa-magnifying-glass' => 'fa-search', 'fa-cart-shopping' => 'fa-shopping-cart',
            'fa-bag-shopping' => 'fa-shopping-bag', 'fa-basket-shopping' => 'fa-shopping-basket',
            'fa-xmark' => 'fa-times', 'fa-circle-xmark' => 'fa-times-circle',
            'fa-circle-check' => 'fa-check-circle', 'fa-circle-info' => 'fa-info-circle',
            'fa-circle-exclamation' => 'fa-exclamation-circle',
            'fa-triangle-exclamation' => 'fa-exclamation-triangle',
            'fa-house' => 'fa-home', 'fa-gear' => 'fa-cog', 'fa-gears' => 'fa-cogs',
            'fa-screwdriver-wrench' => 'fa-wrench', 'fa-wand-magic' => 'fa-magic',
            'fa-wand-magic-sparkles' => 'fa-magic', 'fa-person' => 'fa-user',
            'fa-user-gear' => 'fa-user', 'fa-lock-open' => 'fa-unlock',
            'fa-rotate' => 'fa-refresh', 'fa-arrows-rotate' => 'fa-refresh',
            'fa-floppy-disk' => 'fa-floppy-o', 'fa-image' => 'fa-picture-o',
            'fa-images' => 'fa-picture-o', 'fa-calendar-days' => 'fa-calendar',
            'fa-clock' => 'fa-clock-o', 'fa-location-dot' => 'fa-map-marker',
            'fa-map-location-dot' => 'fa-map-marker', 'fa-envelope-open-text' => 'fa-envelope',
            'fa-paper-plane-top' => 'fa-paper-plane', 'fa-bolt-lightning' => 'fa-bolt',
            'fa-circle-user' => 'fa-user-circle', 'fa-user-group' => 'fa-users',
            'fa-people-group' => 'fa-users', 'fa-gauge-simple' => 'fa-tachometer',
            'fa-link-slash' => 'fa-chain-broken', 'fa-ban' => 'fa-ban',
            'fa-face-smile' => 'fa-smile-o', 'fa-star-half-stroke' => 'fa-star-half-o',
        ];
        $icon = $fa5to4[$icon] ?? $icon;
        return '<i class="fa ' . $icon . '"></i>';
    }
    if (str_starts_with($icon, 'glyphicon-')) {
        return '<i class="glyphicon ' . $icon . '"></i>';
    }
    if (str_starts_with($icon, 'mdi-')) {
        return '<i class="mdi ' . $icon . '"></i>';
    }
    if (str_starts_with($icon, 'icon-')) {
        return '<i class="icon ' . $icon . '"></i>';
    }


    return '';
}

function current_template()
{
    return template_name();

}


function template_parent($templateName = false)
{
    return app()->template_manager->getParentTemplate($templateName);

}

function template_stylesheet_url()
{
    $template_settings = app()->template_manager->get_config();

    $stylesheet_settings = false;
    if (isset($template_settings['stylesheet_compiler']) and isset($template_settings['stylesheet_compiler']['settings'])) {
        $stylesheet_settings = $template_settings['stylesheet_compiler']['settings'];
    }

//    if (!$stylesheet_settings) {
//        return;
//    }

    if (!isset($template_settings['stylesheet_compiler']['source_file'])) {
        return;
    }

    if (!isset($template_settings['stylesheet_compiler']['css_file'])) {
        return;
    }

    $stylesheet = app()->template_manager->get_stylesheet($template_settings['stylesheet_compiler']['source_file'], $template_settings['stylesheet_compiler']['css_file'], true);
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
    $template_settings = app()->template_manager->get_config();

    if (isset($template_settings['stylesheet_compiler']) and isset($template_settings['stylesheet_compiler']['css_file'])) {
        return '<link href="' . template_url() . $template_settings['stylesheet_compiler']['css_file'] . '" id="theme-style" rel="stylesheet" type="text/css" media="all"/>';
    } else {
        return;
    }
}

function template_framework()
{

    //   $css_framework = 'mw-ui';
    $css_framework = 'mw-ui';
    $css_framework = 'bootstrap5';

    if (isset(app()->template_manager->get_config()['framework'])) {
        $css_framework = app()->template_manager->get_config()['framework'];
    }

    return $css_framework;
}

function template_row_class()
{

    $css_framework = template_framework();

    if ($css_framework == 'mw-ui') {
        return 'mw-flex-row';
    }

    if ($css_framework == 'tailwind') {
        return 'flex flex-wrap';
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

    if ($css_framework == 'tailwind') {
        return 'grid';
    }

    if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4' || $css_framework == 'bootstrap5') {
        return 'row';
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

    if ($css_framework == 'bootstrap3' || $css_framework == 'bootstrap4' || $css_framework == 'bootstrap5') {
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

function get_template_colors_settings()
{
    $getTemplateConfig = app()->template_manager->get_config();

    if (!isset($getTemplateConfig)) {
        throw new \MicroweberPackages\Template\Exceptions\MissingTemplateException('Template is not selected. Please do to admin panel and select a template.');

    } else if (!isset($getTemplateConfig['dir_name'])) {
        throw new \MicroweberPackages\Template\Exceptions\MissingTemplateException('Template dir_name is not set');
    }

    $optionGroupLess = 'mw-template-' . $getTemplateConfig['dir_name'];

    $colors = [];
    if (isset($getTemplateConfig['stylesheet_compiler']['settings'])) {
        foreach ($getTemplateConfig['stylesheet_compiler']['settings'] as $key => $value) {

            if (isset($value['type'])) {
                if ($value['type'] !== 'color') {
                    continue;
                }
            }

            $value['value'] = get_option($key, $optionGroupLess);
            if (empty($value['value']) && isset($value['default'])) {
                $value['value'] = $value['default'];
            }

            $colors[] = [
                'key' => $key,
                'value' => $value['value'],
            ];
        }
    }
    return $colors;

}

//function get_template_meta_webmaster_tags()
//{
//    $in = new \MicroweberPackages\Template\Adapters\RenderHelpers\TemplateMetaTagsRenderer();
//    return $in->get_template_meta_webmaster_tags();
//
//}

