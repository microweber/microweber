<div x-data="{
showMainEditTab: 'mainSettings'
}">

    <?php
    $moduleTemplates = module_templates($moduleType);

    $editorSettings = [
        'config' => [
            'title' => 'Accordion',
            'addButtonText' => 'Add accordion item',
            'editButtonText' => 'Edit accordion item',
            'deleteButtonText' => 'Delete accordion item',
            'sortItems' => true,
            'settingsKey' => 'settings',
            'listColumns' => [
                'icon' => 'icon',
                'title' => 'title',

            ],
        ],
        'schema' => [
            [
                'type' => 'text',
                'label' => 'Title',
                'name' => 'title',
                'placeholder' => 'Enter title',
                'help' => 'Enter title',
            ], [
                'type' => 'icon',
                'label' => 'Icon',
                'placeholder' => 'Icon',
                'name' => 'icon',
            ]
        ]
    ];
    ?>



    @if($moduleTemplates && count($moduleTemplates) >  1)
        <div class="d-flex justify-content-between align-items-center mb-4 collapseNav-initialized">
            <div class="d-flex flex-wrap gap-md-4 gap-3">
                <button x-on:click="showMainEditTab = 'mainSettings'"
                        :class="{ 'active': showMainEditTab == 'mainSettings' }"
                        class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                    @lang('Accordion Settings')
                </button>
                <button x-on:click="showMainEditTab = 'design'" :class="{ 'active': showMainEditTab == 'design' }"
                        class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                    @lang('Design')
                </button>
            </div>
        </div>
    @endif

    <div x-show="showMainEditTab=='mainSettings'" x-transition:enter="tab-pane-slide-left-active">


        <livewire:microweber-live-edit::module-items-editor :moduleId="$moduleId" :moduleType="$moduleType"
                                                            :editorSettings="$editorSettings"/>


    </div>


    @if($moduleTemplates && count($moduleTemplates) >  1)

        <div x-show="showMainEditTab=='design'" x-transition:enter="tab-pane-slide-right-active">

            <div>
                <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType"/>
            </div>
        </div>

    @endif
</div>
