<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>css/parallax.css"/>

<script>
    mw.lib.require('bootstrap3ns');
</script>

<div class="bootstrap3ns">
    <?php
    if (get_option('parallax', $params['id'])) {
        $parallax = get_option('parallax', $params['id']);
    } else {
        $parallax = $config['url_to_module'] . 'images/parallax-default.jpg';
    }

    if (get_option('text', $params['id'])) {
        $infoBox = get_option('text', $params['id']);
    } else {
        $infoBox = _e('This text is set by default and is suitable for edit in real time. By default the drag and drop core feature will allow you to position it anywhere on the site. Get creative, Make Web.', true);
    }

    if (get_option('info-image', $params['id'])) {
        $infoImage = get_option('info-image', $params['id']);
    } else {
        $infoImage = $config['url_to_module'] . 'images/infoImage.jpg';
    }

    if (get_option('height', $params['id'])) {
        $height = get_option('height', $params['id']);
    } else {
        $height = 550;
    }

    if (get_option('alpha', $params['id'])) {
        $alpha = get_option('alpha', $params['id']);
    } else {
        $alpha = '0.6';
    }


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
</div>
