<div class="img-holder" style="background-image: url('<?php print thumbnail($slide['images'][0], 1440, 656); ?>');">
    <div class="info-holder left">
        <h3><?php echo $slide['primaryText']; ?></h3>
        <p><?php echo $slide['secondaryText']; ?></p>
    </div>

    <div class="additional-img right">
        <a href="<?php if (isset($slide['url'])) {
            print $slide['url'];
        } else { ?>javascript:;<?php } ?>">
            <?php if (isset($slide['images'][1])): ?>
                <img src="<?php print thumbnail($slide['images'][1], 910, 600); ?>"/>
            <?php endif; ?>
        </a>
    </div>
</div>