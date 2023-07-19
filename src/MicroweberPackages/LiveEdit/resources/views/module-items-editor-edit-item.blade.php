<div>


    <form wire:submit.prevent="submit">
        @if (isset($editorSettings['schema']))
            @foreach ($editorSettings['schema'] as $field)
                <div class="form-group">
                    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                    <x-microweber-ui::input type="{{ $field['type'] }}" placeholder="{{ $field['placeholder'] }}" wire:model.defer="itemState.{{ $field['name'] }}"/>
                    @error($field['name']) <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            @endforeach
        @endif

        <button x-on:click="showEditTab = 'main'" class="btn btn-outline-secondary" type="button">@lang('Cancel')</button>
        <button type="submit" class="btn btn-primary">@lang('Save')</button>
    </form>



</div>
