<?php

/*

type: layout

name: Skin-11

description: Skin-11

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

    @media screen and (min-width: 768px) {
    <?php echo '#' . $params['id'] . ' '; ?>
        .avatar-holder {
            margin-left: -40% !important;
        }
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
                <div class="row">
                    <div class="col-12 col-md-9 offset-md-3 col-lg-7 offset-lg-4">
                        <div class="border testimonials-background-variable testimonialBorderVariable hover-border-color-primary   p-md-5">
                            <div class="d-block d-md-flex text-center text-md-start">
                                <?php if ($item['client_image']): ?>
                                    <div class="mw-300 w-300 avatar-holder mx-auto me-md-5">
                                        <div class="img-as-background   h-300">
                                            <img loading="lazy" src="<?php print thumbnail($item['client_image'], 300); ?>" class="d-block"/>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

                                    <div class="row d-flex align-items-center justify-content-between mt-3">
                                        <div class="col">
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
            </div>
        <?php endforeach; ?>
    </div>
    <div id="js-testimonials-slider-pagination-<?php echo $params['id']; ?>" class="swiper-pagination mt-4"></div>


</div>
