<div x-data="{
showMainEditTab: 'mainSettings'
}"
     @mw-option-saved.window="function() {

                if ($event.detail.optionGroup === '{{$moduleId}}' && $event.detail.optionKey === 'use_content_from_live_edit') {
                     mw.reload_module_everywhere('{{$moduleType}}', function () {
                        window.location.reload();
                     });
                }
                }"

>

    <?php
    $moduleTemplates = module_templates($moduleType);

    $use_content_from_live_edit = get_option('use_content_from_live_edit', $moduleId);
    if(!$use_content_from_live_edit){
        $use_content_from_live_edit = 0;
    }



    $editorSettings = [
        'config' => [
            'title' => '',
            'addButtonText' => 'Add Item',
            'editButtonText' => 'Edit',
            'deleteButtonText' => 'Delete',
            'sortItems' => true,
            'settingsKey' => 'settings',
            'listColumns' => [
                'question' => 'Question',
            ],
        ],
        'schema' => [
            [
                'type' => 'text',
                'label' => 'Question',
                'name' => 'question',
                'placeholder' => 'Enter question',
                'help' => 'Enter Question',
            ]

        ]
    ];

    if($use_content_from_live_edit != 1) {
        $editorSettings['schema'][] =  [
            'type' => 'textarea',
            'label' => 'Answer',
            'name' => 'answer',
            'placeholder' => 'Enter answer',
            'help' => 'Enter Answer',
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
        <div class="d-flex justify-content-between align-items-center collapseNav-initialized form-control-live-edit-label-wrapper">
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

        <livewire:microweber-live-edit::module-items-editor :moduleId="$moduleId"
                                                            :moduleType="$moduleType"
                                                            :editorSettings="$editorSettings"/>

    </div>


    @if($moduleTemplates && count($moduleTemplates) >  1)

        <div x-show="showMainEditTab=='design'" x-transition:enter="tab-pane-slide-right-active">


            <div class="d-flex col-xl-3 col-md-6 col-12">

                <livewire:microweber-option::toggle value="1" optionKey="use_content_from_live_edit" :optionGroup="$moduleId" :module="$moduleType" />
                <div>
                    <label class="form-check-label d-flex align-items-center" >

                        @lang('Use tabs content from live edit')

                    </label>

                </div>
            </div>



            <div>
                <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId" :moduleType="$moduleType"/>
            </div>
        </div>

    @endif
</div>
