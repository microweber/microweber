<?php
$settings = get_module_option('settings', $params['id']);
if (empty($settings)) {

    $mdsApplier = new \MicroweberPackages\Module\ModuleDefaultSettingsApplier();
    $mdsApplier->moduleName = 'slider_v2';
    $mdsApplier->modulePath = __DIR__;
    $mdsApplier->moduleId = $params['id'];

    $applied = $mdsApplier->apply();

    if (isset($applied['success']) && $applied['success']) {
        $settings = get_module_option('settings', $params['id']);
    }

}

$slides = json_decode($settings, true);

$moduleTemplate = get_module_option('template', $params['id']);
if ($moduleTemplate == false and isset($params['template'])) {
    $moduleTemplate = $params['template'];
}
if ($moduleTemplate != false) {
    $templateFile = module_templates($config['module'], $moduleTemplate);
} else {
    $templateFile = module_templates($config['module'], 'default');
}
?>


<?php
if (is_file($templateFile)) {
    include($templateFile);
} else {
    print lnotif("No template found. Please choose template.");
    return;
}

?>

<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<script src="//cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    $(document).ready(function () {
        new Swiper('#js-slider-<?php echo $params['id']; ?>', {
            loop: true,
            // If we need pagination
            pagination: {
                el: '#js-slide-pagination-<?php echo $params['id']; ?>',
            },
            // Navigation arrows
            navigation: {
                nextEl: '#js-slide-pagination-next-<?php echo $params['id']; ?>',
                prevEl: '#js-slide-pagination-previous-<?php echo $params['id']; ?>',
            },
        });
    });
</script>

