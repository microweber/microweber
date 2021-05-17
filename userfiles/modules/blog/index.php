<?php

$content_from_id = get_option('content_from_id', $params['id']);
$filtering_the_results = get_option('filtering_the_results', $params['id']);

/*
$contentQuery = \MicroweberPackages\Content\Content::query();
$contentQuery->where('parent', $contentFromId);
$contentResults = $contentQuery->get()->toArray();*/
/*
foreach($contentResults as $content) {
    echo $content['title'] . '<hr />';
}*/

/*
$module_template = get_option('data-template', $params['id']);
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
    print lnotif("No template found. Please choose template.");
}*/
?>
<br />
<br />
<br />
<br />

<?php
if ($filtering_the_results):
?>
    <module type="content_filter" content-id="<?php echo $content_from_id; ?>" class="no-settings" />
<?php
endif;
?>

<module type="posts" data-page-id="<?php echo $content_from_id; ?>" class="no-settings" />
