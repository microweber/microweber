<?php
/*

type: layout

name: Skin-2

description: Skin-2

*/
?>

<script>mw.lib.require('slick');</script>
<script>
    $(document).ready(function () {
        $('.slickslider', '#<?php echo $params['id']; ?>').slick();
    });
</script>

<style>
    <?php echo '#'.$params['id']; ?>
    .slick-dots {
        position: relative;
        height: 100%;
        display: flex;
        flex-flow: column;
        bottom: 0;
    }

    @media screen and (max-width: 991px) {
    <?php echo '#'.$params['id']; ?>
        .slick-dots {
            display: block;
        }
    }
</style>

<div class="row text-center text-md-start d-flex align-items-center justify-content-center justify-content-lg-between">
    <div class="col-sm-10 col-md-12 col-lg-10 col-lg-10">
        <?php if (isset($data) and $data): ?>
            <div class="slickslider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "dots": true, "appendDots": ".slick-paging", "vertical" : true, "verticalSwiping" : true, "arrows": false}'>
                <?php foreach ($data as $key => $slide): ?>
                    <div>
                        <div class="row d-flex align-items-center justify-content-center justify-content-lg-between">
                            <div class="col-sm-12 col-md-6 mb-5 mb-md-0">
                                <div class="w-250 mx-auto">
                                    <div class="img-as-background   square">
                                        <img src="<?php print thumbnail($slide['file'], 850); ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div>
                                    <h1 class="mb-1"><?php print array_get($slide, 'name'); ?></h1>
                                    <p class="lead mb-3"><?php print array_get($slide, 'role'); ?></p>
                                    <p class="lead"><?php print array_get($slide, 'bio'); ?></p>
                                    <module type="social_links" template="skin-3"/>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-sm-10 col-md-12 col-lg-2">
        <div class="d-flex flex-lg-column align-items-center justify-content-center">
            <div class="slick-slider">
                <div class="slick-paging"></div>
            </div>
        </div>
    </div>
</div>
