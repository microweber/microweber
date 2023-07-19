
<div>
    <h1>{{ isset($moduleTitle) ? $moduleTitle : '' }}</h1>

    <div>

        <livewire:microweber-live-edit::module-items-editor-list :optionGroup="$moduleId" :module="$moduleType"
                                                            :editorSettings="$editorSettings"/>


    </div>
</div>
