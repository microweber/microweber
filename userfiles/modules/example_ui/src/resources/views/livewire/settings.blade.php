<div>

    <div>
        <label class="form-label">Text</label>
        <livewire:microweber-module-option::text optionName="text" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <label class="form-label">Link</label>
        <livewire:microweber-module-option::link-picker optionName="link" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <label class="form-label">Icon Picker</label>
        <livewire:microweber-module-option::icon-picker optionName="icon" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <label class="form-label">Alignment</label>
        <livewire:microweber-module-option::alignment optionName="alignment" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <label class="form-label">Range Slider</label>
        <livewire:microweber-module-option::range-slider optionName="range_slider" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <label class="form-label">Dropdown</label>
        <livewire:microweber-module-option::dropdown dropdownOptions="{'example1': 'Example 1','example2': 'Example 2','example3': 'Example 3'}" optionName="dropdown" :moduleId="$moduleId" :moduleType="$moduleType"  />
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
