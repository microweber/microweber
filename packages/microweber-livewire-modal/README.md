# Microweber Livewire Modal

Standalone Laravel Livewire modal package with:

- **Multiple open instances** — each `openModal` gets a unique instance id (no clobbering)
- **Nested / stacked modals** — opening a modal inside another keeps the parent open
- **Skins** — default skin with X close button; swap via config or per-modal options
- **Options** (all **ON** by default):
  - show close button
  - close on click outside (backdrop)
  - show backdrop
  - close on Escape

Works as a drop-in for `wire-elements/modal` (`livewire-ui-modal` / `wire-elements-modal` aliases) and as a standalone package in any Laravel + Livewire app.

## Installation

```bash
composer require microweber-packages/livewire-modal
```

Laravel auto-discovers the service provider. Publish config/views if needed:

```bash
php artisan vendor:publish --tag=livewire-modal-config
php artisan vendor:publish --tag=livewire-modal-views
```

## Usage

### 1. Mount the modal host once (layout / footer)

```blade
@livewire('microweber-livewire-modal')
{{-- or legacy alias: @livewire('livewire-ui-modal') --}}
```

### 2. Create a modal component

```php
use MicroweberPackages\LivewireModal\ModalComponent;

class ConfirmDeleteModal extends ModalComponent
{
    public string $title = 'Confirm';

    public function confirm(): void
    {
        $this->dispatch('item-deleted');
        $this->closeModal();
    }

    public function render()
    {
        return view('modals.confirm-delete');
    }

    // Optional overrides (defaults are all true / on):
    // public static function closeModalOnClickAway(): bool { return true; }
    // public static function closeModalOnEscape(): bool { return true; }
    // public static function showCloseButton(): bool { return true; }
    // public static function showBackdrop(): bool { return true; }
}
```

Register it with Livewire:

```php
Livewire::component('confirm-delete-modal', ConfirmDeleteModal::class);
```

### 3. Open / close

```blade
<button wire:click="$dispatch('openModal', { component: 'confirm-delete-modal', title: 'Delete item?' })">
    Delete
</button>
```

From a modal component (opening another / nested modal), use the `openModal()` helper —
a component-scoped `$this->dispatch('openModal', …)` is not routed to the modal container
across Livewire component boundaries in Livewire 4, so the helper bridges to a global
browser dispatch for you:

```php
// From inside a ModalComponent (e.g. to open a nested/child modal):
$this->openModal('confirm-delete-modal', ['title' => 'Delete item?']);

$this->closeModal();                 // closes topmost only
$this->forceClose()->closeModal();   // closes entire stack
```

### Nested modals

Open a second modal while one is already open — the first stays mounted and visible underneath with a lower z-index. Closing pops only the top instance.

### Skins

Default skin: `resources/views/skins/default.blade.php`.

```php
// config/livewire-modal.php
'skin' => 'default',
```

Per open:

```js
$dispatch('openModal', {
  component: 'confirm-delete-modal',
  modalAttributes: { skin: 'default', showCloseButton: false }
})
```

## Testing

```bash
composer test
composer analyse
```

## License

MIT
