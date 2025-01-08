<?php

/*

type: layout

name: Skin-22

description: Skin-22

*/

?>

<?php

$rand = uniqid();
$limit = 40;

?>


<script>mw.lib.require('slick')</script>
<script>
    $(document).ready(function () {
        var counter = 1; // Initialize counter to 1
        var slider = $('.slickslider', '#<?php echo $params['id']; ?>');

        slider.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: true,
            arrows: false
        });

        slider.on('afterChange', function(event, slick, currentSlide) {
            var slide = slick.$slides.get(currentSlide);
            var testimonialContent = $(slide).find('.testimonial-content');
            var testimonialImage = $(slide).find('.testimonial-image-two');

            if (counter % 2 === 0) {
                testimonialContent.css('order', 1);
                testimonialImage.css('order', 2);
            } else {
                testimonialContent.css('order', 2);
                testimonialImage.css('order', 1);
            }
            counter++;
        });

        // Manually trigger the afterChange event for the first slide
        slider.trigger('afterChange', [slider, 0]);
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

</style>

<style>
    .job-role {
        color: #000;
        font-size: 15px;
        font-weight: 500;
        margin-bottom: 0;
        opacity: .8;
        text-transform: uppercase;
    }

    .testimonial-content {
        align-items: center;
        display: flex;
        flex-direction: column;
        margin-left: auto;
        margin-right: auto;
        max-width: 440px;
        text-align: center;
        text-transform: uppercase;
    }

    .testimonial-image-two {
        height: 530px;
        object-fit: cover;
        width: 450px;
    }

    .testimonial-quote {
        color: #000;
        font-size: 20px;
        font-weight: 500;
        line-height: 1.4;
        margin-bottom: 30px;
        margin-top: 20px;
        text-transform: uppercase;
    }

    .testimonial-author-name {
        color: #000;
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .testimonial-info-two, .testimonial-slide {
        align-items: center;
        display: flex !important;
    }

    .testimonial-slide {
        background-color: #f1f1f1;
        justify-content: space-between;
    }

    @media screen and (max-width: 991px) {
        .testimonial-content {
            width: 50%;
        }

        .testimonial-image-two {
            height: 350px;
            width: 270px;
        }

        .testimonial-quote {
            font-size: 21px;
            line-height: 30px;
        }
    }

    @media screen and (max-width: 767px) {
        .testimonial-content {
            margin-bottom: 21px;
            margin-top: 21px;
            width: 90%;
        }

        .testimonial-image-two {
            height: auto;
            width: 100%;
        }

        .testimonial-slide {
            column-gap: 30px;
            flex-direction: column-reverse;
            padding: 23px;
            row-gap: 30px;
        }
    }

    @media screen and (max-width: 479px) {
        .testimonial-content {
            margin-bottom: 18px;
            margin-top: 18px;
            width: 95%;
        }

        .testimonial-quote {
            font-size: 18px;
            line-height: 26px;
        }

        .testimonial-slide {
            column-gap: 25px;
            flex-direction: column-reverse;
            padding: 15px;
            row-gap: 25px;
        }
    }
</style>


<div class="slick-arrows-1">
    <div class="slickslider">
        <?php foreach ($testimonials as $item): ?>
            <div class="testimonial-slide">
                <div class="testimonial-content">
                    <div class="testimonial-quote"> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</div>

                    <div class="testimonial-info-two">
                       <div>
                           <?php if ($item['name']): ?>
                               <div class="testimonial-author-name"><?php print $item['name']; ?></div>
                           <?php endif; ?>

                           <?php if ($item['client_role']): ?>
                               <div class="job-role"><?php print $item['client_role']; ?></div>
                           <?php endif; ?>

                           <?php if ($item['client_company']): ?>
                               <small><?php print $item['client_company']; ?></small>
                           <?php endif; ?>

                           <?php if ($item['client_website']): ?>
                               <a class="my-1 d-block"
                                  href="<?php print $item['client_website']; ?>"><?php print $item['client_website'] ?></a>
                           <?php endif; ?>
                       </div>
                    </div>
                </div>
                    <?php if ($item['client_picture']): ?>
                        <img class="testimonial-image-two" loading="lazy"
                             src="<?php print thumbnail($item['client_picture'], 800); ?>"/>
                    <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
