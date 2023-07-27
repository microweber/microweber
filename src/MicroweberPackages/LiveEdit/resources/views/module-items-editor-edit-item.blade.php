<div>


    <?php

    $backButtonIconSvg = '';
    if (isset($editorSettings['config']['backButtonIconSvg'])) {
        $backButtonIconSvg = $editorSettings['config']['backButtonIconSvg'];
    }
    ?>

    <form wire:submit.prevent="submit">

        <div class="d-flex align-items-center justify-content-between">

            <x-microweber-ui::button-back x-on:click="showEditTab = 'main'">
                {!!$backButtonIconSvg!!}
            </x-microweber-ui::button-back>

        </div>


        @if (isset($editorSettings['schema']))
            @include('content::admin.content.livewire.form-builder.schema-render')
        @endif

        <div class="d-flex align-items-center justify-content-between">
            <x-microweber-ui::button-animation x-on:click="showEditTab = 'main'"
                                               type="button">@lang('Cancel')</x-microweber-ui::button-animation>
            <x-microweber-ui::button-animation type="submit">@lang('Save')</x-microweber-ui::button-animation>
        </div>
    </form>


</div>
