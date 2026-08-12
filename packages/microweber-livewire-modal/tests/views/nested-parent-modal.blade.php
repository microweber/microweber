<div data-testid="nested-parent-modal" data-title="{{ $title }}">
    <h2>{{ $title }}</h2>
    <button type="button" wire:click="openChild" data-testid="open-child-modal" dusk="open-child-modal">Open child</button>
    <button type="button" wire:click="closeModal" data-testid="parent-close" dusk="parent-close">Close parent</button>
</div>
