<div>

    <script type="text/javascript" wire:ignore>
        // Simplified JavaScript for preset manager
        document.addEventListener('DOMContentLoaded', function () {
            // Save module as preset
            window.saveModuleAsPreset = function () {
                var el = mw.top().app.canvas.getWindow().$('#{{$this->moduleId}}')[0];
                if (!el) {
                    el = mw.top().app.canvas.getWindow().$('[data-module-original-id="{{$this->moduleId}}"]')[0];
                }
                if (!el) {
                    el = mw.top().app.canvas.getWindow().$('[data-module-id-from-preset="{{$this->moduleId}}"]')[0];
                }
                var attrs = el.attributes;
                var attrsObj = {};
                var skipAttrs = ['contenteditable', 'class', 'data-original-attrs', 'data-original-id']

                for (var i = 0; i < attrs.length; i++) {
                    if (skipAttrs.includes(attrs[i].name)) {
                        continue;
                    }
                    attrsObj[attrs[i].name] = attrs[i].value;
                }

                Livewire.dispatch('onSaveAsNewPreset', attrsObj);
            };

            // Listen for applyPreset event
            window.addEventListener('applyPreset', function (event) {


                var applyToModuleId = event.detail.moduleId;
                var preset = event.detail.preset;


                var el = mw.top().app.canvas.getWindow().$('#' + applyToModuleId)[0];
                if (el !== null) {
                    mw.top().app.registerChangedState(el);
                }

                var orig_id = mw.top().app.canvas.getWindow().$(el).attr("id");
                var have_orig_id = mw.top().app.canvas.getWindow().$(el).attr("data-module-original-id");
                var have_orig_attr = mw.top().app.canvas.getWindow().$(el).attr("data-module-original-attrs");

                if (!have_orig_attr) {
                    var attrsEl = mw.top().tools.getAttrs(el);
                    var orig_attrs_encoded = window.btoa(JSON.stringify(attrsEl));
                    if (orig_attrs_encoded) {
                        mw.top().app.canvas.getWindow().$(el).attr("data-module-original-attrs", orig_attrs_encoded);
                    }
                }

                mw.top().app.canvas.getWindow().$(el).attr("data-module-id-from-preset", preset.module_id);
                if (!have_orig_id) {
                    mw.top().app.canvas.getWindow().$(el).attr("data-module-original-id", applyToModuleId);
                }

                mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': applyToModuleId}));
            });

            // Listen for removeSelectedPresetForModule event
            window.addEventListener('removeSelectedPresetForModule', function (event) {
                var applyToModuleId = event.detail.moduleId;


                var el = mw.top().app.canvas.getWindow().$('#' + applyToModuleId)[0];
                if (el !== null) {
                    mw.top().app.registerChangedState(el);
                }

                var have_orig_attr = mw.top().app.canvas.getWindow().$(el).attr("data-module-original-attrs");
                var have_orig_id = mw.top().app.canvas.getWindow().$(el).attr("data-module-original-id");

                if (have_orig_attr) {
                    var obj = JSON.parse(window.atob(have_orig_attr));
                    if (obj) {
                        for (var key in obj) {
                            var val = obj[key];
                            if (key != 'id') {
                                mw.top().app.canvas.getWindow().$(el).attr(key, val);
                            }
                        }
                    }
                }

                if (have_orig_id) {
                    mw.top().app.canvas.getWindow().$(el).attr("id", have_orig_id);
                    applyToModuleId = have_orig_id;
                }

                mw.top().app.canvas.getWindow().$(el).removeAttr("data-module-original-id");
                mw.top().app.canvas.getWindow().$(el).removeAttr("data-module-id-from-preset");
                mw.top().app.canvas.getWindow().$(el).removeAttr("data-module-original-attrs");
                mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': applyToModuleId}));
            });

            // Remove selected preset
            window.removeSelectedPresetForModule = function (applyToModuleId) {
                Livewire.dispatch('onRemoveSelectedPresetForModule', {moduleId: applyToModuleId});
            };

            // Apply preset
            window.selectPresetForModule = function (presetId, moduleId) {
                Livewire.dispatch('onSelectPresetForModule', {id: presetId});
            };

            // Delete preset
            window.confirmDeletePreset = function (itemId) {
                Livewire.dispatch('onShowConfirmDeleteItemById', {itemId: itemId});
            };

            // Edit preset
            window.editPreset = function (itemId) {
                Livewire.dispatch('onEditItemById', {id: itemId});
            };
        });
    </script>

    <!-- Main View -->
    <div class="preset-manager-container">
        <!-- Save as preset button -->
        @if($isAlreadySavedAsPreset)
            <div class="alert alert-info mb-4">
                This module is already saved as preset.
                To use the preset, place new module of type <kbd>{{ $moduleType }}</kbd> on the page and select this
                preset from the list.
            </div>
        @else
            <button class="btn btn-primary mb-4" type="button" onclick="saveModuleAsPreset()">
                Save as preset
            </button>
        @endif

        <!-- Presets List -->
        <div class="presets-list mb-4">
            <h5>Available Presets</h5>

            @if(is_array($items) && count($items) > 0)
                <div class="list-group">
                    @foreach($items as $item)
                        @php
                            $itemId = isset($item['id']) ? $item['id'] : false;
                            if(!$itemId) continue;
                        @endphp

                        <div class="list-group-item d-flex justify-content-between align-items-center p-3 mb-2">
                            <div class="preset-info" onclick="editPreset('{{ $itemId }}')">
                                <strong>{{ $item['name'] ?? 'Unnamed Preset' }}</strong>
                            </div>

                            <div class="preset-actions d-flex gap-2">
                                <!-- Delete button -->
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDeletePreset('{{ $itemId }}')">
                                    Delete
                                </button>

                                <!-- Edit button -->
                                <button class="btn btn-sm btn-outline-primary" onclick="editPreset('{{ $itemId }}')">
                                    Edit
                                </button>

                                <!-- Use preset button -->
                                @if($moduleIdFromPreset == $item['module_id'])
                                    <span class="badge bg-warning">Current preset</span>
                                    <button class="btn btn-sm btn-warning"
                                            onclick="removeSelectedPresetForModule('{{ $moduleId }}')">
                                        Clear preset
                                    </button>
                                @elseif($moduleId == $item['module_id'])
                                    <span class="badge bg-success">Current module</span>
                                @else
                                    <button class="btn btn-sm btn-primary"
                                            onclick="selectPresetForModule('{{ $itemId }}', '{{ $moduleId }}')">
                                        Use preset
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-light">
                    No presets available. Create your first preset by clicking the "Save as preset" button.
                </div>
            @endif
        </div>

        <!-- Edit Preset Form -->
        @if(isset($itemState['id']))
            <div class="edit-preset-form mt-4 p-4 border rounded">
                <h5>Edit Preset</h5>
                <form wire:submit.prevent="submit">
                    <div class="mb-3">
                        <label for="preset-name" class="form-label">Preset Name</label>
                        <input type="text" class="form-control" id="preset-name" wire:model.defer="itemState.name"
                               required>
                        @error('itemState.name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" wire:model.defer="itemState.module">
                    <input type="hidden" wire:model.defer="itemState.module_id">
                    <input type="hidden" wire:model.defer="itemState.position">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-outline-secondary"
                                wire:click="$dispatch('switchToMainTab')">Cancel
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div>
        <x-microweber-ui::dialog-modal wire:model.live="areYouSureDeleteModalOpened">
            <x-slot name="title">
                    <?php _e('Are you sure?'); ?>
            </x-slot>
            <x-slot name="content">
                    <?php _e('Are you sure you want to delete this preset?'); ?>
            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-outline-secondary" wire:click="$set('areYouSureDeleteModalOpened', false)">
                        <?php _e('Cancel'); ?>
                </button>
                <button class="btn btn-danger" wire:click="confirmDeleteSelectedItems">
                        <?php _e('Delete'); ?>
                </button>
            </x-slot>
        </x-microweber-ui::dialog-modal>
    </div>
</div>
