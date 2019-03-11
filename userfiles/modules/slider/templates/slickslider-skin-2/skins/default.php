<div class="">
    <?php if (isset($slide['url']) AND $slide['url']): ?>
        <a href="<?php print $slide['url']; ?>">
            <img src="<?php print $slide['images'][0]; ?>" alt=""/>
        </a>
    <?php else: ?>
        <img src="<?php print $slide['images'][0]; ?>" alt=""/>
    <?php endif; ?>
</div>