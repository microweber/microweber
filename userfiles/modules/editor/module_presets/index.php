<?php only_admin_access(); ?>
<?php

$moduleId = $params['id'] ?? '';
$moduleType = $params['module_type_for_preset'] ?? '';
$selectedPresetId = $params['module-id-from-preset'] ?? '';

print  \Livewire\Livewire::mount('microweber-live-edit::module-presets-manager', [
    'moduleId' => $moduleId,
    'moduleType' => $moduleType,
    'moduleIdFromPreset' => $selectedPresetId,
])->html();
