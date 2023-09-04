@if (!empty($templateSettings))

    @php
        $templateSettingsJson = [];
        foreach ($templateSettings as $formItem) {
            if (!isset($formItem['default'])) {
                $formItem['default'] = false;
            }
            $templateSettingsJson[$formItem['name']] = $formItem['default'];
        }
        $templateSettingsJson = json_encode($templateSettingsJson);
    @endphp

    <div
        x-data="{{$templateSettingsJson}}"
    >
        
    @foreach($templateSettings as $formItem)

        <div @if(isset($formItem['show_when'])) x-show="{{$formItem['show_when']}}" @endif >
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

    </div>

@endif
