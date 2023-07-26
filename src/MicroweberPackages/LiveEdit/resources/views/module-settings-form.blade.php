<div id="mw-options-save-<?php print md5($this->moduleId) ?>">

    @php
        /**
         * @var \MicroweberPackages\FormBuilder\FormElementBuilder $formBuilder
         */
        $formBuilder = app()->make(\MicroweberPackages\FormBuilder\FormElementBuilder::class);
    @endphp

    @if($this->settingsForm)
        @foreach($this->settingsForm as $formItemKey => $formItem)

            <div wire:ignore>
                <label class="live-edit-label">{{$formItem['label']}} </label>
                @php
                    $attributes = [];
                    if (isset($formItem['attributes'])) {
                        $attributes = $formItem['attributes'];
                    }

                    if (isset($formItem['options'])) {
                        $attributes['dropdownOptions'] = $formItem['options'];
                    }
                @endphp
                @livewire('microweber-option::'.$formItem['type'], $attributes)
            </div>

        @endforeach
    @endif

</div>
