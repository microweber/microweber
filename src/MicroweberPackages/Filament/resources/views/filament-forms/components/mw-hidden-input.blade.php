@php
    $statePath = $getStatePath();
@endphp
<div x-data="{

state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}

, init() {
    window.Livewire.on('{{$statePath}}', value => {
        this.state = value
    })
} }" >

    <input x-model="state" type="text" />

</div>
