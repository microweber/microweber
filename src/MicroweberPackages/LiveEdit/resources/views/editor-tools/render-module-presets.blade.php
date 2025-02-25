<div>
    @php

    $moduleId = $params['id'] ?? '';
    $moduleType = $params['module_type_for_preset'] ?? '';
    $selectedPresetId = $params['module-id-from-preset'] ?? '';




    @endphp

    <livewire:microweber-live-edit::module-presets-manager :moduleId="$moduleId" :moduleType="$moduleType" :moduleIdFromPreset="$selectedPresetId" />
</div>
