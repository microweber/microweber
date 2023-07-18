<div style="padding:150px;">

    <div>
        <label class="live-edit-label">Text</label>
        <livewire:microweber-module-option::text optionName="text" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>

    <div>
        <label class="live-edit-label">TextArea</label>
        <livewire:microweber-module-option::textarea optionName="textarea" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Link</label>
        <livewire:microweber-module-option::link-picker optionName="link" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Icon Picker</label>
        <livewire:microweber-module-option::icon-picker optionName="icon" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Alignment</label>
        <livewire:microweber-module-option::alignment optionName="alignment" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Range Slider</label>
        <livewire:microweber-module-option::range-slider optionName="range_slider" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Dropdown</label>
        @php
            $dropdownOptions = [
                '1' => 'Option 1',
                '2' => 'Option 2',
                '3' => 'Option 3',
            ];
            @endphp
            <livewire:microweber-module-option::dropdown :dropdownOptions="$dropdownOptions" optionName="dropdown" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>


    <div class="mt-4 mb-3">
        <label class="live-edit-label">Radio</label>
        @php
            $radioOptions = [
                '1' => 'Radio 1',
                '2' => 'Radio 2',
                '3' => 'Radio 3',
            ];
        @endphp
        <livewire:microweber-module-option::radio :radioOptions="$radioOptions" optionName="radio" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>



    <div class="mt-4 mb-3">
        <label class="live-edit-label">Checkbox</label>
        @php
            $checkboxOptions = [
                '1' => 'Checkbox 1',
                '2' => 'Checkbox 2',
                '3' => 'Checkbox 3',
            ];
        @endphp
        <livewire:microweber-module-option::checkbox :checkboxOptions="$checkboxOptions" optionName="checkbox" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>



    <div>
        @php
              $keyValueOptions = [];
              $options = app()->option_repository->getOptionsByGroup($moduleId);
              if (!empty($options)) {
                foreach ($options as $option) {
                    $keyValueOptions[$option['option_key']] = $option['option_value'];
                }
            }
        @endphp

        <pre>
        {!! json_encode($keyValueOptions, JSON_PRETTY_PRINT) !!}
        </pre>
    </div>
</div>
