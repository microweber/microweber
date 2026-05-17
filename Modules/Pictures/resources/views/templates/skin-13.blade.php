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

@if(isset($data))
    @php
        $rand = uniqid();
    @endphp

    <div class="slick-gallery-2-holder">
        <div class="slick-gallery-2">
            @php $count = -1; @endphp
            @if(empty($data))
                {{-- task-2026-05-17-525769 / AI-812 — wrap admin-targeted empty-state copy in is_admin() gate;
                     adopt AI-780a typed empty-state pattern (heading + body + CTA pointing to admin_url('media')).
                     Pre-fix the bare <p> rendered "No pictures added. Please add pictures to the module." to
                     anonymous frontend visitors — admin-targeted copy leaked to public surface. --}}
                @if (is_admin())
                    <div class="mw-canvas-empty-state" data-mw-ai780-content-type="picture">
                        <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
                    </div>
                @endif
            @else
                @foreach($data as $item)
                @php $count++; @endphp
                <div class="slide item pictures picture-{{ $item['id'] ?? '' }}">
                    {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                    {!! responsive_thumbnail($item['filename'] ?? '', 880, 550, ['class' => 'img-fluid', 'crop' => true]) !!}
                </div>
            @endforeach
            @endif
        </div>

        <div class="slick-arrows"></div>
    </div>
@endif
