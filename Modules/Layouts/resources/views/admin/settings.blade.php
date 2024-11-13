<div>

    <div
        x-ignore
        ax-load
        ax-load-src="{{ asset('modules/layouts/js/layouts-module-settings.js') }}"
        x-data="layoutSettings('image', '{{ $optionGroup }}')"
    >

        <div id="mw-layout-setting-module">

            <div id="change-background" wire:ignore>
                <style>
                    .change-layout-background-wrapper span {
                        font-size: 12px;
                    }
                    .change-layout-background-wrapper {
                        max-width: 90%;
                    }
                </style>

                <div>
                    <div class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper" id="bg-tabs">
                        <span x-on:click="activeTab = 'image'" x-bind:class="{ 'active': activeTab === 'image' }" class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Image</span>
                        <span x-on:click="activeTab = 'video'" x-bind:class="{ 'active': activeTab === 'video' }" class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Video</span>
                        <span x-on:click="activeTab = 'color'" x-bind:class="{ 'active': activeTab === 'color' }" class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Color</span>
                        <span x-on:click="activeTab = 'other'" x-bind:class="{ 'active': activeTab === 'other' }" class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100" style="display: none;">Other</span>
                    </div>
                    <br>

                    <div x-show="activeTab === 'image'" class="bg-tab">
                        <div id="bg--image-picker"></div>
                        <br>
                        <div class="change-layout-background-wrapper">
                            <label class="live-edit-label">Image size</label>
                            <div class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper mx-0">
                                <label class="form-selectgroup-item w-100">
                                    <input type="radio" name="backgroundSize" value="auto" class="form-selectgroup-input" checked />
                                    <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Auto</span>
                                </label>
                                <label class="form-selectgroup-item w-100">
                                    <input type="radio" name="backgroundSize" value="cover" class="form-selectgroup-input" />
                                    <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Cover</span>
                                </label>
                                <label class="form-selectgroup-item w-100">
                                    <input type="radio" name="backgroundSize" value="contain" class="form-selectgroup-input" />
                                    <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Fit</span>
                                </label>
                                <label class="form-selectgroup-item w-100">
                                    <input type="radio" name="backgroundSize" value="100% 100%" class="form-selectgroup-input" />
                                    <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Scale</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div x-show="activeTab === 'video'" class="bg-tab">
                        <div id="bg--video-picker"></div>
                    </div>

                    <div x-show="activeTab === 'color'" class="bg-tab">
                        <div id="overlay-color-picker" class="card card-body"></div>
                        <div id="overlay-color-picker-remove-wrapper">
                            <button id="overlay-color-picker-remove-color" type="button" class="btn btn-ghost-danger w-100">
                                Remove color
                            </button>
                        </div>
                    </div>

                    <div x-show="activeTab === 'other'" class="bg-tab">
                        <h4>Other settings</h4>
                        Cursor image, must be small image, for example 32x32px
                        <div id="bg--cursor-picker"></div>
                    </div>
                </div>
            </div>


            <div class="current-template-modules-list-wrap">
                <label class="current-template-modules-list-label live-edit-label" style="display: none" >This layout contains those modules</label>
                <div class="current-template-modules-list d-flex flex-wrap gap-2 ms-2"></div>
            </div>

        </div>

    </div>

</div>
