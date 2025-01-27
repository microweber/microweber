<?php

/*

type: layout

name: Skin-8

description: Skin-8

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
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 575,
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
                <div class="border testimonials-background-variable testimonialBorderVariable   mx-3 h-100 p-5">
                    <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

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
