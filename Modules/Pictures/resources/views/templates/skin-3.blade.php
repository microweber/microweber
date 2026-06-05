{{--
type: layout
name: Skin-3
description: Skin-3
--}}

<script>mw.lib.require('slick');</script>
<script>
    (function (){
        var galleryImages = [];
        $(document).ready(function () {
            if ($('#{{ $params['id'] ?? '' }} .slick-gallery').length > 0) {
                $('#{{ $params['id'] ?? '' }} .slick-gallery').each(function () {
                    Array.from(this.querySelectorAll('.slick-slide-item-x')).forEach(function (node, index){
                        var img = node.querySelector('img')
                        galleryImages.push({
                            url: img.dataset.largeImage || img.src
                        })
                        node.addEventListener('click', function (){
                            mw.gallery(galleryImages, index)
                        })
                    })

                    var el = $(this);
                    el.slick({
                        rtl: document.documentElement.dir === 'rtl',
                        centerMode: false,
                        centerPadding: '0px',
                        slidesToShow: 4,
                        arrows: true,
                        autoplay: false,
                        autoplaySpeed: 2000,
                        dots: true,
                        responsive: [
                            {
                                breakpoint: 1200,
                                settings: {
                                    arrows: false,
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
    })()
</script>

<style>
    #{{ $params['id'] ?? '' }} .slick-track {
        display: flex;
        gap: 0.6rem;
    }
</style>

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
        <div class="slick-gallery">
            @if(empty($data))
                {{-- task-2026-05-17-525769 / AI-812 — wrap admin-targeted empty-state copy in is_admin() gate;
                     adopt AI-780a typed empty-state pattern (heading + body + CTA pointing to admin_url('media')).
                     Pre-fix the bare <p> rendered "No pictures added. Please add pictures to the module." to
                     anonymous frontend visitors — admin-targeted copy leaked to public surface. --}}
                @if (is_admin())
                    <div class="mw-canvas-empty-state" data-mw-content-type="picture">
                        <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
                    </div>
                @endif
            @else
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

                <div class="d-block position-relative">
                    @if($itemLink)
                        <a @if($click_image_event == 'link_target_blank') target="_blank" @endif
                           href="{{ $itemLink }}" rel="noopener noreferrer">
                    @endif

                    <div class="img-as-background mh-350 mb-3">
                        {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                        {!! responsive_thumbnail($item['filename'] ?? '', 350, 350, ['alt' => $item['title'] ?? $item['description'] ?? __('Image'), 'class' => 'img-fluid', 'crop' => true]) !!}
                    </div>

                    @if($itemLink)
                        </a>
                    @endif

                    @if($itemTitle)
                        <div class="bg-body-opacity-5 w-100 px-3 py-1 text-center" style="z-index: 9;">
                            <h6 class="m-0">{{ $itemTitle }}</h6>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
        </div>
    </div>
@endif
