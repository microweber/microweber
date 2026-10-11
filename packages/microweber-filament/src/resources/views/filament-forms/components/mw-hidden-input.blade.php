@php
    $statePath = $getStatePath();
@endphp
<div x-data="{
    state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
    init() {
        // Listen for slider updates
        window.Livewire.on('updated-{{ str_replace('.', '-', $statePath) }}', (event) => {
            this.state = event.value;
        });
        
        // Also listen for the direct state path event (legacy support)
        window.Livewire.on('{{ $statePath }}', (value) => {
            this.state = value;
        });
    }
}">
    <input x-model="state" type="hidden" />
</div>
