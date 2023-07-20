<div class="px-2 py-2" x-data="{
showEditTab: 'content',
showAdvancedDesign: false
}">

    <div class="d-flex justify-content-between align-items-center mb-4 collapseNav-initialized">
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
            <livewire:microweber-module-option::text optionKey="text" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>
        <div class="mt-4 mb-3">
            <label class="live-edit-label">{{__('Link')}} </label>
            <livewire:microweber-module-option::link-picker optionName="link" :optionGroup="$moduleId" :module="$moduleType"  />
        </div>

    </div>
    <div x-show="showEditTab=='design'">

        <livewire:microweber-live-edit::module-select-template :optionGroup="$moduleId" :module="$moduleType"/>

        <div class="mt-3">
          <x-microweber-ui::icon-picker wire:model="settings.icon" :value="$settings['icon']"/>
      </div>

       <div class="mt-3">
           <x-microweber-module-btn::btn-align :settings="$settings"/>
       </div>

        <div class="mt-3">
           <button x-on:click="showAdvancedDesign =! showAdvancedDesign" type="button" class="btn btn-link btn-sm">
               {{__('Advanced design')}}
           </button>
        </div>

        <div x-show="showAdvancedDesign" x-transition>
            <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType"/>
        </div>
    </div>

</div>
