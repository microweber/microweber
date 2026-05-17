{{--
type: layout
name: Slick
description: Slick Pictures List Template
--}}

@if(isset($data))
    <script>
        mw.lib.require('slick');
    </script>

    <script>mw.moduleCSS("{{ asset('modules/pictures/css/slick.css') }}");</script>

    <script>
        $(document).ready(function () {
            if ($('.slickSlider', '#{{ $params['id'] ?? '' }}').hasClass('slick-initialized')) {
                console.log('initialized');
            } else {
                console.log('not initialized');
            }

            $('.slickSlider', '#{{ $params['id'] ?? '' }}').slick({
                rtl: document.documentElement.dir === 'rtl',
                dots: false,
                arrows: false,
                infinite: false,
                speed: 200,
                slidesToShow: 6,
                slidesToScroll: 6,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 5,
                            slidesToScroll: 5,
                        }
                    },
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 585,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        });
    </script>

    @if(!isset($no_img) || !$no_img)
        <div class="mw-module-images">
            <div class="slickSlider">
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
                    <div class="slick-pictures-item slick-pictures-item-{{ $item['id'] ?? '' }}">
                        <div class="thumbnail-wrapper">
                            <div class="thumbnail">
                                {{-- task-2026-05-05-90021f — lazy-load gallery images. --}}
                                {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                {!! responsive_thumbnail($item['filename'] ?? '', 300, null, ['alt' => $item['title'] ?? $item['description'] ?? __('Image'), 'class' => 'img-fluid']) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
                @endif
            </div>
        </div>
    @endif
@else
    @include('modules.pictures::partials.no-pictures')
@endif
