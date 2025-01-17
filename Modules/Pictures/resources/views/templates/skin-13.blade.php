{{--
type: layout
name: Skin-13
description: Skin-13
--}}

<script>
    /* ###################### Slick   ###################### */
    var skin13arrowSizes = function () {
        var currentSliderWidth = $('.slick-gallery-2-holder .slick-slide.slick-current').outerWidth();
        if (currentSliderWidth > $(window).width()) {
            currentSliderWidth = $(window).width();
        }
        $('.slick-arrows').css({'width': currentSliderWidth + 'px'})
    }
    
    $(document).ready(function () {
        if ($('#{{ $params['id'] ?? '' }} .slick-gallery-2').length > 0) {
            $('#{{ $params['id'] ?? '' }} .slick-gallery-2').each(function () {
                var el = $(this);
                el.slick({
                    rtl: document.documentElement.dir === 'rtl',
                    centerMode: true,
                    slidesToShow: 1,
                    variableWidth: true,
                    dots: true,
                    arrows: true,
                    appendArrows: $("#{{ $params['id'] ?? '' }} .slick-arrows"),
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
            });

            // On before slide change
            $('#{{ $params['id'] ?? '' }} .slick-gallery-2').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                $('#{{ $params['id'] ?? '' }} .slick-gallery-2-holder .slick-arrow').hide();
            });
            // On after slide change
            $('#{{ $params['id'] ?? '' }} .slick-gallery-2').on('afterChange', function (event, slick, currentSlide, nextSlide) {
                $('#{{ $params['id'] ?? '' }} .slick-gallery-2-holder .slick-arrow').show();
            });
        }
    });

    $(window).on('resize', function () {
        skin13arrowSizes()
    });

    $(window).on('load', function () {
        skin13arrowSizes()
    });
</script>

@if(isset($data) && is_array($data))
    @php
        $rand = uniqid();
    @endphp

    <div class="slick-gallery-2-holder">
        <div class="slick-gallery-2">
            @php $count = -1; @endphp
            @foreach($data as $item)
                @php $count++; @endphp
                <div class="slide item pictures picture-{{ $item['id'] ?? '' }}">
                    <img src="{{ thumbnail($item['filename'] ?? '', 880, 550, true) }}" alt="">
                </div>
            @endforeach
        </div>

        <div class="slick-arrows"></div>
    </div>
@endif
