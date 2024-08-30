<?php
$template_file_from_theme = template_dir() . 'modules/unlock-package/templates/default.php';

$template_file = module_templates($config['module'], 'default');
if (is_file($template_file_from_theme)) {
    $template_file = $template_file_from_theme;
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
