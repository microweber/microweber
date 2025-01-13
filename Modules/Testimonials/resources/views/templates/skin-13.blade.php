<?php

/*

type: layout

name: Skin-13

description: Skin-13

*/

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

<?php

$rand = uniqid();
$limit = 40;

?>


<div id="js-testimonials-slider-<?php echo $params['id']; ?>" class="slider_v2-default swiper">
    <div class="swiper-wrapper pb-4">
        <?php foreach ($testimonials as $item): ?>
        <div class="swiper-slide">
            <div class="row text-center text-lg-start d-md-flex justify-content-center justify-content-lg-between align-items-center">
                <div class="col-12 col-sm-8 col-lg-7 col-lg-6">

                    <h4> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</h4>
                </div>

                <div class="col-12 col-sm-8 col-lg-5 text-center">
                    <?php if ($item['client_image']): ?>
                        <div class="w-250 mx-auto mb-4">
                            <div class="img-as-background rounded-circle square">
                                <img loading="lazy" src="<?php print thumbnail($item['client_image'], 250); ?>">
                            </div>
                        </div>
                    <?php endif; ?>

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
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div id="js-testimonials-slider-pagination-<?php echo $params['id']; ?>" class="swiper-pagination mt-4"></div>

</div>
