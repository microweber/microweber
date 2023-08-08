<div x-data="{
showEditTab: 'content',
showAdvancedDesign: false
}">

    <div class="d-flex justify-content-between align-items-center collapseNav-initialized form-control-live-edit-label-wrapper">
        <div class="d-flex flex-wrap gap-md-4 gap-3">
            <a href="#" x-on:click="showEditTab = 'content'" :class="{ 'active': showEditTab == 'content' }"
               class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                Content
            </a>
            <a href="#" x-on:click="showEditTab = 'design'" :class="{ 'active': showEditTab == 'design' }"
               class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
               Design
            </a>
        </div>
    </div>

    <div x-show="showEditTab=='content'">
        <div>
            <label class="live-edit-label">{{__('Text')}} </label>
            <livewire:microweber-option::text optionKey="text" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>
        <div class="mt-4 mb-3">
            <label class="live-edit-label">{{__('Link')}} </label>
            <livewire:microweber-option::link-picker optionKey="link" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

    </div>
    <div x-show="showEditTab=='design'">
        <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType" />
    </div>

</div>
