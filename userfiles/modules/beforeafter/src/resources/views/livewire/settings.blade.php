<div>
    <div>
        <label class="live-edit-label">{{__('Upload Before Image')}} </label>
        <livewire:microweber-option::media-picker optionKey="before" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div class="mt-4 mb-4">
        <label class="live-edit-label">{{__('Upload After Image')}} </label>
        <livewire:microweber-option::media-picker optionKey="after" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

</div>
