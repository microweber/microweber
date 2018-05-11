<?php

/*

type: layout

name: Owl Carousel - 6 Brands per slide

description: Skin 1

*/

?>
    <style>
        <?php print '#'.$params['id']; ?>
        .owl-carousel .owl-item img {
            max-height: 100px;
        }

        <?php print '#'.$params['id']; ?>
        .owl-carousel .owl-item {
            text-align: center;
            padding: 10px !important;
        }
    </style>
<?php if (is_array($data)): ?>
    <?php $rand = uniqid(); ?>
    <div class="slider" data-items="6" data-timing="3000">
        <ul class="slides">
            <?php $count = -1;
            foreach ($data as $item): ?>
                <?php $count++; ?>
                <li class="pictures picture-<?php print $item['id']; ?>" onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $count; ?>);return false;">
                    <img src="<?php print thumbnail($item['filename'], 993, 300); ?>" alt="">
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <script>
        gallery<?php print $rand; ?> = [
                <?php foreach($data  as $item): ?>{image: "<?php print ($item['filename']); ?>", description: "<?php print $item['title']; ?>"},
            <?php endforeach;  ?>
        ];
        $(document).ready(function () {
            mr.sliders.documentReady($)
        })
    </script>
<?php endif; ?>