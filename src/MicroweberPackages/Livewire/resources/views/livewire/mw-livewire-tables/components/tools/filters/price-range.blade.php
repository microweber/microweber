@php
    $theme = $component->getTheme();
@endphp

@if ($theme === 'tailwind')
    not supported
@elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')

    @php
    $productsMinPriceRounded = 0;
    $productsMaxPriceRounded = 1000;
    $getMinPrice = 0;
    $getMaxPrice = 0;
    $currencySymbol = 'USD';
    $priceBetween = 0;
    @endphp


    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $(function() {
            $( "#slider-range" ).slider({
                range: true,
                min: {{$productsMinPriceRounded}},
                max: {{$productsMaxPriceRounded}},
                values: [ {{$getMinPrice}}, {{$getMaxPrice}} ],
                slide: function( event, ui ) {

                    $('.js-shop-products-price-between').val(ui.values[ 0 ] +','+ ui.values[ 1 ]);

                    $( "#amount" ).val( "{{$currencySymbol}}" + ui.values[ 0 ] + " - {{$currencySymbol}}" + ui.values[ 1 ] );
                }
            });
            $( "#amount" ).val( "{{$currencySymbol}}" + $( "#slider-range" ).slider( "values", 0 ) +
                " - {{$currencySymbol}}" + $( "#slider-range" ).slider( "values", 1 ) );
        });
    </script>

    <div class="col-md-12">

        <input type="text" id="amount" readonly  style="border:0; color:#4592ff; font-weight:bold;background: #f5f5f5;padding: 1px;font-size: 16px;">

        <input type="hidden" class="js-shop-products-price-between" value="{{$priceBetween}}" name="priceBetween" />

        <div id="slider-range" class="mb-4"></div>
    </div>


@endif
