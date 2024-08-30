<script>
    mw.lib.require('noUIiSliderStyled');
    $(document).ready(function() {

        let priceRangeElement = document.getElementById('{{$priceRangeElement}}');
        if(priceRangeElement && priceRangeElement.noUiSlider){
            priceRangeElement.noUiSlider.destroy();
        }

        let shopPriceRange = noUiSlider.create(priceRangeElement, {
            start: [{{$filteredPriceFrom}},{{$filteredPriceTo}}],
            step: 1,
            connect: true,
            range: {
                'min': {{ $filteredMinPrice }},
                'max': {{ $filteredMaxPrice }}
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

            let filteredPriceFrom = parseFloat('{{$filteredPriceFrom}}');
            let filteredPriceTo = parseFloat('{{$filteredPriceTo}}');

            shopPriceRangeFrom.value = parseFloat(values[0]);
            shopPriceRangeTo.value = parseFloat(values[1]);

            if ((filteredPriceFrom != shopPriceRangeFrom.value)
                || (filteredPriceTo != shopPriceRangeTo.value)) {
                shopPriceRangeFrom.dispatchEvent(new Event('input'));
                shopPriceRangeTo.dispatchEvent(new Event('input'));
            }

        });

    });
</script>
