<div x-data="{
showMainEditTab: 'mainSettings'
}">

    <?php
    $moduleTemplates = module_templates($moduleType);

    $editorSettings = [
        'config' => [
            'title' => '',
            'addButtonText' => 'Add Slide',
            'editButtonText' => 'Edit',
            'deleteButtonText' => 'Delete',
            'sortItems' => true,
            'settingsKey' => 'settings',
            'listColumns' => [
                'primaryText' => 'Slide Heading',
            ],
        ],
        'schema' => [
            [
                'type' => 'text',
                'label' => 'Slide Heading',
                'name' => 'primaryText',
                'placeholder' => 'Slide Heading',
                'help' => 'Slide Heading',
            ],
            [
                'type' => 'icon-picker',
                'label' => 'Icon',
                'name' => 'icon',
                'placeholder' => 'Icon',
                'help' => 'Icon',
            ],

             [
                'type' => 'text',
                'label' => 'Slide Description',
                'name' => 'secondaryText',
                'placeholder' => 'Slide Description',
                'help' => 'Slide Description',
            ],

            [
                'type' => 'text',
                'label' => 'Url',
                'name' => 'url',
                'placeholder' => 'Url',
                'help' => 'Url',
            ],
            [
                'type' => 'text',
                'label' => 'See More Text',
                'name' => 'seemoreText',
                'placeholder' => 'See More Text',
                'help' => 'See More Text',
            ],
            
        ]
    ];

    ?>



    @if($moduleTemplates && count($moduleTemplates) >  1)
        <div class="d-flex justify-content-between align-items-center collapseNav-initialized">
            <div class="d-flex flex-wrap gap-md-4 gap-3">
                <button x-on:click="showMainEditTab = 'mainSettings'"
                        :class="{ 'active': showMainEditTab == 'mainSettings' }"
                        class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                    @lang('Main settings')
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
