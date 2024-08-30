<?php

$marqueeOptions = [];
$marqueeOptions['data-template'] = '';
$marqueeOptions['text'] = 'Your cool text here!';
$marqueeOptions['fontSize'] = "46";
$marqueeOptions['typeSpeed'] = "normal";
$marqueeOptions['backSpeed'] = "normal";
$marqueeOptions['shuffle'] = false;
$marqueeOptions['disable_loop'] = false;

$getMarqueeOptions = \MicroweberPackages\Option\Models\ModuleOption::where('option_group', $params['id'])->get();
if (!empty($getMarqueeOptions)) {
    foreach ($getMarqueeOptions as $option) {
        $marqueeOptions[$option['option_key']] = $option['option_value'];
    }
}

$shuffle = false;
if ($marqueeOptions['shuffle'] == 1) {
    $shuffle = true;
}

$loop = true;
if ($marqueeOptions['disable_loop'] == 1) {
    $loop = false;
}

$textsJsonArray = [];
$text = $marqueeOptions['text'];
$expText = explode(PHP_EOL, $text);
if (isset($expText[0])) {
    $textsJsonArray = json_encode($expText);
}

$fontSize = $marqueeOptions['fontSize'];

$typeSpeed = convertTextSpeedToNumber($marqueeOptions['typeSpeed']);
$backSpeed = convertTextSpeedToNumber($marqueeOptions['backSpeed']);

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
