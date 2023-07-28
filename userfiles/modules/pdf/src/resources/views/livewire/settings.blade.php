<div>

    <div>
        <label class="live-edit-label">{{__('Choose your PDF file to upload')}} </label>
        <livewire:microweber-option::file-picker optionKey="pdf" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div class="mt-3">
        <label class="live-edit-label">{{__('Set border color to PDF view')}} </label>
        <livewire:microweber-option::color-picker optionKey="border" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

    <div class="mt-3">
        <label class="live-edit-label">{{__('Allow users to download the PDF file')}} </label>
        <livewire:microweber-option::toggle optionKey="download" :optionGroup="$moduleId" :module="$moduleType"  />
    </div>

</div>
