<div>
    <label class="live-edit-label">{{__('Title')}} </label>
    <livewire:microweber-option::text optionKey="title" :optionGroup="$moduleId" :module="$moduleType"  />
</div>

<div>
    <label class="live-edit-label">{{__('Description')}} </label>
    <livewire:microweber-option::textarea optionKey="description" :optionGroup="$moduleId" :module="$moduleType"  />
</div>
