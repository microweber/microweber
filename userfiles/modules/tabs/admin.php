<?php must_have_access() ?>

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
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">

                <?php
                $moduleOption = \MicroweberPackages\Option\Models\ModuleOption::where('option_key', 'settings')
                    ->where('option_group',$params['id'])
                    ->first();

                $formBuilder = App::make(\MicroweberPackages\Form\FormElementBuilder::class);

                echo $formBuilder->mwModuleSettings('settings')
                    ->setModel($moduleOption)
                    ->setGroupId($params['id'])
                    ->schema([
                        [
                            'interface' => 'text',
                            'label' => 'Title',
                            'id' => 'title'
                        ],
                        [
                            'interface' => 'icon',
                            'label' => 'Icon',
                            'id' => 'icon',
                        ]
                    ]);
                ?>

            </div>
            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
