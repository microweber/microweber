<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Livewire Modal Playground</title>
    @livewireStyles
</head>
<body>
<div style="padding: 2rem; position: relative; z-index: 2000;">
    <h1>Livewire Modal Test Playground</h1>

    <button type="button" dusk="open-parent"
            onclick="window.Livewire.dispatch('openModal', { component: 'nested-parent-modal', title: 'Parent Modal' })">
        Open parent modal
    </button>

    <button type="button" dusk="open-demo"
            onclick="window.Livewire.dispatch('openModal', { component: 'demo-modal', title: 'Demo 1' })">
        Open demo modal
    </button>

    <button type="button" dusk="open-demo-again"
            onclick="window.Livewire.dispatch('openModal', { component: 'demo-modal', title: 'Demo 2' })">
        Open demo modal again
    </button>
</div>

@livewire('microweber-livewire-modal')
@livewireScripts
</body>
</html>
