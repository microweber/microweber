<?php

$content_from_id = get_option('content_from_id', $params['id']);
$filtering_the_results = get_option('filtering_the_results', $params['id']);
$limit_the_results = get_option('limit_the_results', $params['id']);
$sort_the_results = get_option('sort_the_results', $params['id']);

$limit = 10;
if (isset($_GET['page'][$content_from_id]['limit'])) {
    $limit = (int) $_GET['page'][$content_from_id]['limit'];
}

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
<br />
<br />
<section class="section section-filter edit safe-mode nodrop" field="layout-filter-skin-1-" rel="content">
    <div class="container">


        

    </div>
</section>
