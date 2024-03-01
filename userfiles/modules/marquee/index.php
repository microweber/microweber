<?php

$marqueeOptions = [];
$marqueeOptions['data-template'] = '';
$marqueeOptions['text'] = 'Your cool text here!';
$marqueeOptions['fontSize'] = "26px";
$marqueeOptions['animationSpeed'] = "0.3";

$getMarqueeOptions = \MicroweberPackages\Option\Models\ModuleOption::where('option_group', $params['id'])->get();
if (!empty($getMarqueeOptions)) {
    foreach ($getMarqueeOptions as $option) {
        $marqueeOptions[$option['option_key']] = $option['option_value'];
    }
}

$text = $marqueeOptions['text'];
$fontSize = $marqueeOptions['fontSize'];
$animationSpeed = $marqueeOptions['animationSpeed'];

$module_template = $marqueeOptions['data-template'];
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}

if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}

if (is_file($template_file) != false) {
    include($template_file);
} else {
    print lnotif(_e("No template found. Please choose template."));
}
?>
