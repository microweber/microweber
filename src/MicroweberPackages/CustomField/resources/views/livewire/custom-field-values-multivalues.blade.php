<div class="mt-3">
    <x-microweber-ui::label value="Values" />

    <div id="js-sortable-items-holder-{{$this->id}}">
        @foreach($customField->fieldValue as $fieldValue)
            <div class="d-flex gap-3 mt-3 js-sortable-item" sort-key="{{ $fieldValue->id }}" wire:key="custom-field-vals-{{ $this->id }}">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="js-sort-handle">
                        <svg class="mdi-cursor-move cursor-grab ui-sortable-handle"
                             fill="#8e8e8e"
                             xmlns="http://www.w3.org/2000/svg" height="24"
                             viewBox="0 96 960 960" width="24">
                            <path
                                d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"></path>
                        </svg>
                    </div>
                </div>
                <div class="w-full">
                    <x-microweber-ui::input class="mt-1 block w-full" wire:model.debounce="inputs.{{ $fieldValue->id }}" />
                    @error('inputs.'.$fieldValue->id) <span class="text-danger error">{{ $message }}</span>@enderror
                </div>

                @if (isset($customField->options['as_price_modifier']) && $customField->options['as_price_modifier'])
                    <div class="w-full" style="max-width: 120px;">
                        <x-microweber-ui::input type="number" class="mt-1 block w-full" wire:model.debounce="priceModifiers.{{ $fieldValue->id }}" placeholder="0" />
                        @error('priceModifiers.'.$fieldValue->id) <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                @endif


                <div class="d-flex gap-3 justify-content-center align-items-center">
                    <button class="btn btn-outline-success btn-sm" wire:click.prevent="add()">Add</button>
                    <button class="btn btn-outline-danger btn-sm" wire:click.prevent="remove({{$fieldValue->id}})">Delete</button>
                </div>


            </div>
        @endforeach
    </div>

    <br>

    <div class="mt-3">
        <x-microweber-ui::label for="as_price_modifier" value="Use a price modifier for value" />
        <x-microweber-ui::toggle id="as_price_modifier" class="mt-1 block w-full" wire:model="state.options.as_price_modifier" />
    </div>


</div>

<div wire:ignore>
    <script>
        window.mw.custom_fields_values_sort = function () {
            if (!mw.$("#js-sortable-items-holder-{{$this->id}}").hasClass("ui-sortable")) {
                mw.$("#js-sortable-items-holder-{{$this->id}}").sortable({
                    items: '.js-sortable-item',
                    axis: 'y',
                    handle: '.js-sort-handle',
                    update: function () {
                        setTimeout(function () {
                            var obj = {itemIds: []};
                            var sortableItems = document.querySelectorAll('#js-sortable-items-holder-{{$this->id}} .js-sortable-item');
                            sortableItems.forEach(function (item) {
                                var id = item.getAttribute('sort-key');
                                obj.itemIds.push(id);
                            });
                            window.Livewire.emit('onReorderCustomFieldValuesList', obj);
                        }, 300);
                    },
                    scroll: false
                });
            }
        }
        window.mw.custom_fields_values_sort();
    </script>

</div>
