<div x-data="{
showEditTab: 'settings',
showAdvancedDesign: false
}">

    <div class="d-flex justify-content-between align-items-center collapseNav-initialized">
        <div class="d-flex flex-wrap gap-md-4 gap-3">
            <a href="#" x-on:click="showEditTab = 'settings'" :class="{ 'active': showEditTab == 'settings' }"
               class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                Settings
            </a>
            <a href="#" x-on:click="showEditTab = 'design'" :class="{ 'active': showEditTab == 'design' }"
               class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                Design
            </a>
        </div>
    </div>

    <div x-show="showEditTab=='settings'">

        <div class="mt-3">
            <label class="live-edit-label">{{__('Root level')}} </label>
            @php
            $dropdownOptions = [
                '' => 'Default',
                'page' => 'Page',
                'category' => 'Category',
            ];
            @endphp
            <livewire:microweber-option::dropdown :dropdownOptions="$dropdownOptions" optionKey="data-start-from" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

    </div>
    <div x-show="showEditTab=='design'">
        <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType" />
    </div>

</div>
