<?php

/*

type: layout

name: Skin-3 beauty

description: Skin-3 beauty

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
            <div class="col-holder col-md-6">
                <?php foreach ($data as $count => $item): ?>
                    <?php if ($count == 0): ?>
                        <div class="item pictures picture-<?php print $item['id']; ?>" onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $count; ?>);return false;">
                            <img   src="<?php print thumbnail($item['filename'], 1400, 1400, true); ?>" alt="">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="col-holder col-md-6">
                <?php foreach ($data as $count => $item): ?>
                    <?php if ($count == 1 OR $count == 2): ?>
                        <div class="item pictures picture-<?php print $item['id']; ?>" onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $count; ?>);return false;">
                            <img   src="<?php print thumbnail($item['filename'], 1400, 695, true); ?>" alt="">
                        </div>
                    <?php endif; ?>
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
