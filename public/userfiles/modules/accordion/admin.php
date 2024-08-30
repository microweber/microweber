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

<div class="card-body mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class=" ">
        <style>
            .show-on-hover-root {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .show-on-hover-root > div {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
        </style>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs  active" data-bs-toggle="tab" href="#settings">  <?php _e('Settings'); ?></a>
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="tab" href="#templates">   <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">
                <!-- Settings Content -->

                <?php
                $moduleOption = \MicroweberPackages\Option\Models\ModuleOption::where('option_key', 'settings')
                    ->where('option_group',$params['id'])
                    ->first();

                $formBuilder = App::make(\MicroweberPackages\FormBuilder\FormElementBuilder::class);

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

                <br>
                <br>
                <label class="form-label align-self-center"> <?php  _e('Info!'); ?></label>
                <small class="text-muted d-inline-block mb-2"><?php  _e('Use the live edit to drag and drop image, video or something else directly on created accordions.'); ?></small>

            </div>
            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
