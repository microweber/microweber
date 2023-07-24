<div x-data="{
showMainEditTab: 'mainSettings'
}">

    <?php
    $moduleTemplates = module_templates($moduleType);

    $editorSettings = [
        'config' => [
            'title' => '',
            'icon' => 'mdi mdi-account-group',
            'addButtonText' => 'ADD ITEM',
            'addButtonIconSvg' => '<svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"></path></svg>',
            'editButtonIconSvg' => '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M530-481 332-679l43-43 241 241-241 241-43-43 198-198Z"/></svg>',
            'deleteButtonIconSvg' => '<svg class="text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>',
            'backButtonIconSvg' => '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M400-240 160-480l241-241 43 42-169 169h526v60H275l168 168-43 42Z"/></svg>',
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
            ],  [
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
            <button   x-on:click="showMainEditTab = 'mainSettings'" :class="{ 'active': showMainEditTab == 'mainSettings' }"
                      class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                @lang('Main settings')
            </button>
            <button   x-on:click="showMainEditTab = 'design'" :class="{ 'active': showMainEditTab == 'design' }"
                      class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                @lang('Design')
            </button>
        </div>
    </div>
@endif

    <div x-show="showMainEditTab=='mainSettings'" x-transition:enter="tab-pane-slide-left-active">






        <livewire:microweber-live-edit::module-items-editor :moduleId="$moduleId" :moduleType="$moduleType" :editorSettings="$editorSettings"/>

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
