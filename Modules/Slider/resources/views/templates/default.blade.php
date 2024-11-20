

<div class="swiper" id="slider-{{ $params['id'] }}">
  <!-- Additional required wrapper -->
  <div class="swiper-wrapper">


            @if(!empty($slides))




            @foreach($slides as $slideKey => $slide)
                <div class="swiper-slide" style="text-align: {{ isset($slide['alignItems']) && !empty($slide['alignItems']) ? $slide['alignItems'] : 'center' }};">
                    @if(isset($slide['image']) && !empty($slide['image']))
                        <img src="{{ $slide['image'] }}" alt="{{ isset($slide['title']) && !empty($slide['title']) ? $slide['title'] : '' }}" class="slide-image" />
                    @endif
                    <div class="slide-content">
                        @if(isset($slide['title']) && !empty($slide['title']))
                            <h3 class="slide-title slide-title-{{ $slideKey }}">
                                {{ $slide['title'] }}
                            </h3>
                        @endif
                        @if(isset($slide['description']) && !empty($slide['description']))
                            <p class="slide-description slide-description-{{ $slideKey }}">
                                {{ $slide['description'] }}
                            </p>
                        @endif
                        @if(isset($slide['buttonText']) )
                            @if(!isset($slide['showButton']) || $slide['showButton'])
                                <a href="{{ isset($slide['url']) && !empty($slide['url']) ? $slide['url'] : '#' }}" class="slide-button slide-button-{{ $slideKey }}">
                                    {{ $slide['buttonText'] }}
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="swiper-slide">
                <p>{{ __('No slides available.') }}</p>
            </div>
        @endif

  </div>

  <div class="swiper-pagination"></div>


  <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>



</div>

<style>
    #slider-{{ $params['id'] }} {
        padding-bottom: 40px;
    }
    .slider-module {
        width: 100%;
        overflow: hidden;
    }

    .slide {
        min-width: 100%;
    }
    .slide-image {
        width: 100%;
        height: auto;
    }
    .slide-content {
        padding: 10px;
    }
    .slide-title {
        font-size: 24px;
        margin: 10px 0;
    }
    .slide-description {
        font-size: 16px;
        margin: 10px 0;
    }
    .slide-button {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
    }
    .slide-title, .slide-description {
        color: inherit;
    }

    @foreach($slides as $slideKey => $slide)
        #slider-{{ $params['id'] }} .slide-title-{{ $slideKey }} {
            color: {{ isset($slide['titleColor']) && !empty($slide['titleColor']) ? $slide['titleColor'] : 'inherit' }};
            font-size: {{ isset($slide['titleFontSize']) && !empty($slide['titleFontSize']) ? $slide['titleFontSize'] : '24' }}px;
            font-family: {{ isset($slide['titleFontFamily']) && !empty($slide['titleFontFamily']) ? $slide['titleFontFamily'] : 'inherit' }};
        }
        #slider-{{ $params['id'] }} .slide-description-{{ $slideKey }} {
            color: {{ isset($slide['descriptionColor']) && !empty($slide['descriptionColor']) ? $slide['descriptionColor'] : 'inherit' }};
            font-size: {{ isset($slide['descriptionFontSize']) && !empty($slide['descriptionFontSize']) ? $slide['descriptionFontSize'] : '16' }}px;
            font-family: {{ isset($slide['descriptionFontFamily']) && !empty($slide['descriptionFontFamily']) ? $slide['descriptionFontFamily'] : 'inherit' }};
        }
        #slider-{{ $params['id'] }} .slide-button-{{ $slideKey }} {
            background-color: {{ isset($slide['buttonColor']) && !empty($slide['buttonColor']) ? $slide['buttonColor'] : 'inherit' }};
            color: {{ isset($slide['buttonTextColor']) && !empty($slide['buttonTextColor']) ? $slide['buttonTextColor'] : 'inherit' }};
            font-size: {{ isset($slide['buttonFontSize']) && !empty($slide['buttonFontSize']) ? $slide['buttonFontSize'] : '16' }}px;
        }
    @endforeach
</style>

<script >

    mw.lib.require('swiper');

    $(document).ready(function(){
        const swiper = new Swiper('#slider-{{ $params['id'] }}', {


            autoHeight: true,
            loop: true,


            pagination: {
                el: '.swiper-pagination',
            },


            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },



        });
    });



</script>

