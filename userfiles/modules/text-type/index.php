<?php

$marqueeOptions = [];
$marqueeOptions['data-template'] = '';
$marqueeOptions['text'] = 'Your cool text here!';
$marqueeOptions['fontSize'] = "46";
$marqueeOptions['animationSpeed'] = "normal";

$getMarqueeOptions = \MicroweberPackages\Option\Models\ModuleOption::where('option_group', $params['id'])->get();
if (!empty($getMarqueeOptions)) {
    foreach ($getMarqueeOptions as $option) {
        $marqueeOptions[$option['option_key']] = $option['option_value'];
    }
}

$textsJsonArray = [];
$text = $marqueeOptions['text'];
$expText = explode(PHP_EOL, $text);
if (isset($expText[0])) {
    $textsJsonArray = json_encode($expText);
}

$fontSize = $marqueeOptions['fontSize'];

$animationSpeed = 50;
if ($marqueeOptions['animationSpeed'] == 'slow') {
    $animationSpeed = 40;
}
if ($marqueeOptions['animationSpeed'] == 'medium') {
    $animationSpeed = 45;
}
if ($marqueeOptions['animationSpeed'] == 'high') {
    $animationSpeed = 30;
}
if ($marqueeOptions['animationSpeed'] == 'fast') {
    $animationSpeed = 20;
}
if ($marqueeOptions['animationSpeed'] == 'ultra_fast') {
    $animationSpeed = 15;
}

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
