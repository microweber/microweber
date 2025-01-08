<?php

/*

type: layout

name: Skin-20

description: Skin-20

*/

?>

<script>mw.lib.require('slick')</script>
<script>
    $(document).ready(function () {
        $('.slickslider', '#<?php echo $params['id']; ?>').slick();
    });
</script>

<?php

$rand = uniqid();
$limit = 40;

?>

<style>
    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-track {
        display: flex !important;
    }

    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-dots {
        position: relative;
        bottom: -30px;
    }

    @media screen and (min-width: 768px) {
    <?php echo '#' . $params['id'] . ' '; ?>
        .avatar-holder {
            margin-left: -40% !important;
        }
    }

    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-slide {
        height: inherit !important;
    }


    <?php echo '#' . $params['id'] . ' '; ?>
        .slick-arrow.slick-prev {
            bottom: -104px;
            top: unset;
            left: 72px !important;
        }


    <?php echo '#' . $params['id'] . ' '; ?>
        .slick-arrow.slick-next {
            bottom: -104px;
            top: unset;
            left: 70px;
        }



    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-arrows-1 .slick-arrow:before {
        margin-bottom: 2px!important;
        opacity: 1;

    }

    <?php echo '#' . $params['id'] . ' '; ?>

    .action-blog-quote {
        right: -13px;
        top: 211px;

</style>

<div class="slick-arrows-1">
    <div class="slickslider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "dots": false, "arrows": true}'>
        <?php foreach ($testimonials as $item): ?>
            <div class="row">
                <div class=" d-flex justify-content-between flex-wrap mx-auto">
                   <div class="col-lg-6 col-11 pe-3 position-relative">
                       <?php if ($item['client_picture']): ?>
                           <div class="img-as-background h-500">
                               <img loading="lazy" src="<?php print thumbnail($item['client_picture'], 800); ?>" class="position-relative"/>

                           </div>
                           <img loading="lazy" src="<?php print asset('templates/big2/assets/img/layouts/action/action-blog-quote.png'); ?>" class="position-absolute action-blog-quote"/>

                       <?php endif; ?>
                   </div>

                    <div class="col-lg-4 col-12 mx-auto ps-3 mt-lg-0 mt-5">

                        <p class="py-3"> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

                        <img loading="lazy" src="<?php print asset('templates/big2/assets/img/layouts/action/action-blog-stars.png'); ?>" class="mb-4"/>

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
</div>
