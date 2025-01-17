<?php

/*

type: layout

name: Skin for sliding Logos

description: Skin for sliding Logos

*/

?>
<?php $rand = uniqid(); ?>

<?php

$defaultImageWidth = '100'; // Default width in pixels
$imageWidth = get_option('imageWidth', $params['id']);
if ($imageWidth == false) {
    $imageWidth = $defaultImageWidth;
}


?>


<style>
    <?php
        $gallery_id = 'gallery-' . $rand;
        $selector_prefix = '#' . $gallery_id . ' ';
    ?>

    <?php print $selector_prefix ?>
    {
        --items-per-page: 4
    ;
    }

    <?php print $selector_prefix ?>
    {
        width: 100%
    ;
        overflow: hidden
    ;
        position: relative
    ;
    }

    <?php print $selector_prefix ?>.lg-carousel-container {
        white-space: nowrap;
    }

    <?php print $selector_prefix ?>.lg-carousel-item {
        display: inline-block;
        width: <?php print $imageWidth ?>px;
        padding: 20px;
        text-align: center;

    }
</style>


<?php if (is_array($data)): ?>

    <?php $size = sizeof($data); ?>

    <div class="lg-carousel" id="<?php print $gallery_id; ?>" role="region">
        <div class="lg-carousel-container" id="<?php print $gallery_id; ?>container" role="list">
            <?php if ($size > 1) { ?>
            <?php foreach ($data as $item): ?>

                <?php
                $itemTitle = false;
                $itemDescription = false;
                $itemLink = false;
                $itemAltText = 'Open';
                if (isset($item['image_options']) and is_array($item['image_options'])) {
                    if (isset($item['image_options']['title'])) {
                        $itemTitle = $item['image_options']['title'];
                    }
                    if (isset($item['image_options']['caption'])) {
                        $itemDescription = $item['image_options']['caption'];
                    }
                    if (isset($item['image_options']['link'])) {
                        $itemLink = $item['image_options']['link'];
                    }
                    if (isset($item['image_options']['alt-text'])) {
                        $itemAltText = $item['image_options']['alt-text'];
                    }
                }
                ?>
                <a class="lg-carousel-item" role="listitem" href="<?php print $itemLink; ?>">
                    <img src="<?php print thumbnail($item['filename'], 800, 800); ?>"
                         alt="<?php print $item['title'] ?>" title="<?php print $item['title'] ?>"  >
                </a>

            <?php endforeach; ?>

            <?php foreach ($data as $item): ?>

                <?php
                $itemTitle = false;
                $itemDescription = false;
                $itemLink = false;
                $itemAltText = 'Open';
                if (isset($item['image_options']) and is_array($item['image_options'])) {
                    if (isset($item['image_options']['title'])) {
                        $itemTitle = $item['image_options']['title'];
                    }
                    if (isset($item['image_options']['caption'])) {
                        $itemDescription = $item['image_options']['caption'];
                    }
                    if (isset($item['image_options']['link'])) {
                        $itemLink = $item['image_options']['link'];
                    }
                    if (isset($item['image_options']['alt-text'])) {
                        $itemAltText = $item['image_options']['alt-text'];
                    }
                }
                ?>
                <a class="lg-carousel-item" role="listitem" href="<?php print $itemLink; ?>">
                    <img src="<?php print thumbnail($item['filename'], 800, 800); ?>"
                         alt="<?php print $item['title'] ?>" title="<?php print $item['title'] ?>"  >
                </a>

            <?php endforeach; ?>

        </div>
        <div class="mw-new-9-gradient-scrim"></div>
    </div>


    <script>

        ;(function (containerId) {

            const carouselContainer = document.getElementById(containerId);

            const carouselItems = carouselContainer.innerHTML;
            carouselContainer.innerHTML += carouselItems;

            let scrollLeft = 0;
            const scrollSpeed = 7;

            function animateCarousel(timestamp) {
                if (!lastTimestamp) {
                    lastTimestamp = timestamp;
                }

                const deltaTime = timestamp - lastTimestamp;
                lastTimestamp = timestamp;

                scrollLeft += scrollSpeed * deltaTime / 60;
                if (scrollLeft >= carouselContainer.scrollWidth / 2) {
                    scrollLeft = 0;
                }
                carouselContainer.style.transform = `translateX(-${scrollLeft}px)`;

                requestAnimationFrame(animateCarousel);
            }

            let lastTimestamp = null;
            requestAnimationFrame(animateCarousel);

        })('<?php print $gallery_id; ?>container');


    </script>

<?php } ?>


<?php endif; ?>



