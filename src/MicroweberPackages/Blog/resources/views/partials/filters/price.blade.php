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

                let maxValueText = ui.values[ 1 ];
                if (ui.values[1] > 10000000000) {
                    maxValueText = '∞';
                }
                $( "#amount" ).val( "{{$currencySymbol}}" + ui.values[ 0 ] + " - {{$currencySymbol}}" + maxValueText );
            }
        });
        $( "#amount" ).val( "{{$currencySymbol}}" + $( "#slider-range" ).slider( "values", 0 ) +
            " - {{$currencySymbol}}" + $( "#slider-range" ).slider( "values", 1 ) );
    });
</script>

<div class="col-md-12">
    <p>
        <label for="amount">Price range:</label>
        <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
    </p>

    <input type="hidden" class="js-shop-products-price-between" value="{{$priceBetween}}" name="priceBetween" />

    <div id="slider-range" class="mb-4"></div>
</div>
