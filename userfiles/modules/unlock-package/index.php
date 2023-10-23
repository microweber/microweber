<?php
$template = get_option('data-template', $params['id']);

if ($template == false and isset($params['template'])) {
    $template = $params['template'];
}
if ($template != false) {
    $template_file = module_templates($config['module'], $template);
} else {
    $template_file = module_templates($config['module'], 'default');

}
if ($template_file != false and is_file($template_file)) {
    include($template_file);
}
?>
