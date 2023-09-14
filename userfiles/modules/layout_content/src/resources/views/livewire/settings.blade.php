<div x-data="{
showMainEditTab: 'mainSettings'
}">

    <?php
    $editorSettings = [
        'config' => [
            'title' => '',
            'addButtonText' => 'Add Content',
            'editButtonText' => 'Edit',
            'deleteButtonText' => 'Delete',
            'sortItems' => true,
            'settingsKey' => 'contents',
            'listColumns' => [
                'image' => 'Image',
                'title' => 'Title',
            ],
        ],
        'schema' => [
            [
                'type' => 'image',
                'label' => 'Image',
                'placeholder' => 'Image',
                'name' => 'image',

            ],
//            [
//                'type' => 'text',
//                'label' => 'Image alt text',
//                'name' => 'imageAltText',
//                'placeholder' => 'Image alt text',
//                'help' => 'Image alt text',
//            ],
            [
                'type' => 'text',
                'label' => 'Title',
                'name' => 'title',
                'placeholder' => 'Enter title',
                'help' => 'Enter Title',
            ],
            [
                'type' => 'textarea',
                'label' => 'Description',
                'name' => 'description',
                'placeholder' => 'Enter description',
                'help' => 'Enter description',
                'maxlength' => '600'
            ]
            , [
                'type' => 'text',
                'label' => 'Button Text',
                'name' => 'buttonText',
                'placeholder' => 'Enter Button Text',
                'help' => 'Enter Button Text',
            ],
            [
                'type' => 'url',
                'label' => 'Button Link',
                'name' => 'buttonLink',
                'placeholder' => 'https://yourwebsite.com',
                'help' => 'Select Link',
            ]
        ]
    ];

    ?>

    <div class="d-flex justify-content-between align-items-center collapseNav-initialized form-control-live-edit-label-wrapper">
        <div class="d-flex flex-wrap gap-md-4 gap-3">
            <button x-on:click="showMainEditTab = 'mainSettings'"
                    :class="{ 'active': showMainEditTab == 'mainSettings' }"
                    class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                @lang('Content')
            </button>
            <button x-on:click="showMainEditTab = 'design'" :class="{ 'active': showMainEditTab == 'design' }"
                    class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                @lang('Design')
            </button>
        </div>
    </div>

    <div x-show="showMainEditTab=='mainSettings'" x-transition:enter="tab-pane-slide-left-active">


        <livewire:microweber-live-edit::module-items-editor :moduleId="$moduleId" :moduleType="$moduleType"
                                                            :editorSettings="$editorSettings"/>

    </div>


        <div x-show="showMainEditTab=='design'" x-transition:enter="tab-pane-slide-right-active">
            <div>
                <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType"/>
            </div>
        </div>

</div>
