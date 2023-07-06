<div class="px-2 py-2" x-data="{showEditTab: 'content'}">
    <div class="d-flex justify-content-between align-items-center mb-4 collapseNav-initialized">
        <div class="d-flex flex-wrap gap-md-4 gap-3">
            <a href="#" x-on:click="showEditTab = 'content'" :class="{ 'active': showEditTab == 'content' }"
               class="btn btn-link text-decoration-none mw-admin-action-links active">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21 6v2H3V6h18M3 18h9v-2H3v2m0-5h18v-2H3v2Z"/></svg>
                Content
            </a>
            <a href="#" x-on:click="showEditTab = 'design'" :class="{ 'active': showEditTab == 'design' }"
               class="btn btn-link text-decoration-none mw-admin-action-links">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M3 17.25V21h3.75L17.81 9.93l-3.75-3.75L3 17.25m19.61 1.11l-4.25 4.25l-5.2-5.2l1.77-1.77l1 1l2.47-2.48l1.42 1.42L18.36 17l1.06 1l1.42-1.4l1.77 1.76m-16-7.53L1.39 5.64l4.25-4.25L7.4 3.16L4.93 5.64L6 6.7l2.46-2.48l1.42 1.42l-1.42 1.41l1 1l-2.85 2.78M20.71 7c.39-.39.39-1 0-1.41l-2.34-2.3c-.37-.39-1.02-.39-1.41 0l-1.84 1.83l3.75 3.75L20.71 7Z"/></svg>
               Design
            </a>
        </div>
    </div>

    <div x-show="showEditTab=='content'">
        <div>
            Text
            <livewire:microweber-module-option::text optionName="text" :moduleId="$moduleId" :moduleType="$moduleType"  />
        </div>
        <div class="mt-4">
            Link
            <livewire:microweber-module-option::text optionName="url" :moduleId="$moduleId" :moduleType="$moduleType"    />
        </div>
    </div>

    <div x-show="showEditTab=='design'">
        
    </div>

</div>
