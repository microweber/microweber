@php
$realtimeEditing = false;
if (isset($editorSettings['config']['realtimeEditing'])) {
    $realtimeEditing = $editorSettings['config']['realtimeEditing'];
}
@endphp

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

                @if ($realtimeEditing)
                    <x-microweber-ui::input wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::input wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'textarea' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::textarea wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::textarea wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'simple-text-editor' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::simple-text-editor wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::simple-text-editor wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'image' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::media-picker wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::media-picker wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'file' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::file-picker wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::file-picker wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'select' )
                @php
                    $fieldOptions = ($field['options'] ? $field['options'] : []);
                @endphp

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::select :options="$fieldOptions" wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::select  :options="$fieldOptions" wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'toggle' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::toggle wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::toggle wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'checkbox' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::checkbox wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::checkbox wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'radio' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::radio wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::radio wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'color' )

                @if ($realtimeEditing)
                    <x-microweber-ui::color-picker label="{{$field['label']}}" wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::color-picker label="{{$field['label']}}" wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'icon' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::icon-picker label="{{$field['label']}}" wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::icon-picker label="{{$field['label']}}" wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'date' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::date label="{{$field['label']}}" wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::date label="{{$field['label']}}" wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'datetime' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::datetime label="{{$field['label']}}" wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::datetime label="{{$field['label']}}" wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'range' )

                @if ($realtimeEditing)
                    <x-microweber-ui::range-slider label="{{$field['label']}}" wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::range-slider label="{{$field['label']}}" wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'time' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::time label="{{$field['label']}}" wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::time label="{{$field['label']}}" wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'url' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::input type="url" label="{{$field['label']}}" wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::input type="url" label="{{$field['label']}}" wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif


            @elseif($field['type'] == 'link-picker' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::link-picker label="{{$field['label']}}" wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::link-picker label="{{$field['label']}}" wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @elseif($field['type'] == 'info' )

                <x-microweber-ui::alert type="info"> {{ $field['help'] }} </x-microweber-ui::alert>

            @else

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if ($realtimeEditing)
                    <x-microweber-ui::input label="{{$field['label']}}" wire:model.debounce.500ms="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @else
                    <x-microweber-ui::input label="{{$field['label']}}" wire:model.defer="itemState.{{$field['name']}}" placeholder="{{ $placeholder }}" name="{{ $field['name'] }}" />
                @endif

            @endif

            @error($field['name']) <span class="text-danger">{{ $message }}</span> @enderror
            @error('itemState.'.$field['name']) <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    @endforeach
@endif
