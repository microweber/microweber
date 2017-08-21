<?php

/*

  type: layout

  name: Four slides

  description: Four slides template for bxSlider


*/

?>
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>/templates/four-slides/style.css"/>

<?php

if (isset($params['pager_custom'])) {
    $pagerCustom = $params['pager_custom'];
} else {
    $pagerCustom = '';
}
?>


<div class="template-four-slides">
    <div class="slider-images">
        <ul class="bxSlider">
            <?php foreach ($data as $slide) { ?>
                <li>
                    <?php if (isset($slide['skin_file'])) { ?>
                        <?php include $slide['skin_file'] ?>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    </div>

    <?php if (isset($pagerCustom) and $pagerCustom != ''): ?>
        <div class="slider-pagers">
            <div class="<?php print $pagerCustom; ?>">
                <?php //d($data); ?>
                <?php foreach ($data as $key => $slide) { ?>
                    <a data-slide-index="<?php print $key ?>" href="javascript:;" class="<?php if ($key == 0): ?>active<?php endif; ?>">
                        <span><?php print $slide['primaryText']; ?></span>
                        <p><?php print $slide['secondaryText']; ?></p>
                    </a>
                <?php } ?>
            </div>
        </div>
    <?php endif; ?>
</div>

