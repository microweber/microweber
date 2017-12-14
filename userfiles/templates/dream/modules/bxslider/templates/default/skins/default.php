<div class="caption-slider-default">
    <div class="display-table">
        <div class="display-table-cell vertical-align-middle">
            <div class="caption-container text-left">
                <h2><?php print $slide['primaryText']; ?></h2>
                <p>
                    <?php print $slide['secondaryText']; ?>
                </p>
                <?php if (isset($slide['url']) AND $slide['url'] != ''): ?>
                    <br />
                    <br />
                    <a href="<?php print $slide['url']; ?>" class="btn btn-primary"><?php print $slide['seemoreText']; ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<img class="img-responsive radius-4" src="<?php print $slide['images'][0]; ?>" width="851" height="335" alt="">
