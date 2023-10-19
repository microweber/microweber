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
                var el = mw.top().app.canvas.getWindow().$('#{{$this->moduleId}}')[0];
                var attrs = el.attributes;
                var attrsObj = {};
                var skipAttrs = ['contenteditable', 'class', 'data-original-attrs', 'data-original-id']

                for (var i = 0; i < attrs.length; i++) {
                    if (skipAttrs.includes(attrs[i].name)) {
                        continue;
                    }
                    attrsObj[attrs[i].name] = attrs[i].value;
                }

                Livewire.emit('onSaveAsNewPreset', attrsObj);
            })


            window.livewire.on('showConfirmDeleteItemById', (itemId) => {
                Livewire.emit('onShowConfirmDeleteItemById', itemId);
            })

            window.livewire.on('removeSelectedPresetForModule', (applyToModuleId) => {
                var el = mw.top().app.canvas.getWindow().$('#' + applyToModuleId)[0];
                if (el !== null) {
                     mw.top().app.registerChangedState(el);
                }



                var have_orig_attr = mw.top().app.canvas.getWindow().$(el).attr("data-module-original-attrs");


                if (have_orig_attr) {
                    var obj = JSON.parse(window.atob(have_orig_attr));
                    if (obj) {
                        for (var key in obj) {
                            var val = obj[key];
                            if (key == 'id') {
                                //   mw.top().app.canvas.getWindow().$(el).attr("data-module-id-from-preset", val);
                            } else {
                                mw.top().app.canvas.getWindow().$(el).attr(key, val);
                            }
                        }
                    }

                }

                mw.top().app.canvas.getWindow().$(el).removeAttr("data-module-id-from-preset");
                mw.top().app.canvas.getWindow().$(el).removeAttr("data-module-original-attrs");
                mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': applyToModuleId}))


            })


            window.livewire.on('applyPreset', (applyToModuleId, preset) => {

                var json = preset.module_attrs;
                var obj = JSON.parse(json);


                var el = mw.top().app.canvas.getWindow().$('#' + applyToModuleId)[0];
                if (el == null) {
                    //      var el = mw.top().app.canvas.getWindow().$('[data-module-original-id="' + applyToModuleId+'"]')[0];
                }
                if (el !== null) {
                    mw.top().app.registerChangedState(el);
                }


                var orig_id = mw.top().app.canvas.getWindow().$(el).attr("id");
                // var set_orig_id = mw.top().app.canvas.getWindow().$(el).attr("data-module-id-from-preset");
                // var have_orig_id = mw.top().app.canvas.getWindow().$(el).attr("data-module-original-id");
                var have_orig_attr = mw.top().app.canvas.getWindow().$(el).attr("data-module-original-attrs");
                //
                if(!have_orig_attr) {
                    var attrsEl = mw.top().tools.getAttrs(el);
                 //   var orig_attrs_encoded = window.btoa(JSON.stringify(obj));
                    var orig_attrs_encoded = window.btoa(JSON.stringify(attrsEl));
                    if (orig_attrs_encoded) {
                        mw.top().app.canvas.getWindow().$(el).attr("data-module-original-attrs", orig_attrs_encoded);
                    }
                }
                //
                // mw.top().app.canvas.getWindow().$(el).attr("data-module-original-id", orig_id);
                //
                if (obj) {
                    for (var key in obj) {
                        var val = obj[key];
                        if (key == 'id') {
                         //   mw.top().app.canvas.getWindow().$(el).attr("data-module-id-from-preset", val);
                        } else {
                            mw.top().app.canvas.getWindow().$(el).attr(key, val);
                        }
                    }
                }
                mw.top().app.canvas.getWindow().$(el).attr("data-module-id-from-preset", preset.module_id);

                //   mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': preset.module_id}))
                mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': applyToModuleId}))

            });

        }

    </script>


    <div x-data="{
...initPresetsManagerData,
}" x-init="mw.initPresetsManager">


        <div x-show="initPresetsManagerData.showEditTab=='main'"
             x-transition:enter-end="tab-pane-slide-left-active"
             x-transition:enter="tab-pane-slide-left-active">


            @if($isAlreadySavedAsPreset)

                <div class="alert alert-info">
                    This module is already saved as preset
                    To use the preset , place new module of type <kbd>{{ $moduleType  }}</kbd> on the page and select
                    this
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




