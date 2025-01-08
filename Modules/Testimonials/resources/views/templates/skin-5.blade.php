<?php

/*

type: layout

name: Skin-5

description: Skin-5

*/

?>

<?php

$rand = uniqid();
$limit = 40;

?>


<script>
    mw.require('<?php print modules_url() ?>slider_v2/slider-v2.js');
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
            <div class="row text-center">
                <div class="col-12 col-lg-10 col-lg-8 mx-auto">
                    <h5> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</h5>
                    <br/>
                    <br/>
                    <div class="text-center text-sm-start mb-5">
                        <div class="d-block d-sm-flex align-items-center mx-auto justify-content-center">
                            <?php if ($item['client_image']): ?>
                                <div class="me-sm-4 mb-5 mb-sm-0 mx-auto mx-sm-0">
                                    <div class="w-80 mx-auto">
                                        <div class="img-as-background rounded-circle square">
                                            <img loading="lazy" src="<?php print thumbnail($item['client_image'], 120); ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div>
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
        <?php endforeach; ?>
    </div>
    <div id="js-testimonials-slider-pagination-<?php echo $params['id']; ?>" class="swiper-pagination"></div>
    <div id="js-testimonials-pagination-previous-<?php echo $params['id']; ?>" class="mw-slider-v2-buttons-slide mw-slider-v2-button-prev"></div>
    <div id="js-testimonials-pagination-next-<?php echo $params['id']; ?>" class="mw-slider-v2-buttons-slide mw-slider-v2-button-next"></div>

</div>
