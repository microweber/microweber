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
$slidesIndexes = [];
$currentSlide = 0;

if (!empty($slides)) {
    foreach ($slides as $iSlide => $slide) {
        $slidesIndexes[$slide['itemId']] = $iSlide;
    }
 //   $slidesOrderedByDate = collect($slides)->sortBy('updatedAt')->reverse()->toArray();
  //  $currentSlide = key($slidesOrderedByDate);
}

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

<script>
    mw.require('<?php print $config['url_to_module']; ?>slider-v2.js');
    $(document).ready(function () {

        console.log(typeof(window.sliderV2<?php echo md5($params['id']); ?>_initialSlide))

        if(typeof 'sliderV2<?php echo md5($params['id']); ?>_initialSlide' === 'undefined'){
            window.sliderV2<?php echo md5($params['id']); ?>_initialSlide = <?php echo $currentSlide; ?>;

        }
console.log(window.sliderV2<?php echo md5($params['id']); ?>_initialSlide = <?php echo $currentSlide; ?>)
       window.sliderV2<?php echo md5($params['id']); ?> = null;
       window.sliderV2<?php echo md5($params['id']); ?> = new SliderV2('#js-slider-<?php echo $params['id']; ?>', {
            loop: true,
            pagination: {
                element: '#js-slide-pagination-<?php echo $params['id']; ?>',
            },
            navigation: {
                nextElement: '#js-slide-pagination-next-<?php echo $params['id']; ?>',
                previousElement: '#js-slide-pagination-previous-<?php echo $params['id']; ?>',
            },
            slidesIndexes: <?php echo json_encode($slidesIndexes); ?>,
            initialSlide: window.sliderV2<?php echo md5($params['id']); ?>_initialSlide,
        });



    });
</script>

