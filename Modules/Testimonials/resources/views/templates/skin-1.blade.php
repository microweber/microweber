@php
/*
 
type: layout
 
name: Skin-1
 
description: Skin-1
 
*/
@endphp

@php
$rand = uniqid();
$limit = 40;
@endphp

<script>mw.lib.require('slick');</script>

<script>
    $(document).ready(function () {
        var items = $(".mwt-face-holder");
        $(".mwt-faces").append(items.eq(0).clone(true));
        $(".mwt-faces").prepend(items.eq(items.length - 1).clone(true));
        var configFaces = function (nextSlide) {
            nextSlide = nextSlide || 0;
            var active = $(".mwt-face-holder")
                .removeClass('active subactive')
                .one('click', function () {
                    $(".mw-testimonials-faces").slick('slickGoTo', $(this).attr('data-index'));
                    return false;
                })
                .eq(nextSlide + 1)
                .addClass('active');
            active.prev('.mwt-face-holder').addClass('subactive');
            active.next('.mwt-face-holder').addClass('subactive');
        }
        var el = $(".mw-testimonials-faces");
        el.slick({
            infinite: true,
            dots: false,
            arrows: false,
            adaptiveHeight: true,
            rtl: document.documentElement.dir === 'rtl',
        })
            .on('beforeChange init reInit', function (event, slick, currentSlide, nextSlide) {
                configFaces(nextSlide);
            });
        configFaces();
    });
</script>

<div class="testimonials-faces-wrapper overflow-hidden">
    <div class="testimonials-wrapper mx-auto">
        <div class="mw-testimonials mw-testimonials-faces">
            @if($testimonials->isEmpty())
                <p>No testimonials available.</p>
            @else
                @foreach ($testimonials as $item)
                    <div class="mw-testimonials-item-faces">
                        <div class="mw-testimonials-item-faces-content">
                            <div class="row text-center">
                                <div class="col-12 col-lg-10 col-lg-8 mx-auto">
                                    <i class="mdi mdi-format-quote-close icon-size-46px d-block mb-5"></i>
                                    <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    @if (isset($all_have_pictures))
        <div class="mwt-faces my-3">
            @php $count = -1; @endphp
            @foreach ($testimonials as $item)
                @php $count++; @endphp
                <span class="mwt-face-holder" data-index="{{ $count }}">
                @if (isset($item['client_website']))
                    <a href="{{ $item['client_website'] }}" class="mwt-face" style="background-image: url({{ thumbnail($item['client_image'], 250) }});"></a>
                @else
                    <span class="mwt-face" style="background-image: url({{ thumbnail($item['client_image'], 250) }});"></span>
                @endif
                </span>
            @endforeach
        </div>
    @else
        <div class="mwt-faces my-3">
            @php $count = -1; @endphp
            @foreach ($testimonials as $item)
                @php $count++; @endphp
                <span class="mwt-face-holder" data-index="{{ $count }}">
                @if (isset($item['client_website']))
                    <a href="{{ $item['client_website'] }}" class="mwt-face-name">{{ $item['name'] }}</a>
                @else
                    <span class="mwt-face-name">{{ $item['name'] }}</span>
                @endif
                </span>
            @endforeach
        </div>
    @endif

    <div class="testimonials-wrapper mx-auto">
        <div class="mw-testimonials mw-testimonials-faces">
            @foreach ($testimonials as $item)
                <div class="mw-testimonials-item-faces">
                    <div class="mw-testimonials-item-faces-content mt-2">
                        <div class="row text-center">
                            <div class="col-12 col-lg-10 col-lg-8 mx-auto">
                                @if (isset($item['name']))
                                    <h5 class="mb-0">{{ $item['name'] }}</h5>
                                @endif
                                @if (isset($item['client_company']))
                                    <p class="mb-0">{{ $item['client_company'] }}</p>
                                @endif

                                @if (isset($item['client_website']))
                                    <a class="my-1 d-block" href="{{ $item['client_website'] }}">{{ $item['client_website'] }}</a>
                                @endif

                                @if (isset($item['client_role']))
                                    <p>{{ $item['client_role'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
