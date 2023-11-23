<div>

    <div class="mt-4 mb-3">
        <label class="live-edit-label">Show products from</label>
        <livewire:microweber-option::dropdown :dropdownOptions="$shopPagesDropdownOptions" optionKey="dropdown" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div>
        <label class="live-edit-label">Tags Filtering</label>
        <livewire:microweber-option::toggle-reversed optionKey="disable_tags_filtering" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div>
        <label class="live-edit-label">Categories Filtering</label>
        <livewire:microweber-option::toggle-reversed optionKey="disable_categories_filtering" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div>
        <label class="live-edit-label">Custom Fields Filtering</label>
        <livewire:microweber-option::toggle-reversed optionKey="disable_custom_fields_filtering" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

</div>
