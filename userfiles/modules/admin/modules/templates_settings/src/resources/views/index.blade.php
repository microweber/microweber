@if (!empty($templateSettings))


    @foreach($templateSettings as $formItem)

        <div wire:ignore>
            <div class="d-flex gap-2 justify-content-between">
                <label class="live-edit-label">{{$formItem['label']}} </label>
                @php
                    $attributes = [];
                    if (isset($formItem['attributes'])) {
                        $attributes = $formItem['attributes'];
                    }
                    if (isset($formItem['options'])) {
                        $attributes['dropdownOptions'] = $formItem['options'];
                    }

                    $attributes['optionKey'] = $formItem['name'];
                    $attributes['optionGroup'] = $moduleId;
                    $attributes['module'] = $moduleType;
                @endphp

                @livewire('microweber-option::'.$formItem['type'], $attributes)

            </div>
        </div>

    @endforeach

@endif
