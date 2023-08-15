<?php only_admin_access(); ?>
<?php

$moduleId = $params['module_id_for_preset'] ?? '';
$moduleType = $params['module_type_for_preset'] ?? '';


print  \Livewire\Livewire::mount('microweber-live-edit::module-presets-manager', [
    'moduleId' => $moduleId,
    'moduleType' => $moduleType,
])->html();
