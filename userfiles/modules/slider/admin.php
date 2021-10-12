<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e('Slides'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">

                <?php

               $moduleOption = \MicroweberPackages\Option\Models\ModuleOption::where('option_key', 'settings')
                                ->where('option_group',$params['id'])
                                ->where('module',$params['module'])
                                ->first();

                $formBuilder = App::make(\MicroweberPackages\Form\FormElementBuilder::class);

                echo $formBuilder->mwModuleSettings('option_value')
                    ->setModel($moduleOption)
                    ->schema([
                        [
                            'interface' => 'file',
                            'id' => 'images',
                            'label' => 'Add Image',
                            'types' => 'images',
                            'multiple' => 2,
                        ],
                        [
                            'interface' => 'select',
                            'label' => ['Skin'],
                            'id' => 'skin',
                            'options' => 'skins'
                        ],
                        [
                            'interface' => 'icon',
                            'label' => ['Icon'],
                            'id' => 'icon'
                        ],
                        [
                            'interface' => 'text',
                            'label' => ['Slide Heading'],
                            'id' => 'primaryText'
                        ],
                        [
                            'interface' => 'text',
                            'label' => ['Slide Description'],
                            'id' => 'secondaryText'
                        ],
                        [
                            'interface' => 'text',
                            'label' => ['URL'],
                            'id' => 'url'
                        ],
                        [
                            'interface' => 'text',
                            'label' => ['See more text'],
                            'id' => 'seemoreText'
                        ]
                    ]);
                ?>

            </div>

            <div class="tab-pane fade" id="settings">
                <module type="slider/settings" />
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>

        </div>
    </div>
</div>
