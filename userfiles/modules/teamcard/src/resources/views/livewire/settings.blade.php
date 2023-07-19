<div>


    <div>

        <?php

        $editorSettings = [
            'config' => [
                'title' => 'My Module',
                'icon' => 'mdi mdi-account-group',
                'addButtonText' => 'Add Item',
                'editButtonText' => 'Edit Item',
                'deleteButtonText' => 'Delete Item',
                'sortItems' => true,
                'settingsKey' => 'settings',
                'listColumns' => [
                    'name' => 'Name',
                    'bio' => 'Bio',
                    'role' => 'Role',
                    'website' => 'Website',
                ],
            ],
            'schema' => [
                [
                    'type' => 'text',
                    'label' => 'Item Name',
                    'name' => 'name',
                    'placeholder' => 'Enter Name',
                    'help' => 'Enter Name',
                ], [
                    'type' => 'text',
                    'label' => 'bio',
                    'name' => 'bio',
                    'placeholder' => 'Enter bio',
                    'help' => 'Enter bio',
                ]
                , [
                    'type' => 'text',
                    'label' => 'role',
                    'name' => 'role',
                    'placeholder' => 'Enter role',
                    'help' => 'Enter role',
                ]
                , [
                    'type' => 'text',
                    'label' => 'website',
                    'name' => 'website',
                    'placeholder' => 'Enter website',
                    'help' => 'Enter website',
                ]
            ]
        ];

        ?>

        <livewire:microweber-live-edit::module-items-editor :moduleId="$moduleId" :moduleType="$moduleType"  :editorSettings="$editorSettings"/>

        <?php

        /*<livewire:microweber-module-teamcard::list-items :moduleId="$moduleId" :moduleType="$moduleType"  />*/
        ?>

    </div>




</div>
