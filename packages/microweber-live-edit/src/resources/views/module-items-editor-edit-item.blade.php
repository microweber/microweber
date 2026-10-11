<div>


    <?php

    $backButtonIconSvg = '';
    if (isset($editorSettings['config']['backButtonIconSvg'])) {
        $backButtonIconSvg = $editorSettings['config']['backButtonIconSvg'];
    }
    $saveButtonIconSvg = '';
    if (isset($editorSettings['config']['saveButtonIconSvg'])) {
        $saveButtonIconSvg = $editorSettings['config']['saveButtonIconSvg'];
    }

    ?>


    <script>
        @php
        $formRandId = time() . rand(1111,9999);
        @endphp
        mw.require('forms.js', true);
        setTimeout(function() {
            mw.form.unsavedChangesCheck('js-module-items-editor-edit-item-form{{$formRandId}}');
        }, 300);
    </script>

    <form  id="js-module-items-editor-edit-item-form{{$formRandId}}" wire:submit.prevent="submit">

        <div class="d-flex align-items-center justify-content-between">

            <x-microweber-ui::button-back x-on:click="showEditTab = 'main'">
                {!!$backButtonIconSvg!!}
            </x-microweber-ui::button-back>

            <x-microweber-ui::button-animation type="submit">
                {!!$saveButtonIconSvg!!}
                @lang('Save')
            </x-microweber-ui::button-animation>

        </div>


        @if (isset($editorSettings['schema']))
            @include('microweber-live-edit::module-items-editor-edit-item-schema-render')
        @endif

        <div class="d-flex align-items-center justify-content-between">
            <x-microweber-ui::button-animation x-on:click="showEditTab = 'main'"
                                               type="button">@lang('Cancel')</x-microweber-ui::button-animation>
            <x-microweber-ui::button-animation type="submit">
                {!!$saveButtonIconSvg!!}
                @lang('Save')
            </x-microweber-ui::button-animation>
        </div>
    </form>


</div>
