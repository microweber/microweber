@if (isset($editorSettings['schema']))
    @foreach ($editorSettings['schema'] as $field)
        @php
            $placeholder = '';

            if(isset($field['placeholder'])){
                $placeholder = $field['placeholder'];
            }

            $hidden  = '';

            if(isset($field['hidden'])){
                $hidden = 'd-none';
            }
        @endphp

        <div class="form-group {{ $hidden }}">

            @if($field['type'] == 'text' )
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::input placeholder="{{ $placeholder }}" name="{{ $field['name'] }}"  wire:model.defer="itemState.{{ $field['name'] }}" />
            @elseif($field['type'] == 'textarea' )
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::textarea placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'simple-text-editor' )
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::simple-text-editor placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'image' )
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::media-picker wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'file' )
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::file-picker wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'select' )
                @php
                    $fieldOptions = ($field['options'] ? $field['options'] : []);
                @endphp
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::select :options="$fieldOptions" wire:model.defer="itemState.{{ $field['name'] }}" />
            @elseif($field['type'] == 'checkbox' )
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::checkbox wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'radio' )
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::radio wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'color' )
                <x-microweber-ui::color-picker label="{{$field['label']}}" wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'icon' )
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::icon-picker wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'date' )
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::date wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'datetime' )
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::datetime wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'range' )
                <x-microweber-ui::range-slider label="{{$field['label']}}" wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'time' )
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::time wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'url' )
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::link-picker wire:model.defer="itemState.{{ $field['name'] }}"/>
            @elseif($field['type'] == 'info' )
                <x-microweber-ui::alert type="info"> {{ $field['help'] }} </x-microweber-ui::alert>
            @else
                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <x-microweber-ui::input placeholder="{{ $placeholder }}" wire:model.defer="itemState.{{ $field['name'] }}"/>
            @endif

            @error($field['name']) <span class="text-danger">{{ $message }}</span> @enderror
            @error('itemState.'.$field['name']) <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    @endforeach
@endif
