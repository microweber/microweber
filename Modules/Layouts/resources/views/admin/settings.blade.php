<div>
    <div
        x-ignore
        ax-load
        ax-load-src="{{ asset('modules/layouts/js/layouts-module-settings.js') }}"
        x-data="layoutSettings('image', '{{ $optionGroup }}')"
    >
        <div id="mw-layout-setting-module" wire:ignore>

            <div x-show="supports.length > 0">
                <style>
                    .change-layout-background-wrapper span {
                        font-size: 12px;
                    }

                    .change-layout-background-wrapper {
                        max-width: 90%;
                    }
                </style>

                <div>
                    <div class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper">
                        <span x-show="supports.includes('image')" x-on:click="activeTab = 'image'"
                              x-bind:class="{ 'active': activeTab === 'image' }"
                              class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Image</span>
                        <span x-show="supports.includes('video')" x-on:click="activeTab = 'video'"
                              x-bind:class="{ 'active': activeTab === 'video' }"
                              class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Video</span>
                        <span x-show="supports.includes('color')" x-on:click="activeTab = 'color'"
                              x-bind:class="{ 'active': activeTab === 'color' }"
                              class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Color</span>
                        <span x-show="supports.includes('other')" x-on:click="activeTab = 'other'"
                              x-bind:class="{ 'active': activeTab === 'other' }"
                              class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100"
                              style="display: none;">Other</span>
                    </div>
                    <br>

                    <div x-show="supports.includes('image') && activeTab === 'image'" class="bg-tab">
                        <div id="bg--image-picker"></div>
                        <br>
                        <div class="change-layout-background-wrapper">
                            <label class="live-edit-label">Image size</label>
                            <div
                                class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper mx-0">
                                <label class="form-selectgroup-item w-100">
                                    <input type="radio" name="backgroundSize" value="auto"
                                           class="form-selectgroup-input" x-model="backgroundSize"
                                           x-bind:checked="backgroundSize === 'auto'"
                                           x-bind:class="{ 'active': backgroundSize === 'auto' }"/>
                                    <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100"
                                          x-bind:class="{ 'active': backgroundSize === 'auto' }">Auto</span>
                                </label>
                                <label class="form-selectgroup-item w-100">
                                    <input type="radio" name="backgroundSize" value="cover"
                                           class="form-selectgroup-input" x-model="backgroundSize"
                                           x-bind:checked="backgroundSize === 'cover'"
                                           x-bind:class="{ 'active': backgroundSize === 'cover' }"/>
                                    <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100"
                                          x-bind:class="{ 'active': backgroundSize === 'cover' }">Cover</span>
                                </label>
                                <label class="form-selectgroup-item w-100">
                                    <input type="radio" name="backgroundSize" value="contain"
                                           class="form-selectgroup-input" x-model="backgroundSize"
                                           x-bind:checked="backgroundSize === 'contain'"
                                           x-bind:class="{ 'active': backgroundSize === 'contain' }"/>
                                    <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100"
                                          x-bind:class="{ 'active': backgroundSize === 'contain' }">Fit</span>
                                </label>
                                <label class="form-selectgroup-item w-100">
                                    <input type="radio" name="backgroundSize" value="100% 100%"
                                           class="form-selectgroup-input" x-model="backgroundSize"
                                           x-bind:checked="backgroundSize === '100% 100%'"
                                           x-bind:class="{ 'active': backgroundSize === '100% 100%' }"/>
                                    <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100"
                                          x-bind:class="{ 'active': backgroundSize === '100% 100%' }">Scale</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div x-show="supports.includes('video') && activeTab === 'video'" class="bg-tab">
                        <div id="bg--video-picker"></div>
                    </div>

                    <div x-show="supports.includes('color') && activeTab === 'color'" class="bg-tab">
                        <div id="overlay-color-picker" class="card card-body"></div>
                        <div id="overlay-color-picker-remove-wrapper">
                            <button id="overlay-color-picker-remove-color" type="button"
                                    class="btn btn-ghost-danger w-100">
                                Remove color
                            </button>
                        </div>
                    </div>

                    <div x-show="supports.includes('other') && activeTab === 'other'" class="bg-tab">
                        <h4>Other settings</h4>
                        Cursor image, must be small image, for example 32x32px
                        <div id="bg--cursor-picker"></div>
                    </div>
                </div>
            </div>

            <div class="current-template-modules-list-wrap" x-show="modulesList.length > 0">
                <label class="current-template-modules-list-label live-edit-label">This layout contains these
                    modules</label>
                <div class="current-template-modules-list d-flex flex-wrap gap-2 ms-2">
                    <template x-for="module in modulesList" :key="module.moduleId">
                        <a href="javascript:;" class="btn btn-outline-dark btn-sm"
                           x-on:click="openModuleSettings(module.moduleId)">
                            <span x-text="module.moduleTitle"></span>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
