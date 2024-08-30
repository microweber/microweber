<?php
$terms = get_option('shop_require_terms', 'website') == 1;
$template = get_option('data-template', $params['id']);

$terms_label = get_option('terms_label', 'checkout');
$terms_url = get_option('terms_url', 'checkout');

if (!$terms_url) {
    $terms_url = site_url() . 'terms';
}

if (!$terms_label) {
    $terms_label = _e('I agree with the Terms and Conditions', true);
} else {
    $terms_label = _e($terms_label, true);

}


$template_file = false;
$module_template = false;
if ($template != false and strtolower($template) != 'none') {
    $template_file = module_templates($params['type'], $template);

} else {
    if ($template == false and isset($params['template'])) {
        $module_template = $params['template'];
        $template_file = module_templates($params['type'], $module_template);
    } else {
        $template_file = module_templates($params['type'], 'default');
    }
}

if (is_file($template_file)) {
    include($template_file);
}
