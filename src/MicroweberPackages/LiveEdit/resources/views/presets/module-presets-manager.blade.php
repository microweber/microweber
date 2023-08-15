<div>


    <x-microweber-ui::button class="btn btn-primary-outline"
                             type="button">@lang('Use a preset')</x-microweber-ui::button>

    <br>


    <x-microweber-ui::button type="button">@lang('Save as preset')</x-microweber-ui::button>


    <div x-transition:enter-end="tab-pane-slide-left-active"
         x-transition:enter="tab-pane-slide-left-active">

        @include('microweber-live-edit::module-items-editor-list-items')

    </div>


    <div>


        <div x-transition:enter-end="tab-pane-slide-right-active"
             x-transition:enter="tab-pane-slide-right-active">

            <form wire:submit.prevent="submit">

                <div class="d-flex align-items-center justify-content-between">

                    <x-microweber-ui::button-animation type="submit">@lang('Save')</x-microweber-ui::button-animation>
                </div>


                @if (isset($editorSettings['schema']))
                    @include('microweber-live-edit::module-items-editor-edit-item-schema-render')
                @endif


                <div class="d-flex align-items-center justify-content-between">

                    <x-microweber-ui::button-animation type="submit">@lang('Save')</x-microweber-ui::button-animation>
                </div>
            </form>

        </div>

    </div>


</div>




