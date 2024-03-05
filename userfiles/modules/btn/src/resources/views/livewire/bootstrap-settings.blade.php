<div class="mb-3">

    <div>
        <label class="live-edit-label">{{__('Button Icon')}}</label>
        <livewire:microweber-option::icon-picker optionKey="icon" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    @php
        $iconPositionOptions = [
            'left' => '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIgMlYyMEg0TDQgMkgyWiIgZmlsbD0iIzBFMEUwRSIvPgo8cGF0aCBkPSJNMTYgMTJIOS4zMjgzOEwxMi4zMjg0IDE1SDkuNDk5OTVMNS41IDExTDkuNTAwMDUgN0wxMi4zMjg1IDdMOS4zMjg0NyAxMEwxNiAxMFYxMloiIGZpbGw9IiMwRTBFMEUiLz4KPC9zdmc+Cg==" style="width: 22px; height: 22px;">',
            'right' => '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIwIDIwTDIwIDJIMThMMTggMjBIMjBaIiBmaWxsPSIjMEUwRTBFIi8+CjxwYXRoIGQ9Ik02IDEwSDEyLjY3MTZMOS42NzE2MiA3TDEyLjUgN0wxNi41IDExTDEyLjUgMTVIOS42NzE1M0wxMi42NzE1IDEySDZWMTBaIiBmaWxsPSIjMEUwRTBFIi8+Cjwvc3ZnPgo=" style="width: 22px; height: 22px;">',
        ];
    @endphp

    <div>
        <label class="live-edit-label">{{__('Button Icon Position')}} </label>
        <livewire:microweber-option::radio-modern :options="$iconPositionOptions" optionKey="icon_position" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    @php
        $styleOptions = [
            'btn' => 'Normal',
            'btn-primary' => 'Primary',
            'btn-secondary' => 'Secondary',
            'btn-outline' => 'Outline',
            'btn-link' => 'Link',
        ];
    @endphp


    <div>
        <label class="live-edit-label">{{__('Button Style')}} </label>
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
        <label class="live-edit-label">{{__('Button Size')}} </label>
        <livewire:microweber-option::dropdown :dropdownOptions="$sizeOptions" optionKey="button_size" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

</div>
