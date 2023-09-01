
<div class="bootstrap3ns">
    <?php
    if (get_option('fb-page', $params['id'])) {
        $fbPage = get_option('fb-page', $params['id']);
    } else {
        $fbPage = 'https://www.facebook.com/Microweber/';
    }

    if (get_option('width', $params['id'])) {
        $width = get_option('width', $params['id']);
    } else if (isset($params['width'])) {
        $width = $params['width'];
    } else {
        $width = '380';
    }

    if (get_option('height', $params['id'])) {
        $height = get_option('height', $params['id']);
    } else {
        $height = '300';
    }

    if (get_option('friends', $params['id'])) {
        if (get_option('friends', $params['id']) == 1) {
            $friends = 'true';
        } else {
            $friends = 'false';
        }
    } else {
        $friends = 'false';
    }

    if (get_option('timeline', $params['id'])) {
        if (get_option('timeline', $params['id']) == 1) {
            $timeline = '&tabs=timeline';
        } else {
            $timeline = '';
        }
    } else {
        $timeline = '';
    }

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
</div>
