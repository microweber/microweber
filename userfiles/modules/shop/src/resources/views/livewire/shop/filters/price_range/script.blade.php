<script>
    mw.lib.require('noUIiSliderStyled');
    $(document).ready(function() {

        let priceRangeElement = document.getElementById('{{$priceRangeElement}}');
        if(priceRangeElement && priceRangeElement.noUiSlider){
            priceRangeElement.noUiSlider.destroy();
        }

        let shopPriceRange = noUiSlider.create(priceRangeElement, {
            start: [{{$priceFrom}},{{$priceTo}}],
            step: 1,
            connect: true,
            range: {
                'min': {{ $minPrice }},
                'max': {{ $maxPrice }}
            }
        });

        let shopPriceRangeFrom = document.getElementById('{{ $priceFromElementId }}');
        let shopPriceRangeTo = document.getElementById('{{ $priceToElementId }}');

        shopPriceRangeFrom.addEventListener('change', function () {
            shopPriceRange.set([shopPriceRangeFrom.value, shopPriceRangeTo.value]);
        });

        shopPriceRangeTo.addEventListener('change', function () {
            shopPriceRange.set([shopPriceRangeFrom.value, shopPriceRangeTo.value]);
        });

        shopPriceRange.on('update', function (values) {

            shopPriceRangeFrom.value = values[0];
            shopPriceRangeTo.value = values[1];
            shopPriceRangeFrom.dispatchEvent(new Event('input'));
            shopPriceRangeTo.dispatchEvent(new Event('input'));

        });

    });
</script>
