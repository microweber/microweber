@php
/*

type: layout

name: Skin-18

description: Skin-18

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

<style>
    .mw-testimonials-item-faces-content {
        background: #fff;
        text-align: center;
        padding: 100px 30px;
        margin: 20px;
        position: relative;
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        border-radius: 10px;
    }

    .testimonial-1-icon {
        position: absolute;
        font-size: 20rem;
        opacity: 10%;
        right: 28%;
        top: -20%;
    }

    .mw-testimonials-item-faces-img-group33 {
        position: absolute;
        bottom: 15px;
        left: 85px;
    }

    .mw-testimonials-item-faces-img-group41 {
        position: absolute;
        top: 35px;
        right: 85px;
    }

    .testimonials-faces-wrapper .slick-arrows-1 .slick-arrow {
        background-color: transparent!important;
        color: #000000;
    }

    .testimonials-faces-wrapper .slick-arrows-1 .slick-arrow:before {
        color: #000000;
    }

    .slick-prev:hover:before, .slick-prev:focus:before, .slick-next:hover:before, .slick-next:focus:before {
        font-size: 50px;
    }

    .testimonials-faces-wrapper .slick-prev {
        top: unset;
        bottom: -93px;
        position: absolute;
        left: 79%!important;
    }

    .testimonials-faces-wrapper .slick-next {
        top: unset;
        bottom: -93px;
        position: absolute;
        left: 79%!important;
    }

    .mw-testimonials-item-faces-content p {
        font-size: 20px;
        line-height: 2.5rem;
    }

    .mw-testimonials-item-faces-image-for-change {
        position: absolute;
    }

    @media screen and (max-width: 1433px) {
        .mw-testimonials-item-faces-image-for-change {
            display: none;
        }
    }

    .mw-testimonials-item-faces-image-for-change-1 {
        top: 20%;
        left: 0;
    }

    .mw-testimonials-item-faces-image-for-change-2 {
        bottom: 67%;
        right: 0%;
    }

    .mw-testimonials-item-faces-image-for-change-3 {
        top: 45%;
        left: -14%;
    }

    .mw-testimonials-item-faces-image-for-change-4 {
        bottom: 43%;
        right: -15%;
    }

    .mw-testimonials-item-faces-image-for-change-5 {
        bottom: 20%;
        left: -10%;
    }

    .mw-testimonials-item-faces-image-for-change-6 {
        bottom: 15%;
        right: 0;
    }
</style>

<div class="py-5 position-relative">
    <img loading="lazy" class="mw-testimonials-item-faces-image-for-change mw-testimonials-item-faces-image-for-change-1" src="{{ asset('templates/big2/assets/img/layouts/testimonials-image-for-change-1.png') }}"/>
    <img loading="lazy" class="mw-testimonials-item-faces-image-for-change mw-testimonials-item-faces-image-for-change-2" src="{{ asset('templates/big2/assets/img/layouts/testimonials-image-for-change-2.png') }}"/>
    <img loading="lazy" class="mw-testimonials-item-faces-image-for-change mw-testimonials-item-faces-image-for-change-3" src="{{ asset('templates/big2/assets/img/layouts/testimonials-image-for-change-3.png') }}"/>
    <img loading="lazy" class="mw-testimonials-item-faces-image-for-change mw-testimonials-item-faces-image-for-change-4" src="{{ asset('templates/big2/assets/img/layouts/testimonials-image-for-change-4.png') }}"/>
    <img loading="lazy" class="mw-testimonials-item-faces-image-for-change mw-testimonials-item-faces-image-for-change-5" src="{{ asset('templates/big2/assets/img/layouts/testimonials-image-for-change-5.png') }}"/>
    <img loading="lazy" class="mw-testimonials-item-faces-image-for-change mw-testimonials-item-faces-image-for-change-6" src="{{ asset('templates/big2/assets/img/layouts/testimonials-image-for-change-6.png') }}"/>

    <h2 class="edit safe-mode col-md-10 text-center mx-auto mb-5" style="font-weight: 700;" field="testimonial-skin-16-title" rel="module">Trusted by Agencies <br> & Store Owners</h2>

    <div class="testimonials-faces-wrapper position-relative">
        <div class="slick-arrows-1 position-relative">
            <img loading="lazy" class="mw-testimonials-item-faces-img-group33" src="{{ asset('templates/big2/assets/img/layouts/testimonials-Group-33.png') }}"/>
            <img loading="lazy" class="mw-testimonials-item-faces-img-group41" src="{{ asset('templates/big2/assets/img/layouts/testimonials-Group-41.png') }}"/>
            <div class="testimonials-wrapper mx-auto">
                <div class="mw-testimonials mw-testimonials-faces slickslider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "dots": false, "arrows": true}'>
                    @if($testimonials->isEmpty())
                        <p class="mw-pictures-clean">No testimonials available.</p>
                    @else
                        @foreach ($testimonials as $item)
                            <div class="mw-testimonials-item-faces">
                                <div class="mw-testimonials-item-faces-content position-relative col-md-8 mx-auto">
                                    <i class="mdi mdi-format-quote-close testimonial-1-icon"></i>

                                    <div class="row text-center position-relative">
                                        <div class="col-12 col-md-10 mx-auto">
                                            <p class="mb-0"> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            @if (isset($all_have_pictures))
                <div class="mwt-faces col-md-6 my-3 d-flex align-items-center justify-content-center pt-md-0 pt-5">
                    @php $count = -1; @endphp
                    @foreach ($testimonials as $item)
                        @php $count++; @endphp
                        <span class="mwt-face-holder" data-index="{{ $count }}">
                        @if (isset($item['client_website']))
                            <a href="{{ $item['client_website'] }}" class="mwt-face" style="background-image: url({{ thumbnail($item['client_image'], 650) }});"></a>
                        @else
                            <span class="mwt-face" style="background-image: url({{ thumbnail($item['client_image'], 650) }});"></span>
                        @endif
                        </span>
                    @endforeach
                </div>
            @else
                <div class="mwt-faces col-md-6 my-3 d-flex align-items-center justify-content-center pt-md-0 pt-5">
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
        </div>
    </div>
</div>
