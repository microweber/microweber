<div>
    <label class="live-edit-label">{{__('Layout')}} </label>
    @php
        $layoutOptions = [
            'standard' => 'Standard',
            'button_count' => 'Button count',
            'button' => 'Button',
            'box_count' => 'Box count',
        ];
    @endphp
    <livewire:microweber-option::dropdown :dropdownOptions="$layoutOptions" optionKey="layout" :optionGroup="$moduleId" :module="$moduleType"  />
</div>

<div class="mt-3">
    <label class="live-edit-label">{{__('Color scheme')}} </label>
    @php
        $colorSchemeOptions = [
            'light' => 'Light',
            'dark' => 'Dark',
        ];
    @endphp
    <livewire:microweber-option::dropdown :dropdownOptions="$colorSchemeOptions" optionKey="color" :optionGroup="$moduleId" :module="$moduleType"  />
</div>

<div class="mt-3">
    <label class="live-edit-label">{{__('Show faces')}} </label>
    <livewire:microweber-option::toggle optionKey="show_faces" :optionGroup="$moduleId" :module="$moduleType"  />
</div>

<div class="mt-3">
    <label class="live-edit-label">{{__('Custom URL')}} </label>
    <livewire:microweber-option::text optionKey="url" :optionGroup="$moduleId" :module="$moduleType"  />
<!--    <span>
        {{_e('If you fill this field the current link will be liked')}}
    </span>-->
</div>

