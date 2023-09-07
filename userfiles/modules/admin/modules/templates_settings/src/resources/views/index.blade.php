@if (!empty($templateSettings))

    @php
        $templateSettingsJson = [];
        foreach ($templateSettings as $formItem) {
            if (!isset($formItem['default'])) {
                $formItem['default'] = false;
            }
            $templateSettingsJson[$formItem['name']] = $formItem['default'];
        }
    @endphp

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('templateSettings', @json($templateSettingsJson));
        });
        document.addEventListener('mw-option-saved', ($event) => {
            let newSettings = Alpine.store('templateSettings');
            delete newSettings[$event.detail.optionKey];
            newSettings[$event.detail.optionKey] = $event.detail.optionValue;
            Alpine.store('templateSettings', newSettings);
        });
    </script>

    <div x-data="{}">

    @foreach($templateSettings as $formItem)

        <div @if(isset($formItem['show_when'])) x-show="$store.templateSettings.{{$formItem['show_when']}}" @endif >
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
