<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>css/pricing-table.css"/>

<script>
    mw.lib.require('bootstrap3ns');

    $(document).ready(function () {

        var items = mw.$(".item.enabled-true", '#<?php print $params['id'] ?>');
        items.hover(function () {
            mw.$('.enabled-true.active', '#<?php print $params['id'] ?>').removeClass('active');
            $(this).addClass('active');
        });
        items.length === 1 ? items.addClass('active') : items.eq(1).addClass('active');
    });
</script>

<div class="bootstrap3ns">
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
</div>
