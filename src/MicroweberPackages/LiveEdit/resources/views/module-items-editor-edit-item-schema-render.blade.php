@php
$realtimeEditing = true;
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

                <x-microweber-ui::input placeholder="{{ $placeholder }}" name="{{ $field['name'] }}"
                        @if ($realtimeEditing)
                        wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                        @else
                        wire:model.defer="itemState.{{ $field['name'] }}"
                        @endif
                />

            @elseif($field['type'] == 'textarea' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::textarea placeholder="{{ $placeholder }}" name="{{ $field['name'] }}"
                   @if ($realtimeEditing)
                   wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                   @else
                   wire:model.defer="itemState.{{ $field['name'] }}"
                   @endif
                />

            @elseif($field['type'] == 'simple-text-editor' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::simple-text-editor placeholder="{{ $placeholder }}" name="{{ $field['name'] }}"
                     @if ($realtimeEditing)
                     wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                     @else
                     wire:model.defer="itemState.{{ $field['name'] }}"
                    @endif
                 />

            @elseif($field['type'] == 'image' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::media-picker

                    @if ($realtimeEditing)
                    wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                    @else
                    wire:model.defer="itemState.{{ $field['name'] }}"
                    @endif

                />

            @elseif($field['type'] == 'file' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::file-picker

                    @if ($realtimeEditing)
                    wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                    @else
                    wire:model.defer="itemState.{{ $field['name'] }}"
                    @endif

                />

            @elseif($field['type'] == 'select' )
                @php
                    $fieldOptions = ($field['options'] ? $field['options'] : []);
                @endphp

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::select :options="$fieldOptions"

                     @if ($realtimeEditing)
                     wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                     @else
                     wire:model.defer="itemState.{{ $field['name'] }}"
                    @endif
                />

            @elseif($field['type'] == 'checkbox' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::checkbox
                    @if ($realtimeEditing)
                    wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                    @else
                    wire:model.defer="itemState.{{ $field['name'] }}"
                    @endif
                />

            @elseif($field['type'] == 'radio' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::radio
                    @if ($realtimeEditing)
                    wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                    @else
                    wire:model.defer="itemState.{{ $field['name'] }}"
                    @endif
                />

            @elseif($field['type'] == 'color' )

                <x-microweber-ui::color-picker label="{{$field['label']}}"
                   @if ($realtimeEditing)
                   wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                   @else
                   wire:model.defer="itemState.{{ $field['name'] }}"
                   @endif
                />

            @elseif($field['type'] == 'icon' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::icon-picker
                    @if ($realtimeEditing)
                    wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                    @else
                    wire:model.defer="itemState.{{ $field['name'] }}"
                    @endif
                />

            @elseif($field['type'] == 'date' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::date

                    @if ($realtimeEditing)
                    wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                    @else
                    wire:model.defer="itemState.{{ $field['name'] }}"
                    @endif

                />

            @elseif($field['type'] == 'datetime' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::datetime

                    @if ($realtimeEditing)
                    wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                    @else
                    wire:model.defer="itemState.{{ $field['name'] }}"
                    @endif
                />

            @elseif($field['type'] == 'range' )

                <x-microweber-ui::range-slider label="{{$field['label']}}"

                   @if ($realtimeEditing)
                   wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                   @else
                   wire:model.defer="itemState.{{ $field['name'] }}"
                   @endif
                />

            @elseif($field['type'] == 'time' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::time
                    @if ($realtimeEditing)
                    wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                    @else
                    wire:model.defer="itemState.{{ $field['name'] }}"
                    @endif
                />

            @elseif($field['type'] == 'url' )

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::link-picker
                    @if ($realtimeEditing)
                    wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                    @else
                    wire:model.defer="itemState.{{ $field['name'] }}"
                    @endif
                />

            @elseif($field['type'] == 'info' )

                <x-microweber-ui::alert type="info"> {{ $field['help'] }} </x-microweber-ui::alert>

            @else

                <label class="live-edit-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                <x-microweber-ui::input placeholder="{{ $placeholder }}"

                    @if ($realtimeEditing)
                    wire:model.debounce.500ms="itemState.{{ $field['name'] }}"
                    @else
                    wire:model.defer="itemState.{{ $field['name'] }}"
                    @endif

                />

            @endif

            @error($field['name']) <span class="text-danger">{{ $message }}</span> @enderror
            @error('itemState.'.$field['name']) <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    @endforeach
@endif
