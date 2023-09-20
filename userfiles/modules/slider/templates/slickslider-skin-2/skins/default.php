<div class="text-center">
    <?php if (isset($slide['url']) AND $slide['url']): ?>
        <a href="<?php print $slide['url']; ?>">
            <img src="<?php print thumbnail($slide['images'][0], $thumb_quality_x, $thumb_quality_y); ?>" alt=""/>
        </a>
    <?php else: ?>
        <img src="<?php print thumbnail($slide['images'][0], $thumb_quality_x, $thumb_quality_y); ?>" alt=""/>
    <?php endif; ?>
</div>