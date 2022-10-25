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
        <style>
            .faq-setting-item{
                cursor: pointer;
            }
            .faq-setting-item .mdi-cursor-move,
            .faq-setting-item .remove-question {
                visibility: hidden;
            }

            .faq-setting-item:hover .mdi-cursor-move,
            .faq-setting-item:hover .remove-question {
                visibility: visible;
            }
        </style>
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i><?php _e('List of Questions'); ?> </a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <div class="module-live-edit-settings module-faq-settings">

                    <?php
                    $moduleOption = \MicroweberPackages\Option\Models\ModuleOption::where('option_key', 'settings')
                        ->where('option_group',$params['id'])
                        ->first();

                    $formBuilder = App::make(\MicroweberPackages\Form\FormElementBuilder::class);

                    echo $formBuilder->mwModuleSettings('settings')
                        ->setModel($moduleOption)
                        ->schema([
                            [
                                'interface' => 'text',
                                'label' => ['Question'],
                                'id' => 'question'
                            ],
                            [
                                'interface' => 'richtext',
                                'id' => 'answer',
                                'label' => 'Answer',
                                'options' => null,
                            ]
                        ]);

                    ?>

                </div>
            </div>
            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
