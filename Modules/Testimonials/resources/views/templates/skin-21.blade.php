<?php

/*

type: layout

name: Skin-21

description: Skin-21

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


    @media screen and (min-width: 1000px) {

        <?php echo '#' . $params['id'] . ' '; ?>
            .slick-arrow.slick-prev {
                left: unset !important;
                top: 60% !important;
                right: -51px !important;
                transform: translate(-50%, -50%) !important;
                background-color: #fff !important;
                border: 1px solid #ececec;
                opacity: 1;
            }


        <?php echo '#' . $params['id'] . ' '; ?>
            .slick-arrow.slick-next {
                left: unset !important;
                top: 45% !important;
                right: 19px !important;
                transform: translate(-50%, -50%) !important;
                background-color: #fff !important;
                border: 1px solid #ececec;
                opacity: 1;
            }
    }


    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-arrows-1 .slick-arrow:before {
        margin-bottom: 2px !important;
        opacity: 1;

    }

    <?php echo '#' . $params['id'] . ' '; ?>

    .action-blog-quote {
        right: -13px;
        top: 211px;
    }

</style>

<div class="slick-arrows-1">
    <div class="slickslider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "dots": false, "arrows": true}'>
        @if($testimonials->isEmpty())
            <p>No testimonials available.</p>
        @else
            <?php foreach ($testimonials as $item): ?>
            <div class="testimonials-26-item p-5">
                <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

                <div class="testimonials-26-author">
                    <?php if ($item['client_image']): ?>
                        <img loading="lazy" src="<?php print thumbnail($item['client_image'], 800); ?>"/>
                    <?php endif; ?>

                    <?php if ($item['client_role']): ?>
                        <span><?php print $item['client_role']; ?></span>
                    <?php endif; ?>

                    <?php if ($item['name']): ?>
                        <h4 class=" mb-0"><?php print $item['name']; ?></h4>
                    <?php endif; ?>

                    <?php if ($item['client_company']): ?>
                        <p class="mb-0"><?php print $item['client_company']; ?></p>
                    <?php endif; ?>

                    <?php if ($item['client_website']): ?>
                        <a class="my-1 d-block"
                           href="<?php print $item['client_website']; ?>"><?php print $item['client_website'] ?></a>
                    <?php endif; ?>

                </div>
            </div>
        <?php endforeach; ?>
        @endif

    </div>
</div>
