<script>
    mw.lib.require('nouislider');
    // AI-263 Phase C (cycle-186 2026-05-11): wait for the lazy-
    // loaded nouislider library before constructing the slider.
    // Before AI-263, `$(document).ready` waited for jQuery's
    // slower init, giving nouislider.js time to arrive. Now
    // that mw.$ uses vanilla DOMContentLoaded, ready fires
    // before the dynamic `<script>` finishes loading. Use a
    // small retry loop instead of $(document).ready.
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
