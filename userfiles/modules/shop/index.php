<?php
$template = get_option('data-template', $params['id']);

$templateFile = false;
if ($template == false and isset($params['template'])) {
    $templateFile = module_templates($params['type'], $params['template']);
} else {
    $templateFile = module_templates($params['type'], 'default');
}

if (is_file($templateFile)) {
    include($templateFile);
}
