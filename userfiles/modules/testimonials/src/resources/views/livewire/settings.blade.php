<div>
    <div

    x-data="{
        showEditTab: 'testimonials'
    }"

     x-init="() => {
        window.livewire.on('switchToMainTab', () => {
            showEditTab = 'main'
        })

         window.livewire.on('editItemById' , (itemId) => {
            showEditTab = 'editTestimonial'
        })

        window.livewire.on('showConfirmDeleteItemById', (itemId) => {
            Livewire.emit('onShowConfirmDeleteItemById', itemId);
        })}"

    >

        <div class="d-flex justify-content-between align-items-center collapseNav-initialized form-control-live-edit-label-wrapper">
            <div class="d-flex flex-wrap gap-md-4 gap-3">
                <a href="#" x-on:click="showEditTab = 'testimonials'" :class="{ 'active': showEditTab == 'testimonials' }"
                   class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                    Testimonials
                </a>
                <a href="#" x-on:click="showEditTab = 'settings'" :class="{ 'active': showEditTab == 'settings' }"
                   class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                    Settings
                </a>
            </div>
        </div>

        <div x-show="showEditTab=='testimonials'">

            <div class="mt-2">
                <x-microweber-ui::button-animation type="button" class="mt-2" x-on:click="showEditTab = 'addNewTestimonial'">
                    @lang('Add new testimonial')
                </x-microweber-ui::button-animation>
            </div>

            @include('microweber-live-edit::module-items-editor-list-items')

        </div>

        <div x-show="showEditTab=='editTestimonial'">
            <form wire:submit.prevent="submit">

                <div class="d-flex align-items-center justify-content-end">
                    <x-microweber-ui::button-animation type="submit">
                        @lang('Save')
                    </x-microweber-ui::button-animation>
                </div>

                @if (isset($editorSettings['schema']))
                    @include('microweber-live-edit::module-items-editor-edit-item-schema-render')
                @endif

            </form>
        </div>

        <div x-show="showEditTab=='settings'">
            This is the settings tab
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

</div>
