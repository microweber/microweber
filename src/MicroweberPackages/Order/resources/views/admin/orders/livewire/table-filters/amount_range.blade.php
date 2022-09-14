<div class=" col-12 col-sm-6 col-md-4 col-lg-4 mb-4">
    <input type="hidden" id="js-amount-range" wire:model.stop="filters.amountBetween">
    <label class="d-block">
        Order Amount Range
    </label>
    <div class="mb-3 mb-md-0 input-group">
        <span class="input-group-text">From</span>
        <input type="number" class="form-control" id="js-amount-min" placeholder="Min amount">
        <span class="input-group-text">To</span>
        <input type="number" class="form-control" id="js-amount-max" placeholder="Max amount">
    </div>
    <script>
        document.addEventListener('livewire:load', function () {

            let priceMin = document.getElementById('js-amount-min');
            let priceMax = document.getElementById('js-amount-max');
            let priceRangeValue = priceMin.value + ', ' + priceMax.value;
            let priceRangeElement = document.getElementById('js-amount-range');

            const priceRangeExp = priceRangeElement.value.split(",");
            if (priceRangeExp) {
                if (priceRangeExp[0]) {
                    priceMin.value = priceRangeExp[0];
                }
                if (priceRangeExp[1]) {
                    priceMax.value = priceRangeExp[1];
                }
            }

            priceMin.onkeyup = function() {
                priceRangeValue = priceMin.value + ',' + priceMax.value;
                priceRangeElement.value = priceRangeValue;
                priceRangeElement.dispatchEvent(new Event('input'));
            };

            priceMax.onkeyup = function() {
                priceRangeValue = priceMin.value + ',' + priceMax.value;
                priceRangeElement.value = priceRangeValue;
                priceRangeElement.dispatchEvent(new Event('input'));
            };
        });
    </script>

</div>
