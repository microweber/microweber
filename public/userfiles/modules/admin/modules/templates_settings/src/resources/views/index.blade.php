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

        @php
        $showWhen = false;
        if(isset($formItem['show_when']) and !empty($formItem['show_when'])) {
            $showWhenArray = [];
            if(is_array($formItem['show_when'])) {
                foreach ($formItem['show_when'] as $showWhen) {
                $showWhenArray[] = '$store.templateSettings.' . $showWhen;
                }
            } else {
                $showWhenArray[] = '$store.templateSettings.' . $formItem['show_when'];
            }
            if($showWhenArray){
            $showWhen = implode(' && ',  $showWhenArray);
            }
        }
        @endphp

            <div  @if($showWhen) x-show="{{$showWhen}}" @endif>

            <div @if(isset($formItem['label'])) class="d-flex gap-2 justify-content-between" @else class="mb-3"  @endif>

                @if(isset($formItem['label']))
                <label class="live-edit-label">{{$formItem['label']}} </label>
                @endif

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
