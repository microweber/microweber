<div class="skin-default">
    <div class="bxslider-wrapper">
        <div class="info-box-fluid">
            <div class="middle-content">
                <h1 class="bxSlider-title">
                    <?php if (isset($slide['icon'])) { ?>
                        <?php print $slide['icon']; ?>
                    <?php } ?>
                    <?php if (isset($slide['primaryText'])) { ?>
                        <?php print $slide['primaryText']; ?>
                    <?php } ?>
                </h1>

                <p class="bxSlider-desc">
                    <?php if (isset($slide['secondaryText'])) { ?>
                        <?php print $slide['secondaryText']; ?>
                    <?php } ?>
                </p>

                <?php if ($slide['seemoreText']): ?>
                    <div class="button">
                        <a class="btn btn-primary" href="<?php if (isset($slide['url'])) {
                            print $slide['url'];
                        } ?>"><?php print $slide['seemoreText'] ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bxslider" style="<?php if (isset($slide['images'][0])) { ?>background-image:url(<?php print thumbnail($slide['images'][0], $thumb_quality_x, $thumb_quality_y); ?>);<?php } ?>"></div>
    </div>
</div>
