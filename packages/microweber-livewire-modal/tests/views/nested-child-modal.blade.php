<div data-testid="nested-child-modal" data-title="{{ $title }}">
    <h2>{{ $title }}</h2>
    <button type="button" wire:click="closeModal" data-testid="child-close" dusk="child-close">Close child</button>
</div>
