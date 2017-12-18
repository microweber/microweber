<div class="background-image-holder" style="background-image: url('<?php print $slide['images'][0]; ?>');"></div>

<div class="container pos-vertical-center">
    <div class="row">
        <div class="col-sm-12 text-center">
            <h1><?php print $slide['primaryText']; ?></h1>
            <p class="lead"><?php print $slide['secondaryText']; ?></p>
            <?php if (isset($slide['url']) AND $slide['url'] != ''): ?>
                <a class="btn btn--primary" href="<?php print $slide['url']; ?>"><span class="btn__text"><?php print $slide['seemoreText']; ?></span></a>
            <?php endif; ?>
        </div>
    </div>
</div>


