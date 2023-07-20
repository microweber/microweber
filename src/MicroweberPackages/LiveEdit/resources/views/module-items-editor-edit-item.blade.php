<div>


    <form wire:submit.prevent="submit">
        @if (isset($editorSettings['schema']))
            @foreach ($editorSettings['schema'] as $field)
                <div class="form-group">
                    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                    <?php $placeholder = '';

                        if(isset($field['placeholder'])){
                            $placeholder = $field['placeholder'];
                        }
                        ?>

                    <x-microweber-ui::input type="{{ $field['type'] }}"  placeholder="{{ $placeholder }}" wire:model.defer="itemState.{{ $field['name'] }}"/>
                    @error($field['name']) <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            @endforeach
        @endif

            <div class="d-flex align-items-center justify-content-between">
                <x-microweber-ui::button-animation x-on:click="showEditTab = 'main'" type="button">@lang('Cancel')</x-microweber-ui::button-animation>
                <x-microweber-ui::button-animation type="submit">@lang('Save')</x-microweber-ui::button-animation>
            </div>
    </form>



</div>
