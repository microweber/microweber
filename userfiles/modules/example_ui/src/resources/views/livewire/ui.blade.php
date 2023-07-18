<div>

    <div>
        <label class="live-edit-label">Text Input</label>
        <x-microweber-ui::input />
    </div>

    <div>
        <label class="live-edit-label">Textarea</label>
        <x-microweber-ui::textarea />
    </div>

    <div>
        <label class="live-edit-label">Link Picker</label>
        <x-microweber-ui::link-picker />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Icon Picker</label>
        <x-microweber-ui::icon-picker />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Alignment</label>
        <x-microweber-ui::alignment />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Range Slider</label>
        <x-microweber-ui::range-slider />
    </div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Select</label>
        @php
            $options = [
                '1' => 'Option 1',
                '2' => 'Option 2',
                '3' => 'Option 3',
            ];
        @endphp
        <x-microweber-ui::select :options="$options" />
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
        <x-microweber-ui::radio :radioOptions="$radioOptions" />
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
        <x-microweber-ui::checkbox :checkboxOptions="$checkboxOptions" />
    </div>

    <div class="mt-4">
        <x-microweber-ui::button>
            Save
        </x-microweber-ui::button>
    </div>
</div>
