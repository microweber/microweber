<div x-data="{ state: $wire.$entangle('{{$getStatePath()}}', false), init() {
    window.Livewire.on('{{$getStatePath()}}', value => {
        this.state = value
    })
} }" style="display: none">
    <input x-model="state" type="hidden"/>
</div>
