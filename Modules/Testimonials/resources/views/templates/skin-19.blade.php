<?php

/*

type: layout

name: Skin-19

description: Skin-19

*/

?>

<?php

$rand = uniqid();
$limit = 40;

?>


<script>mw.lib.require('slick')</script>
<script>
    $(document).ready(function () {
        $('.slickslider', '#<?php echo $params['id']; ?>').slick();
    });
</script>

<style>
    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-dots {
        position: relative;
        bottom: 0px;
    }
    .ziza-slick-testimonials .slick-slider .slick-dots li.slick-active button {
        background-color: #2639ED!important;
    }

    .ziza-slick-testimonials .slick-slider .slick-dots li button {
        background-color: #E7F0FC!important;
        height: 20px;
        width: 20px;
    }

    .ziza-slick-testimonials .slick-list {
        overflow: hidden;
    }


    @media screen and (min-width: 992px) {
    <?php echo '#' . $params['id'] . ' '; ?>
        .slick-arrow.slick-prev {
            left: -60px;
        }

    <?php echo '#' . $params['id'] . ' '; ?>
        .slick-arrow.slick-next {
            right: -60px;
        }
    }



    .mw-ziza-testimonials-item-eclipse {
        position: absolute;
        bottom: 30px;
        left: -20px;
        z-index: 0;
        height: 60px;
        width: 120px;
        overflow: hidden;
        transform: rotate(48deg);

    }

    .mw-ziza-testimonials-item-eclipse div {
        position: absolute;
        height: 120px;
        width: 120px;
        bottom: 0;
        left: 0;
        border: 8.8px solid #FF007A;
        border-radius: 100px;
        z-index: 1;
    }

    .mw-ziza-testimonials-item-dots {
        position: absolute;
        top: -49px;
        right: -40px;
    }

    /*.ziza-slick-testimonials .slick-list {*/
    /*    overflow: unset;*/
    /*}*/

    .ziza-testimonials-image {
        width: 450px;
        height: 450px;
        z-index: 1;
        position: relative;
        border-radius: 250px 0 250px 250px;
        left: 20px;
    }

</style>

<div class="slick-arrows-1 ziza-slick-testimonials mt-5">
    <div class="slickslider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "dots": true, "arrows": false}'>
        <?php foreach ($testimonials as $item): ?>
            <div class="row text-center text-lg-start d-flex align-items-center pt-7">

                <?php if ($item['client_image']): ?>
                    <div class="text-center mx-auto ziza-testimonials-image background-image-holder" style="background-image: url(<?php print thumbnail($item['client_image'], 450); ?>);">

                       <div>
                           <div class="mw-ziza-testimonials-item-eclipse">
                               <div></div>
                           </div>


                           <img loading="lazy" class="mw-ziza-testimonials-item-dots" src="<?php print asset('templates/big2/assets/img/layouts/ziza/ziza-dots-94.png'); ?>"/>
                       </div>
                    </div>
                <?php endif; ?>


                <div class="col-xl-6 col-md-10 col-12 me-xl-auto mx-auto text-xl-start text-center ">
                    <h4 class="mb-1"><?php print $item['name']; ?></h4>
                    <p><?php print $item['client_role']; ?></p> &nbsp;
                    <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
</div>
