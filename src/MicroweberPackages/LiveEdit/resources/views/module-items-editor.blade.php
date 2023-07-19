
<div>
    <h1>{{ isset($moduleTitle) ? $moduleTitle : '' }}</h1>

    <div>

        <livewire:microweber-live-edit::module-items-editor-list :moduleId="$moduleId" :moduleType="$moduleType"
                                                            :editorSettings="$editorSettings"/>


    </div>
</div>
