<?php

/*

type: layout

name: Skin-2

description: Skin-2

*/

?>

<?php

$rand = uniqid();
$limit = 40;

?>




<script>

    <?php print get_asset('/Modules/Slider/resources/assets/js/slider-v2.js'); ?>

</script>
<script>



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


<div id="js-testimonials-slider-<?php echo $params['id']; ?>" class="slider_v2-default swiper">
     <div class="swiper-wrapper">
         @if($testimonials->isEmpty())
             <p>No testimonials available.</p>
         @else
            <?php foreach ($testimonials as $item): ?>
         <div class="swiper-slide">
            <div class="row text-center">
                <div class="col-12 col-lg-10 col-lg-8 mx-auto my-2">

                    <div class="img-as-background mx-auto my-3 rounded-circle" style="width:125px; height: 125px;">

                        <?php if ($item['client_image']): ?>
                            <img loading="lazy" src="<?php print thumbnail($item['client_image'], 750); ?>" class="d-inline"/>
                        <?php endif; ?>
                    </div>

                    <?php if ($item['name']): ?>
                        <h6 class=" mb-0"><?php print $item['name']; ?></h6>
                    <?php endif; ?>

                    <p class="col-md-8 mx-auto"> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

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
        @endif

    </div>
     <div id="js-testimonials-pagination-previous-<?php echo $params['id']; ?>" class="mw-slider-v2-buttons-slide mw-slider-v2-button-prev"></div>
     <div id="js-testimonials-pagination-next-<?php echo $params['id']; ?>" class="mw-slider-v2-buttons-slide mw-slider-v2-button-next"></div>
</div>
