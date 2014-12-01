<?php
/*
type: layout
name: Header Slider
description: Pictures Header slider
*/
?>


<?php if (is_array($data)): ?>
    <?php $id = "slider-" . uniqid(); ?>

    <div class="header-slider" id="<?php print $id; ?>">
            <div class="header-slider-holder">
                <?php $count = 0; foreach ($data as $item): $count ++;?>
                    <div class="header-slider-item header-slider-item-<?php print $item['id']; ?><?php if( $count == 1 ){ print ' active'; } ?>">
                        <div class="bgimage" style="background-image: url(<?php print $item['filename']; ?>);"></div>
                        <?php if ($item['title'] != '') { ?><i class="header-slider-description"><i class="header-slider-description-content"><?php print $item['title']; ?></i></i><?php } ?>
                    </div>
                <?php endforeach; ?>
                <?php if($count > 1){ ?>
                    <span class="mw-icon-arrowright header-slider-next"></span>
                    <span class="mw-icon-arrowleft header-slider-previous"></span>
                <?php } ?>
            </div>
        </div>

<?php else : ?>
<?php endif; ?>