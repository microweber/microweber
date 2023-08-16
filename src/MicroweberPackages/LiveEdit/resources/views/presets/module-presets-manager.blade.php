<div>

    <div x-data="{
showEditTab: 'main'
}" x-init="() => {
    window.livewire.on('switchToMainTab', () => {
        showEditTab = 'main'
    })

     window.livewire.on('editItemById' , (itemId) => {

        showEditTab = 'tabs-nav-tab-' +  itemId
    })


      window.livewire.on('showConfirmDeleteItemById' , (itemId) => {

        Livewire.emit('onShowConfirmDeleteItemById', itemId);
    })
}">
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

                        <x-microweber-ui::button-animation
                            type="submit">@lang('Save')</x-microweber-ui::button-animation>
                    </div>


                    @if (isset($editorSettings['schema']))
                        @include('microweber-live-edit::module-items-editor-edit-item-schema-render')
                    @endif


                    <div class="d-flex align-items-center justify-content-between">

                        <x-microweber-ui::button-animation
                            type="submit">@lang('Save')</x-microweber-ui::button-animation>
                    </div>
                </form>

            </div>

        </div>


    </div>

    <div>
        <x-microweber-ui::dialog-modal wire:model="areYouSureDeleteModalOpened">
            <x-slot name="title">
                <?php _e('Are you sure?'); ?>
            </x-slot>
            <x-slot name="content">
                <?php _e('Are you sure want to delete this?'); ?>
            </x-slot>

            <x-slot name="footer">
                <x-microweber-ui::button-animation wire:click="$toggle('areYouSureDeleteModalOpened')"
                                                   wire:loading.attr="disabled">
                    <?php _e('Cancel'); ?>
                </x-microweber-ui::button-animation>
                <x-microweber-ui::button-animation class="text-danger" wire:click="confirmDeleteSelectedItems()"
                                                   wire:loading.attr="disabled">
                    <?php _e('Delete'); ?>
                </x-microweber-ui::button-animation>
            </x-slot>
        </x-microweber-ui::dialog-modal>
    </div>
</div>




