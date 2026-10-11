@if($moduleIdFromPreset == $item['module_id'])
    <div class="d-flex flex-column align-items-end">
        <span class="badge bg-warning mb-2">Current preset</span>
        <button class="btn btn-sm btn-warning" onclick="removeSelectedPresetForModule('{{ $moduleId }}')">
            Clear preset
        </button>
    </div>
@elseif($moduleId == $item['module_id'])
    <span class="badge bg-success">Current module</span>
@else
    <button class="btn btn-sm btn-primary" onclick="selectPresetForModule('{{ $itemId }}', '{{ $moduleId }}')">
        Use preset
    </button>
@endif
