<div class="mb-3">

    <div>
        <label class="live-edit-label">Icon</label>
        <livewire:microweber-option::icon-picker optionKey="icon" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    @php
        $styleOptions = [
            'btn-primary' => 'Primary',
            'btn-secondary' => 'Secondary',
        ];
    @endphp 


    <div>
        <label class="live-edit-label">{{__('Style')}} </label>
        <livewire:microweber-option::dropdown :dropdownOptions="$styleOptions" optionKey="button_style" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    @php
        $sizeOptions = [
            '' => 'Default',
            'btn-default-large btn-lg' => 'Large',
            'btn-default-medium btn-md' => 'Medium',
            'btn-default-small btn-sm' => 'Small',
            'btn-default-mini btn-xs' => 'Mini'
        ];
    @endphp

    <div>
        <label class="live-edit-label">{{__('Size')}} </label>
        <livewire:microweber-option::dropdown :dropdownOptions="$sizeOptions" optionKey="button_size" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

</div>
