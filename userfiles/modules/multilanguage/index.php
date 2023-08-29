<?php
$supported_languages = get_supported_languages(true);

$current_language = array();

// Current language
$current_language_abr = mw()->lang_helper->current_lang();

// $current_language_abr = get_short_abr($current_language_abr);

$current_language['locale'] = $current_language_abr;
$current_language['language'] = $current_language_abr;

// Current language icon
$current_language['icon'] = get_flag_icon($current_language_abr);

// Current language full text
$langs = mw()->lang_helper->get_all_lang_codes();
if (isset($langs[$current_language_abr])) {
    $current_language['language'] = $langs[$current_language_abr];
}

$current_language['display_name'] = '';
$current_language['display_icon'] = '';
$details_for_locale = get_supported_locale_by_locale($current_language['locale']);
if ($details_for_locale) {
    $current_language['display_name'] = $details_for_locale['display_name'];
    $current_language['display_icon'] = $details_for_locale['display_icon'];
}

$module_template = get_option('template', $params['id']);

if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}

if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}

if (is_file($template_file)) {
    include($template_file);
}
