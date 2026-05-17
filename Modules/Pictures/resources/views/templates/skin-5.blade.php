{{--
type: layout
name: Skin-5
description: Skin-5
--}}

<script>mw.lib.require('slick');</script>
<script>
    $(document).ready(function () {
        if ($('#{{ $params['id'] ?? '' }} .slick-gallery').length > 0) {
            $('#{{ $params['id'] ?? '' }} .slick-gallery').each(function () {
                var el = $(this);
                el.slick({
                    rtl: document.documentElement.dir === 'rtl',
                    centerMode: false,
                    centerPadding: '0px',
                    slidesToShow: 3,
                    arrows: true,
                    autoplay: false,
                    autoplaySpeed: 2000,
                    dots: true,
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                arrows: true,
                                centerMode: true,
                                centerPadding: '0px',
                                slidesToShow: 3
                            }
                        }, {
                            breakpoint: 768,
                            settings: {
                                arrows: false,
                                centerMode: true,
                                centerPadding: '0px',
                                slidesToShow: 2
                            }
                        }, {
                            breakpoint: 480,
                            settings: {
                                arrows: false,
                                centerMode: true,
                                centerPadding: '0px',
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            });
        }
    });
</script>

@if(isset($data))
    @php
        $rand = uniqid();
        $click_image_event = 'fullscreen';
        $get_click_image_event = get_option('click_image_event', $params['id'] ?? null);
        if ($get_click_image_event != false) {
            $click_image_event = $get_click_image_event;
        }
    @endphp

    <div class="slick-arrows-1">
        <div class="slick-gallery" style="margin: 0 -15px;">
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
             @foreach($data as $item)
                @php
                    $itemTitle = false;
                    $itemDescription = false;
                    $itemLink = false;
                    $itemAltText = 'Open';
                    if (isset($item['image_options']) && is_array($item['image_options'])) {
                        if (isset($item['image_options']['title'])) {
                            $itemTitle = $item['image_options']['title'];
                        }
                        if (isset($item['image_options']['caption'])) {
                            $itemDescription = $item['image_options']['caption'];
                        }
                        if (isset($item['image_options']['link'])) {
                            $itemLink = $item['image_options']['link'];
                        }
                        if (isset($item['image_options']['alt-text'])) {
                            $itemAltText = $item['image_options']['alt-text'];
                        }
                    }
                @endphp

                <div class="px-3">
                    <div class="d-block position-relative">
                        @if($itemTitle)
                            <div class="position-absolute bg-body-opacity-5 w-100 px-3 py-2 bottom-0 text-center" style="z-index: 9;">
                                <h6 class="m-0">{{ $itemTitle }}</h6>
                            </div>
                        @endif

                        <div class="img-as-background mh-350 mb-3">
                            {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                            {!! responsive_thumbnail($item['filename'] ?? '', 350, 350, ['alt' => __('Image'), 'class' => 'img-fluid', 'crop' => true]) !!}
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    </div>
@endif
