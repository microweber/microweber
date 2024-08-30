<div x-data="{
showMainEditTab: 'video'
}">

    <?php
    $moduleTemplates = module_templates($moduleType);
    ?>

    <div class="d-flex justify-content-between align-items-center collapseNav-initialized form-control-live-edit-label-wrapper">
        <div class="d-flex flex-wrap gap-md-4 gap-3">

            <button x-on:click="showMainEditTab = 'video'"
                    :class="{ 'active': showMainEditTab == 'video' }"
                    class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                @lang('Video')
            </button>

            <button x-on:click="showMainEditTab = 'settings'"
                    :class="{ 'active': showMainEditTab == 'settings' }"
                    class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                @lang('Settings')
            </button>

            @if($moduleTemplates && count($moduleTemplates) >  1)
            <button x-on:click="showMainEditTab = 'design'" :class="{ 'active': showMainEditTab == 'design' }"
                    class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                @lang('Design')
            </button>
            @endif
        </div>
    </div>

    <script>
        Livewire.on('mw-option-saved', function ($event) {
            if ($event.detail.optionKey == 'embed_url') {
                mw.options.saveOption({
                    option_group: $event.detail.optionGroup,
                    option_key: 'upload',
                    option_value: ''
                });
            }
            if ($event.detail.optionKey == 'upload') {
                mw.options.saveOption({
                    option_group: $event.detail.optionGroup,
                    option_key: 'embed_url',
                    option_value: ''
                });
            }
        });
    </script>

    <div x-show="showMainEditTab=='video'"
         x-transition:enter="tab-pane-slide-left-active"
    >

        <div class="mt-3">
            <label class="live-edit-label">{{__('Paste video URL or Embed Code')}} </label>
            <livewire:microweber-option::textarea optionKey="embed_url" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

        <div class="mt-3">
            <label class="live-edit-label">{{__('Upload Video')}} </label>
            <livewire:microweber-option::file-picker allowedType="video" optionKey="upload" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>


    </div>


    <div x-show="showMainEditTab=='settings'" x-transition:enter="tab-pane-slide-left-active">


        <div class="mt-3">
            <b>Video settings</b>
            <p>Set a width height in pixels</p>
        </div>

        <div class="d-flex gap-3 w-full">
            <div class="mt-3">
                <label class="live-edit-label">{{__('Width')}} </label>
                <livewire:microweber-option::text optionKey="width" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <div class="mt-3">
                <label class="live-edit-label">{{__('Height')}} </label>
                <livewire:microweber-option::text optionKey="height" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>
        </div>

        <div class="d-flex gap-3 w-full mt-3">
            <div>
                <label class="live-edit-label">{{__('Autoplay')}} </label>
                <small class="text-muted">{{__('Autoplay only works with muted video')}} </small>
                <livewire:microweber-option::toggle optionKey="autoplay" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <div>
                <label class="live-edit-label">{{__('Loop')}} </label>
                <livewire:microweber-option::toggle optionKey="loop" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <div>
                <label class="live-edit-label">{{__('Muted')}} </label>
                <livewire:microweber-option::toggle optionKey="muted" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>
        </div>
        <div class="d-flex gap-3 w-full mt-3">
            <div>
                <label class="live-edit-label">{{__('Hide Controls')}} </label>
                <livewire:microweber-option::toggle optionKey="hide_controls" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

            <div>
                <label class="live-edit-label">{{__('Lazy load')}} </label>
                <livewire:microweber-option::toggle optionKey="lazyload" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>
        </div>

        <div class="mt-3">
            <label class="live-edit-label">{{__('Thumbnail')}} </label>
            <livewire:microweber-option::media-picker optionKey="upload_thumb" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>



    </div>


    @if($moduleTemplates && count($moduleTemplates) >  1)

        <div x-show="showMainEditTab=='design'" x-transition:enter="tab-pane-slide-right-active">

            <div>
                <livewire:microweber-live-edit::module-select-template
                    :disableScreenshots="true"
                    :moduleId="$moduleId" :moduleType="$moduleType"/>
            </div>
        </div>

    @endif
    <br />
</div>
