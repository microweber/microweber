<div>

    @if($customField->type =='text')
        <div class="mt-3">
            <x-microweber-ui::label for="as_text_area" value="Use as textarea" />
            <x-microweber-ui::toggle id="as_text_area" class="mt-1 block w-full" wire:model="state.options.as_text_area" />
        </div>
    @endif

    @if($customField->type == 'checkbox' || $customField->type == 'dropdown' || $customField->type == 'radio')

        @include('custom_field::livewire.custom-field-values-multivalues')

    @elseif($customField->type == 'price')

    <div class="mt-1">
        <x-microweber-ui::label for="price" value="Price" />
        <x-microweber-ui::input-price id="price" wire:model.defer="state.value" />
    </div>

    @elseif($customField->type == 'property')
        <div class="mt-3">
            <x-microweber-ml::input-textarea label-text="Value" wire-model-name="value" wire-model-defer="1" />
        </div>
    @elseif (isset($customField->options['as_text_area']) && $customField->options['as_text_area'])

        <div>
            <div class="mt-3">
                <x-microweber-ml::input-textarea label-text="Value" wire-model-name="value" wire-model-defer="1" />
            </div>

            <div class="d-flex gap-3 mt-3">
                <div class="w-full">
                    <x-microweber-ui::label for="textarea_rows" value="Textarea Rows" />
                    <x-microweber-ui::input id="textarea_rows" class="mt-1 block w-full" wire:model="state.options.rows" />
                </div>
                <div class="w-full">
                    <x-microweber-ui::label for="textarea_cols" value="Textarea Cols" />
                    <x-microweber-ui::input id="textarea_cols" class="mt-1 block w-full" wire:model="state.options.cols" />
                </div>
            </div>
        </div>

    @else
        <div class="mt-3">
            <x-microweber-ml::input-text label-text="Value" wire-model-name="value" wire-model-defer="1" />
        </div>
    @endif


</div>
