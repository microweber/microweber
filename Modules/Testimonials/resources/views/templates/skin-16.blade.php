<?php

/*

type: layout

name: Skin-16

description: Skin-16

*/

?>

<?php

$rand = uniqid();
$limit = 40;

?>


<script>mw.lib.require('slick')</script>
<script>
    $(document).ready(function () {
        $('.slickslider', '#<?php echo $params['id']; ?>').slick({
            rtl: document.documentElement.dir === 'rtl',
            responsive: [
                {
                    breakpoint: 1300,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 975,
                    settings: {
                        slidesToShow: 1,
                    }
                }
            ]
        });
    });
</script>

<style>
    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-track {
        display: flex !important;
    }

    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-slide {
        height: inherit !important;
    }

    <?php echo '#' . $params['id'] . ' '; ?>
    .list-inline {
        margin: 0;
    }

    <?php echo '#' . $params['id'] . ' '; ?>
    .list-inline li {
        margin: 0 3px;
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

    .tony-template-testimonial {
        border: 1px solid #DCE2E7;
        border-radius: 10px;
        transition: all 0.4s ease-in-out;

    }

    .tony-template-testimonial:hover {

        border-color: transparent;
        box-shadow: rgba(100, 250, 111, 0.2) 0px 7px 29px 0px;
        transform: scale(1.05);


    }

    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-list {
        overflow: hidden;
    }


</style>

<div class="slick-arrows-1">
    <div class="slickslider" data-slick='{"slidesToShow": 3, "slidesToScroll": 1, "dots": false, "arrows": true}'>
        @if($testimonials->isEmpty())
            <p>No testimonials available.</p>
        @else
            <?php foreach ($testimonials as $item): ?>
            <div class="tony-template-testimonial testimonials-background-variable testimonialBorderVariable mx-3 p-5">

                <div class="py-3 mb-4">
                    <img loading="lazy" height="20" width="auto" src="<?php print asset('templates/big2/assets/img/layouts/tony/testimonials-stars.png'); ?>" class=""/>
                </div>
                <p class="testimonials-tony-p mb-8"> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

                <div class="text-center text-sm-start mb-3">
                    <div class="d-block d-sm-flex align-items-center justify-content-between">
                        <div class="d-block d-sm-flex align-items-center">
                            <?php if ($item['client_image']): ?>
                                <div class="me-3">
                                    <div class="w-40 mx-auto">
                                        <div class="img-as-background rounded-circle square">
                                            <img loading="lazy" src="<?php print thumbnail($item['client_image'], 120); ?>" class="d-block"/>
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
        <?php endforeach; ?>
        @endif

    </div>
</div>
