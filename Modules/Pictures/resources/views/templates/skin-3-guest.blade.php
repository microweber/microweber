{{--
type: layout
name: Skin-3-guest
description: Skin-3-guest
--}}

<script>
    var arrowsSize = function () {
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
                        // On before slide change
                        $('#{{ $params['id'] ?? '' }} .slick-gallery-2').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                            $('#{{ $params['id'] ?? '' }} .slick-gallery-2-holder .slick-arrow').hide();
                        });
                        // On after slide change
                        $('#{{ $params['id'] ?? '' }} .slick-gallery-2').on('afterChange', function (event, slick, currentSlide, nextSlide) {
                            $('#{{ $params['id'] ?? '' }} .slick-gallery-2-holder .slick-arrow').show();
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

@if(isset($data) && is_array($data))
    @php
        $rand = uniqid();
        $nodeId = 'gallery' . $rand;
    @endphp

    <style>
        #{{ $nodeId }} img {
            opacity: 0;
            position: absolute;
        }

        #{{ $nodeId }} .is-ready img {
            opacity: 1;
            position: relative;
        }
    </style>

    <div class="guesthouse-slider" id="{{ $nodeId }}">
        <div class="slick-gallery-2-holder">
            <div class="slick-gallery-2">
                @php $count = -1; @endphp
                @foreach($data as $item)
                    @php $count++; @endphp
                    <div class="slide item pictures picture-{{ $item['id'] ?? '' }}">
                        <img data-src="{{ thumbnail($item['filename'] ?? '', 880, 550, true) }}" alt="">
                    </div>
                @endforeach
            </div>
            <div class="slick-arrows"></div>
        </div>
    </div>
@endif
