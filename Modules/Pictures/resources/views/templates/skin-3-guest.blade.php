<?php

/*

type: layout

name: Skin-3-guest

description: Skin-3-guest

*/

?>
    <script>
        var arrowsSize = function () {
            var currentSliderWidth = $('.slick-gallery-2-holder .slick-slide.slick-current').outerWidth();
            if (currentSliderWidth > $(window).width()) {
                currentSliderWidth = $(window).width();
            }
            $('.slick-arrows').css({'width': currentSliderWidth + 'px'})
        }
        /* ###################### Slick   ###################### */
        $(document).ready(function () {
            if ($('<?php print '#' . $params['id']; ?> .slick-gallery-2').length > 0) {
                $('<?php print '#' . $params['id']; ?> .slick-gallery-2').each(function () {
                    var el = $(this);

                    var spn = mw.spinner({
                        decorate: true,
                        element: this,
                        size: 50,
                        color: (getComputedStyle(document.documentElement).getPropertyValue('--textDark') || '#111111').trim()
                    })

                    mw.image.preloadAll(Array.from(this.querySelectorAll('img')).map(function (node){
                        node.src = node.dataset.src || node.src;
                        return node.src;
                    }), function (){
                        setTimeout(function (){
                            spn.remove()
                            el.addClass('is-ready')
                            el.slick({
                                rtl: document.documentElement.dir === 'rtl',
                                centerMode: true,
                                slidesToShow: 1,
                                variableWidth: true,

                                dots: true,
                                arrows: true,
                                appendArrows: $("<?php print '#' . $params['id']; ?> .slick-arrows"),
                                responsive: [
                                    {
                                        breakpoint: 768,
                                        settings: {
                                            arrows: true,
                                            slidesToShow: 1
                                        }
                                    },
                                    {
                                        breakpoint: 480,
                                        settings: {
                                            arrows: true,
                                            slidesToShow: 1
                                        }
                                    }
                                ]
                            });
                            // On before slide change
                            $('<?php print '#' . $params['id']; ?> .slick-gallery-2').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                                $('<?php print '#' . $params['id']; ?> .slick-gallery-2-holder .slick-arrow').hide();
                            });
                            // On after slide change
                            $('<?php print '#' . $params['id']; ?> .slick-gallery-2').on('afterChange', function (event, slick, currentSlide, nextSlide) {
                                $('<?php print '#' . $params['id']; ?> .slick-gallery-2-holder .slick-arrow').show();
                            });
                            setTimeout(function (){
                                el.slick('refresh');
                                arrowsSize()
                            },20)
                        },20)
                    });
                });
            }
        });



        $(window).on('resize', function () {
            arrowsSize()
        });

        $(window).on('load', function () {
            arrowsSize()
        });

    </script>
<?php if (is_array($data)): ?>
    <?php

    $rand = uniqid();

    $nodeId = 'gallery' . $rand;
    ?>

    <style>


        #<?php print $nodeId; ?> img{
            opacity:0;
            position: absolute;
        }

        #<?php print $nodeId; ?> .is-ready img{
             opacity: 1;
             position: relative;
         }

    </style>


<div class="guesthouse-slider" id="<?php print $nodeId; ?>">
    <div class="slick-gallery-2-holder">
        <div class="slick-gallery-2">
            <?php $count = -1;
            foreach ($data as $item): ?>
                <?php $count++; ?>
                <div class="slide item pictures picture-<?php print $item['id']; ?>">
                    <img   data-src="<?php print thumbnail($item['filename'], 880, 550, true); ?>" alt="">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="slick-arrows"></div>
    </div>
</div>
<?php endif; ?>
