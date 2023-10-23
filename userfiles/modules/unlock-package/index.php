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
?>

<script>
    $(document).ready(function() {
        document.getElementById('js-unlock-package-save-license-<?php echo $params['id'];?>').addEventListener('click', function() {
            var licenseKey = document.getElementById('js-unlock-package-license-key-<?php echo $params['id'];?>').value;
            mw.top().app.license.save(licenseKey).then(function (response) {
                if (response.data.success) {
                    mw.notification.success('License key saved');
                    mw.top().admin.admin_package_manager.install_composer_package_by_package_name('microweber-templates/big', 'latest');
                } else if (response.data.warning) {
                    mw.notification.warning(response.data.warning);
                } else {
                    mw.notification.warning('Invalid license key');
                }
            });
        });

    });
</script>

<?php
if ($template_file != false and is_file($template_file)) {
    include($template_file);
}
?>
