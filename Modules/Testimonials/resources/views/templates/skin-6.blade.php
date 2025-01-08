<?php

/*

type: layout

name: Skin-6

description: Skin-6

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
    .slick-dots {
        position: relative;
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
    <div class="slickslider" data-slick='{"slidesToShow": 3, "slidesToScroll": 1, "dots": true, "arrows": false}'>
        <?php foreach ($testimonials as $item): ?>
            <div class="row text-center mb-5">
                <div class="col-12 col-lg-11 mx-auto">
                    <?php if ($item['client_picture']): ?>
                        <div class="w-80 mx-auto mb-4">
                            <div class="img-as-background rounded-circle square">
                                <img loading="lazy" src="<?php print thumbnail($item['client_picture'], 120); ?>">
                            </div>
                        </div>
                    <?php endif; ?>

                    <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>
                    <br/>
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
        <?php endforeach; ?>
    </div>
</div>
