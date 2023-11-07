<div>
    <label class="live-edit-label">{{__('Upload Audio file')}} </label>
    <livewire:microweber-option::file-picker  allowedType="audio"  optionKey="data-audio-upload" :optionGroup="$moduleId" :module="$moduleType"  />
</div>

<div class="mt-3">
    <label class="live-edit-label">{{__('Paste URL')}} </label>
    <label class="live-edit-label">
        You can <strong>Upload your audio file</strong> or you can <strong>Paste URL</strong> to the file. It\'s possible to use <strong > only one option</strong>
    </label>
    <livewire:microweber-option::textarea optionKey="data-audio-url" :optionGroup="$moduleId" :module="$moduleType"  />
</div>
