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
                'image' => 'Image',
                'title' => 'Title',
            ],
            'realtimeEditing'=> true,
        ],
        'schema' => [
            [
                'type' => 'image',
                'label' => 'Image',
                'name' => 'image',
                'placeholder' => 'Image',
                'help' => 'Image',
            ],
            [
                'type' => 'text',
                'label' => 'Slide Title',
                'name' => 'title',
                'placeholder' => 'Slide Title',
                'help' => 'Slide Title',
            ],
//            [
//                'type' => 'icon',
//                'label' => 'Icon',
//                'name' => 'icon',
//                'placeholder' => 'Icon',
//                'help' => 'Icon',
//            ],

             [
                'type' => 'textarea',
                'label' => 'Slide Description',
                'name' => 'description',
                'placeholder' => 'Slide Description',
                'help' => 'Slide Description',
            ],
            [
                'type' => 'select',
                'label' => 'Align Items',
                'name' => 'alignItems',
                'placeholder' => 'Align Items',
                'help' => 'Align Items',
                'options'=> [
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ],
            ],
            [
                'type' => 'toggle',
                'label' => 'Show button',
                'name' => 'showButton',
            ],
            [
                'type' => 'text',
                'label' => 'Button text',
                'name' => 'buttonText',
                'placeholder' => 'Button text',
                'help' => 'Button text',
            ],
            [
                'type' => 'color',
                'label' => 'Button Background color',
                'name' => 'buttonBackgroundColor',
                'placeholder' => 'Button background color',
                'help' => 'Button background color',
            ],
            [
                'type' => 'color',
                'label' => 'Button Background Hover color',
                'name' => 'buttonBackgroundHoverColor',
                'placeholder' => 'Button background hover color',
                'help' => 'Button background hover color',
            ],
            [
                'type' => 'color',
                'label' => 'Button Border color',
                'name' => 'buttonBorderColor',
                'placeholder' => 'Button border color',
                'help' => 'Button border color',
            ],
            [
                'type' => 'color',
                'label' => 'Button text color',
                'name' => 'buttonTextColor',
                'placeholder' => 'Button text color',
                'help' => 'Button text color',
            ],
            [
                'type' => 'color',
                'label' => 'Button text hover color',
                'name' => 'buttonTextHoverColor',
                'placeholder' => 'Button text hover color',
                'help' => 'Button text hover color',
            ],
            [
                'type' => 'range',
                'label' => 'Button Font Size',
                'name' => 'buttonFontSize',
                'placeholder' => 'Button Font Size',
                'help' => 'Button Font Size',
                'min' => 8,
                'max' => 64,
                'labelUnit' => 'px',
            ],
            [
                'type' => 'link-picker',
                'label' => 'Button url',
                'name' => 'url',
                'placeholder' => 'Button url',
                'help' => 'Button url',
            ],
            [
                'type' => 'color',
                'label' => 'Title color',
                'name' => 'titleColor',
                'placeholder' => 'Title color',
                'help' => 'Title color',
            ],
            [
                'type' => 'range',
                'label' => 'Title Font Size',
                'name' => 'titleFontSize',
                'placeholder' => 'Title Font Size',
                'help' => 'Title Font Size',
                'min' => 8,
                'max' => 64,
                'labelUnit' => 'px',
            ],
            [
                'type' => 'color',
                'label' => 'Description color',
                'name' => 'descriptionColor',
                'placeholder' => 'Description color',
                'help' => 'Description color',
            ],
            [
                'type' => 'range',
                'label' => 'Description Font Size',
                'name' => 'descriptionFontSize',
                'placeholder' => 'Description Font Size',
                'help' => 'Description Font Size',
                'min' => 8,
                'max' => 64,
                'labelUnit' => 'px',
            ],
            [
                'type' => 'color',
                'label' => 'Image Background Color',
                'name' => 'imageBackgroundColor',
                'placeholder' => 'Image Background Color',
                'help' => 'Image Background Color',
            ],
            [
                'type' => 'range',
                'label' => 'Image Background Opacity',
                'name' => 'imageBackgroundOpacity',
                'placeholder' => 'Image Background Opacity',
                'help' => 'Image Background Opacity',
                'min' => 0,
                'max' => 1,
                'labelUnit' => '%',
            ],
            [
                'type' => 'select',
                'label' => 'Image Background Filter',
                'name' => 'imageBackgroundFilter',
                'placeholder' => 'Image Background Filter',
                'help' => 'Image Background Filter',
                'options'=> [
                    'none' => 'None',
                    'blur' => 'Blur',
                    'mediumBlur' => 'Medium Blur',
                    'maxBlur' => 'Max Blur',
                    'grayscale' => 'Grayscale',
                    'hue-rotate' => 'Hue Rotate',
                    'invert' => 'Invert',
                    'sepia' => 'Sepia',
                ],
            ],

        ]
    ];

    ?>
<div wire:ignore>
    <script>
        var canvasWindow = mw.top().app.canvas.getWindow();
        var lastSlideEditItemId = null;
        window.livewire.on('editItemById' , (itemId) => {
            lastSlideEditItemId = itemId;
            window.slideModuleSettingsSwitchToSlide(itemId);

        });
        window.livewire.on('onItemChanged', (data) => {
            lastSlideEditItemId = data.itemId;
            if(lastSlideEditItemId) {
                window.slideModuleSettingsSwitchToSlide(lastSlideEditItemId);
            }
        });

        window.livewire.on('mouseoverItemId' , (itemId) => {
            lastSlideEditItemId = itemId;
            if(lastSlideEditItemId) {
                window.slideModuleSettingsSwitchToSlide(lastSlideEditItemId);
            }
        });

        mw.top().app.on('onModuleReloaded', (moduleId) => {
            if(moduleId !== '<?php print $moduleId ?>'){
                return;
            }

            if(lastSlideEditItemId) {
             window.slideModuleSettingsSwitchToSlide(lastSlideEditItemId);
            }
        });

        window.slideModuleSettingsSwitchToSlide = function (itemId) {
            var sliderInstanceName = 'sliderV2<?php echo md5($moduleId); ?>'
            var sliderInstanceNameInitialSlide =   'sliderV2<?php echo md5($moduleId); ?>_initialSlide'

            if (canvasWindow && canvasWindow[sliderInstanceName]) {
                canvasWindow[sliderInstanceName].switchToSlideByItemId(itemId);
            }

        }

    </script>
</div>







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
