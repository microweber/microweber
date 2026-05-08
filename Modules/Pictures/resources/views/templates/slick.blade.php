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
                    <p class="mw-pictures-clean">No pictures added. Please add pictures to the module.</p>
                @else
                    @foreach($data as $item)
                    @php $count++; @endphp
                    <div class="slick-pictures-item slick-pictures-item-{{ $item['id'] ?? '' }}">
                        <div class="thumbnail-wrapper">
                            <div class="thumbnail">
                                {{-- task-2026-05-05-90021f — lazy-load gallery images. --}}
                                {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                {!! responsive_thumbnail($item['filename'] ?? '', 300, null, ['alt' => __('Image'), 'class' => 'img-fluid']) !!}
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
