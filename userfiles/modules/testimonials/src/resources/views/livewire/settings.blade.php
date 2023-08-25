<div>
    <div x-data="{
    showEditTab: 'testimonials'
    }"

         x-init="() => {
    window.livewire.on('switchToMainTab', () => {
        showEditTab = 'main'
    })

     window.livewire.on('editItemById' , (itemId) => {
        showEditTab = 'editTestimonial'
    })

}"

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

    </div>

</div>
