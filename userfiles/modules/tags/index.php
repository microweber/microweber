<?php
$cont_id = false;

if (isset($params['content_id'])) {
    $cont_id = $params['content_id'];
} elseif (isset($params['content-id'])) {
    $cont_id = $params['content-id'];
}
$content_tags = false;
$tags_url_base = content_link(MAIN_PAGE_ID);

if ($cont_id) {
    $tags_url_base = content_link($cont_id);

    $content_tags = content_tags($cont_id);

} else {
    $content_tags = content_tags();

}
?>
<?php if ($content_tags == true): ?>
    <?php

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
    }
    ?>
<?php else: ?>
    <?php print lnotif("No tags found."); ?>
<?php endif; ?>
