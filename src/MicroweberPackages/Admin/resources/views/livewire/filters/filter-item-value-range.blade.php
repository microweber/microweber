<div>
    <button type="button" class="btn btn-badge-dropdown js-dropdown-toggle-{{$this->id}} @if(!empty($selectedItem)) btn-primary @else btn-outline-primary @endif btn-sm icon-left">

        @if($itemValue)
            {{$name}}: {{$itemValue}}
        @else
            {{$name}}
        @endif

        <span class="mt-2">&nbsp;</span>

    </button>

    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->id}}" @if(!$showDropdown) style="display: none" @endif>

        <input type="hidden" id="js-price-range" wire:model.stop="itemValue">

        <div class="mb-3 mb-md-0 input-group">
            <span class="input-group-text">From</span>
            <input type="number" class="form-control" id="js-price-min" placeholder="Min">
            <span class="input-group-text">To</span>
            <input type="number" class="form-control" id="js-price-max" placeholder="Max">
        </div>

        <script>
            document.addEventListener('livewire:load', function () {

                let priceMin = document.getElementById('js-price-min');
                let priceMax = document.getElementById('js-price-max');
                let priceRangeValue = priceMin.value + ', ' + priceMax.value;
                let priceRangeElement = document.getElementById('js-price-range');

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
                    if (priceMax.value > priceMin.value) {
                        priceRangeValue = priceMin.value + ',' + priceMax.value;
                        priceRangeElement.value = priceRangeValue;
                        priceRangeElement.dispatchEvent(new Event('input'));
                    }
                };

                priceMax.onkeyup = function() {
                    if (priceMax.value > priceMin.value) {
                        priceRangeValue = priceMin.value + ',' + priceMax.value;
                        priceRangeElement.value = priceRangeValue;
                        priceRangeElement.dispatchEvent(new Event('input'));
                    }
                };
            });
        </script>
    </div>

    <script>
        $(document).ready(function() {
            $('body').on('click', function(e) {
                if (!mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target,['js-dropdown-toggle-{{$this->id}}','js-dropdown-content-{{$this->id}}'])) {
                    $('.js-dropdown-content-{{$this->id}}').hide();
                }
            });
            $('.js-dropdown-toggle-{{$this->id}}').click(function () {
                $('.js-dropdown-content-{{$this->id}}').toggle();
            });
        });
    </script>
</div>
