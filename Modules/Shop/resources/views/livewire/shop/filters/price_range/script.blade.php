<script>
    mw.lib.require('nouislider');
    // Wait for the lazy-loaded nouislider library before constructing the slider —
    // vanilla DOMContentLoaded fires before the dynamic <script> finishes loading,
    // so poll briefly instead of using $(document).ready.
    (function waitForNoUiSlider(retries) {
        if (typeof noUiSlider === 'undefined') {
            if (retries > 0) {
                setTimeout(function () { waitForNoUiSlider(retries - 1); }, 100);
            }
            return;
        }
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

    })(30);  // 30 × 100ms = 3s max wait for nouislider to load
</script>
