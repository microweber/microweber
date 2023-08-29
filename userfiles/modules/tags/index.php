<?php
$cont_id = false;

if (isset($params['content_id'])) {
    $cont_id = $params['content_id'];
} elseif (isset($params['content-id'])) {
    $cont_id = $params['content-id'];
}
$root_page_id = get_option('data-root-page-id', $params['id']);

if(!$cont_id and $root_page_id){
    $cont_id   = $root_page_id;
}

$content_tags_data = false;

$root_page_id_tags = false;
if($root_page_id){
    $root_page_id_tags = $root_page_id;
} else {
    $root_page_id_tags = MAIN_PAGE_ID;
}

if($root_page_id){
    $tags_url_base = content_link($root_page_id_tags);
} else {
    $tags_url_base = content_link($root_page_id_tags);
}

if ($cont_id) {
    $tags_url_base = content_link($cont_id);
    $content_tags_data = content_tags($cont_id, true);
} else {
    $content_tags_data = content_tags(MAIN_PAGE_ID, true);
}

$content_tags = []; // ALLWAYS MUST BE ARRAY WITH STRING
if ($content_tags_data) {
    foreach ($content_tags_data as $content_tag_data_item) {
            if (isset($content_tag_data_item['tag_name'])) {
            $content_tags[] = $content_tag_data_item['tag_name'];
        }
    }
}
?>

<?php if ($content_tags == true): ?>
    <?php

    $module_template = get_option('template', $params['id']);
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
    }
    ?>
<?php else: ?>
    <?php print lnotif("No tags found."); ?>
<?php endif; ?>
