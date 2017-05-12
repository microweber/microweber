<script>
    mw.lib.require('bootstrap3ns');

    $(document).ready(function () {
        var items = mw.$(".item.enabled-true", '#<?php print $params['id'] ?>');
        items.hover(function () {
            mw.$('.enabled-true.active', '#<?php print $params['id'] ?>').removeClass('active');
            $(this).addClass('active');
        });
    });
</script>

<div class="bootstrap3ns">
    <?php
    if (get_option('columns', $params['id'])) {
        $columns = get_option('columns', $params['id']);
    } else {
        $columns = 1;
    }

    if (get_option('feature', $params['id'])) {
        $feature = get_option('feature', $params['id']);
    } else {
        $feature = 1;
    }

    if (get_option('style_color', $params['id']) == 'bg-primary') {
        $styleColor = 'bg-primary';
        $buttonColor = 'btn-primary';
    } elseif (get_option('style_color', $params['id']) == 'bg-success') {
        $styleColor = 'bg-success';
        $buttonColor = 'btn-success';
    } elseif (get_option('style_color', $params['id']) == 'bg-info') {
        $styleColor = 'bg-info';
        $buttonColor = 'btn-info';
    } elseif (get_option('style_color', $params['id']) == 'bg-warning') {
        $styleColor = 'bg-warning';
        $buttonColor = 'btn-warning';
    } elseif (get_option('style_color', $params['id']) == 'bg-danger') {
        $styleColor = 'bg-danger';
        $buttonColor = 'btn-danger';
    } else {
        $styleColor = 'bg-default';
        $buttonColor = 'btn-default';
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
