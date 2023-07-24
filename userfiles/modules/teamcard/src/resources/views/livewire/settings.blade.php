<div x-data="{
showMainEditTab: 'mainSettings'
}">

    <?php
    $moduleTemplates = module_templates($moduleType);

    $editorSettings = [
        'config' => [
            'title' => '',
            'addButtonText' => 'Add Item',
            'editButtonText' => 'Edit',
            'deleteButtonText' => 'Delete',
            'sortItems' => true,
            'settingsKey' => 'settings',
            'listColumns' => [
                'name' => 'Name',
//                'bio' => 'Bio',
//                'role' => 'Role',
//                'website' => 'Website',
            ],
        ],
        'schema' => [
            [
                'type' => 'text',
                'label' => 'Team member name',
                'name' => 'name',
                'placeholder' => 'Enter name',
                'help' => 'Enter Name',
            ], [
                'type' => 'image',
                'label' => 'Team member picture',
                'placeholder' => 'Team member picture',
                'name' => 'file',

            ], [
                'type' => 'textarea',
                'label' => 'Team member bio',
                'name' => 'bio',
                'placeholder' => 'Enter bio',
                'help' => 'Enter bio',
                'maxlength' => '150'
            ]
            , [
                'type' => 'text',
                'label' => 'Team member role',
                'name' => 'role',
                'placeholder' => 'Enter role',
                'help' => 'Enter role',
            ]
            , [
                'type' => 'text',
                'label' => 'Team member website',
                'name' => 'website',
                'placeholder' => 'https://yourwebsite.com',
                'help' => 'Enter website',
            ]
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

        <?php

        /*<livewire:microweber-module-teamcard::list-items :moduleId="$moduleId" :moduleType="$moduleType"  />*/
        ?>

    </div>


    @if($moduleTemplates && count($moduleTemplates) >  1)

        <div x-show="showMainEditTab=='design'" x-transition:enter="tab-pane-slide-right-active">

            <div>
                <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType"/>
            </div>
        </div>

    @endif
</div>
