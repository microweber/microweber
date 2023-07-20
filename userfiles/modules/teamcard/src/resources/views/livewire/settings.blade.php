<div>


    <div>

        <?php

        $editorSettings = [
            'config' => [
                'title' => 'Teamcard',
                'icon' => 'mdi mdi-account-group',
                'addButtonText' => 'Add team member',
                'editButtonText' => 'Edit team member',
                'deleteButtonText' => 'Delete team member',
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
                    'label' => 'Team member name',
                    'name' => 'name',
                    'placeholder' => 'Enter Name',
                    'help' => 'Enter Name',
                ], [
                    'type' => 'image',
                    'label' => 'Team member name',
                    'name' => 'file',

                ], [
                    'type' => 'text',
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
                    'placeholder' => 'Enter website',
                    'help' => 'Enter website',
                ]
            ]
        ];

        ?>

        <livewire:microweber-live-edit::module-items-editor :moduleId="$moduleId" :moduleType="$moduleType"
                                                            :editorSettings="$editorSettings"/>

        <?php

        /*<livewire:microweber-module-teamcard::list-items :moduleId="$moduleId" :moduleType="$moduleType"  />*/
        ?>

    </div>




</div>
