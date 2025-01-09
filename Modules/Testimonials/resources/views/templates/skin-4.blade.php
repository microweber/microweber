<?php

/*

type: layout

name: Skin-4

description: Skin-4

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
    <div class="swiper-wrapper">
        <?php foreach ($testimonials as $item): ?>
        <div class="swiper-slide">
            <div class="row">
                <div class="col-12 col-lg-12 col-lg-10 mx-auto">
                    <i class="mdi mdi-format-quote-close icon-size-46px   d-block mb-5"></i>
                    <div class="px-5">
                        <h4> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</h4>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-12 col-lg-12 col-lg-11 mx-auto">
                            <div class="row">
                                <div class="col-6 text-start">

                                </div>
                                <div class="col-6 text-end text-right">
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
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div id="js-testimonials-slider-pagination-<?php echo $params['id']; ?>" class="swiper-pagination me-auto text-start"></div>

</div>
