<div x-data="{
showMainEditTab: 'mainSettings'
}">

    <?php
    $moduleTemplates = module_templates($moduleType);
    ?>

    @if($moduleTemplates && count($moduleTemplates) >  1)
        <div class="d-flex justify-content-between align-items-center collapseNav-initialized form-control-live-edit-label-wrapper">
            <div class="d-flex flex-wrap gap-md-4 gap-3">
                <button x-on:click="showMainEditTab = 'mainSettings'"
                        :class="{ 'active': showMainEditTab == 'mainSettings' }"
                        class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                    @lang('Main settings')
                </button>
                <button x-on:click="showMainEditTab = 'design'" :class="{ 'active': showMainEditTab == 'design' }"
                        class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                    @lang('Design')
                </button>
            </div>
        </div>
    @endif


        <div x-show="showMainEditTab=='mainSettings'">


            <div>
                <label class="live-edit-label"><?php _e("Headers"); ?></label>
                <livewire:microweber-option::text optionKey="headers" :optionGroup="$moduleId" :module="$moduleType"  />
                <label>
                    Example: h1, h2, h3, h4, h5, h6
                </label>
            </div>

            <div class="mt-3">
                <livewire:microweber-option::range-slider label="{{__('Scroll Speed')}}" min="10" max="200" unit="px" optionKey="speed" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

        </div>

    @if($moduleTemplates && count($moduleTemplates) >  1)

        <div x-show="showMainEditTab=='design'" x-transition:enter="tab-pane-slide-right-active">

            <div>
                <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType"/>
            </div>
        </div>

    @endif
</div>
