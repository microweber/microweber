<div>
<div>

    <div>
        <livewire:microweber-option::color-picker
            label="Color Picker - Custom Label"
            optionKey="color" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div class="mt-3">
        <label class="live-edit-label"><?php _e("Font Family Text"); ?></label>
        <small class="live-edit-label"><?php _e("Select font family for your logo"); ?></small>

        <livewire:microweber-option::font-picker label="Select font" optionKey="font_family" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div>
        <label class="live-edit-label">File Picker</label>
        <livewire:microweber-option::file-picker label="Select file - Custom Label" optionKey="file_picker" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div>
        <label class="live-edit-label">Media Picker</label>
        <livewire:microweber-option::media-picker label="Select media - Custom Label" optionKey="media_picker" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div>
        <label class="live-edit-label">Text</label>
        <livewire:microweber-option::text optionKey="text" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div>
        <label class="live-edit-label">TextArea</label>
        <livewire:microweber-option::textarea optionKey="textarea" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Link</label>
        <livewire:microweber-option::link-picker optionKey="link" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Icon Picker</label>
        <livewire:microweber-option::icon-picker optionKey="icon" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>


    <div class="mt-4 mb-3">
        <label class="live-edit-label">Radio Modern</label>
        @php
            $radioModernOptions = [
                '1' => 'Modern 1',
                '2' => 'Modern 2',
                '3' => 'Modern 3',
            ];
        @endphp
        <livewire:microweber-option::radio-modern :options="$radioModernOptions" optionKey="alignment" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <livewire:microweber-option::range-slider
            label="Range Slider"
            optionKey="range_slider" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div class="mt-4 mb-3">
        <livewire:microweber-option::range-slider labelUnit="px" min="8" max="45" label="Logo Text - Size" optionKey="font_size" :optionGroup="$moduleId" :module="$moduleType"  />
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
        <livewire:microweber-option::dropdown :dropdownOptions="$dropdownOptions" optionKey="dropdown" :optionGroup="$moduleId" :module="$moduleType"  />
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
        <livewire:microweber-option::radio :radioOptions="$radioOptions" optionKey="radio" :optionGroup="$moduleId" :module="$moduleType"  />
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
        <livewire:microweber-option::checkbox :checkboxOptions="$checkboxOptions" optionKey="checkbox" :optionGroup="$moduleId" :module="$moduleType"  />
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
</div>
