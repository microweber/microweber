<div id="mw-options-save-<?php print md5($this->moduleId) ?>">

    @if($this->settingsForm)
        @foreach($this->settingsForm as $formItemKey => $formItem)

            <div wire:ignore>
            <div class="mt-3">
                <label class="live-edit-label">{{$formItem['label']}} </label>
                @php
                    $attributes = [];
                    if (isset($formItem['attributes'])) {
                        $attributes = $formItem['attributes'];
                    }

                    if (isset($formItem['options'])) {
                        $attributes['dropdownOptions'] = $formItem['options'];
                    }

                    $attributes['optionKey'] = $formItemKey;
                    $attributes['optionGroup'] = $this->moduleId;
                    $attributes['module'] = $this->moduleType;

                @endphp

                @livewire('microweber-option::'.$formItem['type'], $attributes)

            </div>
            </div>

        @endforeach
    @endif

</div>
