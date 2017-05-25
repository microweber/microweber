<script>
    mw.lib.require('bootstrap3ns');
</script>
<script>mw.require('https://fonts.googleapis.com/icon?family=Material+Icons&.css', 'material_icons');</script>
<script>mw.moduleCSS("<?php print modules_url(); ?>layouts/styles.css"); </script>

<div class="bootstrap3ns">
    <div class="edit" rel="layout-<?php print $params['id'] ?>" field="layout">
        <?php
        $template = get_option('data-template', $params['id']);
        if ($template == false and isset($params['template'])) {
            $template = $params['template'];
        }

        $template_file = false;
        if ($template != false and strtolower($template) != 'none') {
            $template_file = module_templates($config['module'], $template);
        }
        if ($template_file == false) {
            $template_file = module_templates($config['module'], 'default');
        }
        if ($template_file != false and is_file($template_file)) {
            include($template_file);
        }
        ?>
    </div>
</div>
