<div x-data="{
showEditTab: 'main'
}">


    <?php

    $use_content_from_live_edit = get_option('use_content_from_live_edit', $moduleId);
    if(!$use_content_from_live_edit){
        $use_content_from_live_edit = 0;
    }



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
            ],
            [
                'type' => 'icon',
                'label' => 'Icon',
                'placeholder' => 'Icon',
                'name' => 'icon',
            ],

        ]
    ];




    if($use_content_from_live_edit != 1) {
        $editorSettings['schema'][] = [
            'type' => 'textarea',
            'label' => 'Content',
            'name' => 'content',
            'placeholder' => 'Enter content',
            'help' => 'Enter content',
        ];
    } else {
        $editorSettings['schema'][] = [
            'type' => 'info',
            'label' => 'info',
            'placeholder' => 'info',
            'name' => 'info',
            'help' => 'Use the live edit to drag and drop image, video or something else directly on created accordions.',
        ];
    }

    ?>


    @if($moduleTemplates && count($moduleTemplates) >  1)
        <div class="d-flex justify-content-between align-items-center mb-4 collapseNav-initialized">
            <div class="d-flex flex-wrap gap-md-4 gap-3">
                <button x-on:click="showEditTab = 'main'"
                        :class="{ 'active': showEditTab == 'main' }"
                        class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                    @lang('Accordion Settings')
                </button>
                <button x-on:click="showEditTab = 'design'" :class="{ 'active': showEditTab == 'design' }"
                        class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                    @lang('Design')
                </button>
            </div>
        </div>
    @endif

    <div x-show="showEditTab=='main'" x-transition:enter="tab-pane-slide-left-active">

        <div>
        <livewire:microweber-live-edit::module-items-editor :moduleId="$moduleId" :moduleType="$moduleType"
                                                 wire:key="items-edit-{{ $use_content_from_live_edit }}"
                                                :editorSettings="$editorSettings"/>

        </div>
    </div>


        <div x-show="showEditTab=='design'" x-transition:enter="tab-pane-slide-right-active">

            <div>
                <div class="d-flex col-xl-3 col-md-6 col-12">

                    <livewire:microweber-option::toggle value="1" optionKey="use_content_from_live_edit" :optionGroup="$moduleId" :module="$moduleType" />
                    <div>
                        <label class="form-check-label d-flex align-items-center" >

                            @lang('Use tabs content from live edit')

                        </label>

                    </div>
                </div>


                @if($moduleTemplates && count($moduleTemplates) >  1)
                <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType"/>
                @endif
            </div>
        </div>


</div>
