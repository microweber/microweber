<?php

/*

type: layout

name: Skin-12

description: Skin-12

*/

?>
<style>
    <?php print '#'.$params['id']; ?>
    .gallery-holder .col-holder {
        padding-right: 4px;
        padding-left: 4px;
    }

    <?php print '#'.$params['id']; ?>
    .gallery-holder .row {
        margin-right: -4px;
        margin-left: -4px;
    }

    <?php print '#'.$params['id']; ?>
    .gallery-holder .item {
        margin-bottom: 8px;
    }
</style>
<?php if (is_array($data)): ?>
    <?php $rand = uniqid(); ?>
    <div class="gallery-holder">
        <div class="row">
            <?php $count = -1; ?>
                <?php foreach ($data as $item): ?>
                    <?php $count++; ?>
                    <?php if ($count == 0 || $count == 5): ?>
                        <div class="col-holder col-8">
                            <div class="item pictures picture-<?php print $item['id']; ?>" onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $count; ?>);return false;">
                                <img   class="w-100" src="<?php print thumbnail($item['filename'], 800, 800, true); ?>" alt="">
                            </div>
                        </div>
                     <?php else : ?>
                     <?php  if ($count == 1 || $count == 3 ):  ?>  <div class="col-holder col-4"> <?php endif; ?>

                            <div class="item pictures picture-<?php print $item['id']; ?>" onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $count; ?>);return false;">
                                <img   class="w-100" src="<?php print thumbnail($item['filename'], 500, 500, true); ?>" alt="">
                            </div>


                    <?php  if ($count == 2 || $count == 4):  ?> </div>  <?php endif; ?>

                    <?php endif; ?>
                    <?php if ($count == 5):
                        $count = -1;
                    endif; ?>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
    <script>
        gallery<?php print $rand; ?> = [
                <?php foreach($data  as $item): ?>{image: "<?php print thumbnail($item['filename'], 1200); ?>", description: "<?php print $item['title']; ?>"},
            <?php endforeach;  ?>
        ];
    </script>
<?php endif; ?>
