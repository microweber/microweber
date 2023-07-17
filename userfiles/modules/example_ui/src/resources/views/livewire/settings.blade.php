<div>
    <div>
        <label class="form-label">Text</label>
        <livewire:microweber-module-option::text optionName="text" :moduleId="$moduleId" :moduleType="$moduleType"  />
    </div>
    <div class="mt-4 mb-3">
        <label class="form-label">Link</label>
        <livewire:microweber-module-option::link-picker optionName="link" :moduleId="$moduleId" :moduleType="$moduleType"  />
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
