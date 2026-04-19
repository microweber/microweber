<div>
    <script src="{{ asset('modules/layouts/js/layouts-module-settings.js') }}"></script>
    <div
        x-data="layoutSettings('image', '{{ $optionGroup }}')"
    >
        <div id="mw-layout-setting-module" wire:ignore>

            <div x-show="supports.length > 0">                <style>
                    .change-layout-background-wrapper span {
                        font-size: 12px;
                    }

                    .change-layout-background-wrapper {
                        max-width: 90%;
                    }

                    .mw-live-edit-resolutions-wrapper.d-flex {
                        overflow: visible !important;
                        padding-top: 16px !important;
                        max-height: none !important;
                    }

                    .tab-indicator {
                        position: relative;
                        overflow: visible;
                    }

                    .tab-indicator::after {
                        content: '';
                        position: absolute;
                        top: -12px;
                        right: -8px;
                        width: 24px;
                        height: 24px;
                        border-radius: 50%;
                        border: 2px solid #fff;
                        background-size: cover;
                        background-position: center;
                        display: none;
                        z-index: 10;
                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
                    }

                    html.dark .tab-indicator::after {
                        border-color: #9ca3af;
                    }

                    .tab-indicator.has-image::after {
                        display: block;
                        background-image: var(--bg-image);
                    }

                    .tab-indicator.has-color::after {
                        display: block;
                        background-color: var(--bg-color);
                    }

                    .tab-indicator.has-video::after {
                        display: block;
                        background-color: black;
                        content: '▶';
                        color: white;
                        font-size: 10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .tab-indicator.has-cursor::after {
                        display: block;
                        background-color: #6c757d;
                        content: '↖';
                        color: white;
                        font-size: 10px;
                        line-height: 12px;
                        text-align: center;
                    }

                    .module-icon svg {
                        width: 16px;
                        height: 16px;
                        fill: currentColor;
                    }

                    .module-icon img {
                        width: 16px;
                        height: 16px;
                        object-fit: contain;
                    }

                    html.dark .module-icon svg {
                        fill: #d1d5db;
                    }

                    html.dark .module-icon img {
                        filter: brightness(0) invert(0.85);
                    }

                    .current-template-modules-list .btn {
                        transition: all 0.2s ease;
                    }

                    .current-template-modules-list .btn:hover {
                        transform: translateY(-1px);
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    }

                    html.dark .current-template-modules-list .btn {
                        color: #d1d5db;
                        border-color: #6b7280;
                    }

                    html.dark .current-template-modules-list .btn:hover {
                        color: #f3f4f6;
                        border-color: #9ca3af;
                    }

                    /* File picker action icons dark mode */
                    html.dark .mw-filepicker-component .mw-liveedit-button-actions-component {
                        color: #d1d5db;
                    }

                    html.dark .mw-filepicker-component .mw-liveedit-button-actions-component svg:not([fill="red"]) {
                        fill: #d1d5db;
                    }
                </style>

                <div>
                    <div class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper">
                        <span x-show="supports.includes('image')" x-on:click="activeTab = 'image'"
                              x-bind:class="{ 'active': activeTab === 'image', 'tab-indicator': true, 'has-image': hasBackgroundImage }"
                              x-bind:style="hasBackgroundImage && backgroundImagePreview ? `--bg-image: url('${backgroundImagePreview}')` : ''"
                              class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Image</span>
                        <span x-show="supports.includes('video')" x-on:click="activeTab = 'video'"
                              x-bind:class="{ 'active': activeTab === 'video', 'tab-indicator': true, 'has-video': hasBackgroundVideo }"
                              class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Video</span>
                        <span x-show="supports.includes('color')" x-on:click="activeTab = 'color'"
                              x-bind:class="{ 'active': activeTab === 'color', 'tab-indicator': true, 'has-color': hasBackgroundColor }"
                              x-bind:style="hasBackgroundColor && backgroundColorPreview ? `--bg-color: ${backgroundColorPreview}` : ''"
                              class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Color</span>
                        <span x-show="supports.includes('other')" x-on:click="activeTab = 'other'"
                              x-bind:class="{ 'active': activeTab === 'other', 'tab-indicator': true, 'has-cursor': hasBackgroundCursor }"
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
                    </div>                    <div x-show="supports.includes('color') && activeTab === 'color'" class="bg-tab">
                        <div id="overlay-color-picker" class="card card-body"></div>
                        <div id="overlay-color-picker-remove-wrapper">
                            <button id="overlay-color-picker-remove-color" type="button"
                                    class="btn btn-ghost-danger w-100"
                                    x-on:click="removeBackgroundColor()">
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
            <div @if(isset($showOnlyBackgroundSettings) && $showOnlyBackgroundSettings) x-show="false" @endif>

                <div class="current-template-modules-list-wrap mt-4" x-show="modulesList.length > 0">
                    <label class="current-template-modules-list-label live-edit-label mb-2">This layout contains these modules</label>
                    <div class="current-template-modules-list d-flex flex-wrap gap-2 ms-2">
                        <template x-for="module in modulesList" :key="module.moduleId">
                            <a href="javascript:;" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-1"
                               x-on:click="openModuleSettings(module.moduleId)">
                                <span x-html="module.moduleIcon" class="module-icon" style="width: 16px; height: 16px; display: inline-flex; align-items: center; justify-content: center;"></span>
                                <span x-text="module.moduleTitle"></span>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
