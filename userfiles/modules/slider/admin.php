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
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e('Slides'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">

                <?php
                $module_template = get_module_option('data-template', $params['id']);
                if (!$module_template OR $module_template == 'default') {
                    $module_template = 'bxslider-skin-1';
                }

                $module_template_clean = str_replace('.php', '', $module_template);
                $default_skins_path = normalize_path($config['path_to_module'] . 'templates/' . $module_template_clean . '/skins',true);

                $template_skins_path = normalize_path(template_dir() . 'modules/slider/templates/' . $module_template_clean . '/skins',true);
                $skins = array( );

                if (is_dir($template_skins_path)) {
                    $skins2 = scandir($template_skins_path);
                    if($skins2){
                        $skins = array_merge($skins,$skins2);
                    }
                }

                if (empty($skins) and is_dir($default_skins_path)) {
                    // if the template does not have skins we get from default
                    $skins2 = scandir($default_skins_path);
                    if($skins2){
                        $skins = array_merge($skins,$skins2);
                    }

                }
                $skins_skip = array('..', '.');
                $skins = array_diff($skins, $skins_skip);
                if($skins) {
                    $skins = array_unique($skins);
                    $skins = array_values($skins);
                }

               $moduleOption = \MicroweberPackages\Option\Models\ModuleOption::where('option_key', 'settings')
                                ->where('option_group',$params['id'])
                                ->first();

                $formBuilder = App::make(\MicroweberPackages\Form\FormElementBuilder::class);

                echo $formBuilder->mwModuleSettings('settings')
                    ->setModel($moduleOption)
                    ->schema([
                        [
                            'interface' => 'text',
                            'label' => ['Slide Heading'],
                            'id' => 'primaryText'
                        ],
                        [
                            'interface' => 'file',
                            'id' => 'images',
                            'label' => 'Add Image',
                            'types' => 'images',
                            'multiple' => 2,
                        ],

                        [
                            'interface' => 'icon',
                            'label' => ['Icon'],
                            'id' => 'icon'
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
                        ],
                        [
                            'interface' => 'select',
                            'label' => ['Skin'],
                            'id' => 'skin',
                            'options' => $skins
                        ],
                    ]);
                ?>

            </div>

            <div class="tab-pane fade" id="settings">
                <module type="slider/settings" />
            </div>

            <div class="tab-pane fade" id="templates">

                <module type="admin/modules/templates" for-module="<?php print $params['module'] ?>" parent-module-id="<?php print $params['id'] ?>" />
            </div>

        </div>
    </div>
</div>
