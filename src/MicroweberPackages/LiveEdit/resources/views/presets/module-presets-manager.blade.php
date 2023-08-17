<div>



    <script type="text/javascript" wire:ignore>


        const initPresetsManagerData = {
            showEditTab: 'main'
        }

        window.initPresetsManagerData = initPresetsManagerData

        mw.initPresetsManager = function () {

            window.livewire.on('switchToMainTab', () => {
                window.initPresetsManagerData.showEditTab = 'main'
            })

            window.livewire.on('editItemById', (itemId) => {
                window.initPresetsManagerData.showEditTab = 'editItemById'
                Livewire.emit('onEditItemById', itemId);
            })

            window.livewire.on('saveModuleAsPreset', function () {
                var moduleId = window.livewire.find('{{$this->id}}').get('itemState.module_id')
                // var el = mw.top().app.canvas.getWindow().$('#'+moduleId)[0];
                // var attrs = el.attributes;
                // var attrsObj = {};
                // for (var i = 0; i < attrs.length; i++) {
                //     attrsObj[attrs[i].name] = attrs[i].value;
                // }
                // mw.log(attrsObj)
                //window.livewire.find('{{$this->id}}').set('itemState.module_attrs', 'sssssssssss')
                // window.livewire.find('{{$this->id}}').set('itemState.name', 'bar')
                Livewire.emit('onSaveAsNewPreset');
            })


            window.livewire.on('showConfirmDeleteItemById', (itemId) => {
                Livewire.emit('onShowConfirmDeleteItemById', itemId);
            })
            window.livewire.on('selectPresetForModule', (itemId) => {
                alert('selectPresetForModule')
            })


        }

    </script>




    <div x-data="{
...initPresetsManagerData,
}" x-init="mw.initPresetsManager">





        <div  x-show="initPresetsManagerData.showEditTab=='main'"
             x-transition:enter-end="tab-pane-slide-left-active"
             x-transition:enter="tab-pane-slide-left-active">


            @if($isAlreadySavedAsPreset)

                <div class="alert alert-info">
                    This module is already saved as preset
                    To use the preset , place new module of type <kbd>{{ $moduleType  }}</kbd> on the page and select this
                    preset from the presets list
                </div>
            @else
                <x-microweber-ui::button class="btn btn-primary-outline" type="button"
                                         wire:click="$emit('saveModuleAsPreset')">@lang('Save as preset')</x-microweber-ui::button>
            @endif


            @include('microweber-live-edit::module-items-editor-list-items')

        </div>


        <div>


            <div

                x-show="initPresetsManagerData.showEditTab=='editItemById'"
                x-transition:enter-end="tab-pane-slide-right-active"
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




