<div>
    <div x-data="{
    showEditTab: 'content'
    }">

        <div class="d-flex justify-content-between align-items-center collapseNav-initialized">
            <div class="d-flex flex-wrap gap-md-4 gap-3">
                <a href="#" x-on:click="showEditTab = 'content'" :class="{ 'active': showEditTab == 'content' }"
                   class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                    Content
                </a>
                <a href="#" x-on:click="showEditTab = 'settings'" :class="{ 'active': showEditTab == 'settings' }"
                   class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                    Settings
                </a>
            </div>
        </div>

        <div x-show="showEditTab=='content'">
            <div>
                <label class="live-edit-label">{{__('Title')}} </label>
                <livewire:microweber-option::text optionKey="title" :optionGroup="$moduleId" :module="$moduleType"  />
            </div>

             <div>
                  <label class="live-edit-label">{{__('Description')}} </label>
                  <livewire:microweber-option::textarea optionKey="description" :optionGroup="$moduleId" :module="$moduleType"  />
              </div>

        </div>
        <div x-show="showEditTab=='settings'">
            This is the settings tab
        </div>

    </div>

</div>
