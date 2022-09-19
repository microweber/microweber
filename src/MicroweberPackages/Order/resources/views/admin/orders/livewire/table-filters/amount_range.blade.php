<div class=" col-12 col-sm-6 col-md-4 col-lg-4 mb-4 js-order-amount-rage-filter">
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

            let amountMin = document.getElementById('js-amount-min');
            let amountMax = document.getElementById('js-amount-max');
            let amountRangeValue = amountMin.value + ', ' + amountMax.value;
            let amountRangeElement = document.getElementById('js-amount-range');

            const amountRangeExp = amountRangeElement.value.split(",");
            if (amountRangeExp) {
                if (amountRangeExp[0]) {
                    amountMin.value = amountRangeExp[0];
                }
                if (amountRangeExp[1]) {
                    amountMax.value = amountRangeExp[1];
                }
            }

            amountMin.onkeyup = function () {
                amountRangeValue = amountMin.value + ',' + amountMax.value;
                amountRangeElement.value = amountRangeValue;
                amountRangeElement.dispatchEvent(new Event('input'));
            };

            amountMax.onkeyup = function () {
                amountRangeValue = amountMin.value + ',' + amountMax.value;
                amountRangeElement.value = amountRangeValue;
                amountRangeElement.dispatchEvent(new Event('input'));
            };
        });
    </script>

</div>
