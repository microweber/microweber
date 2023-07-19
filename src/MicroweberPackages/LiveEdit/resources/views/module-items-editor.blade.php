
<div>
    <h1>{{ isset($moduleTitle) ? $moduleTitle : '' }}</h1>

    <div>

        <livewire:microweber-live-edit::module-items-editor-list :moduleId="$moduleId" :module="$moduleType"
                                                            :editorSettings="$editorSettings"/>


    </div>
</div>
