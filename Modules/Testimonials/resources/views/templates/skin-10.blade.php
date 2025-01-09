<?php

/*

type: layout

name: Skin-10

description: Skin-10

*/

?>

<?php

$rand = uniqid();
$limit = 40;

?>


<script>
    <?php print get_asset('/Modules/Slider/resources/assets/js/slider-v2.js'); ?>

    $(document).ready(function () {
        new SliderV2('#js-testimonials-slider-<?php echo $params['id']; ?>', {
            loop: true,
            autoplay:true,
            direction: 'horizontal', //horizontal or vertical
            pagination: {
                element: '#js-testimonials-slider-pagination-<?php echo $params['id']; ?>',
            },
            navigation: {
                nextElement: '#js-testimonials-pagination-next-<?php echo $params['id']; ?>',
                previousElement: '#js-testimonials-pagination-previous-<?php echo $params['id']; ?>',},
        });
    });
</script>
<style>
    .slider_v2-default.swiper .swiper-pagination-bullet {
        background-color: #000;
    }
</style>


<div id="js-testimonials-slider-<?php echo $params['id']; ?>" class="slider_v2-default swiper">
    <div class="swiper-wrapper pb-4">
        <?php foreach ($testimonials as $item): ?>
        <div class="swiper-slide">
            <div class="row text-center">
                <div class="col-12 col-lg-10 col-lg-8 mx-auto my-2">
                    <?php if ($item['client_image']): ?>
                        <div class="w-125 mx-auto my-4">
                            <div class="img-as-background rounded-circle square">
                                 <img loading="lazy" src="<?php print thumbnail($item['client_image'], 120); ?>" class="d-block"/>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="my-3">
                        <?php if ($item['name']): ?>
                            <h5 class=" mb-0"><?php print $item['name']; ?></h5>
                        <?php endif; ?>
                        <?php if ($item['client_company']): ?>
                            <p class="mb-0"><?php print $item['client_company']; ?></p>
                        <?php endif; ?>

                        <?php if ($item['client_website']): ?>
                            <a class="my-1 d-block" href="<?php print $item['client_website']; ?>"><?php print $item['client_website'] ?></a>
                        <?php endif; ?>

                        <?php if ($item['client_role']): ?>
                            <p><?php print $item['client_role']; ?></p>
                        <?php endif; ?>

                    </div>

                    <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div id="js-testimonials-slider-pagination-<?php echo $params['id']; ?>" class="swiper-pagination mt-4"></div>

</div>
