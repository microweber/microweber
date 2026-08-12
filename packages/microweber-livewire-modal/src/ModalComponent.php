<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal;

use InvalidArgumentException;
use Livewire\Component;
use Livewire\Features\SupportEvents\Event;
use MicroweberPackages\LivewireModal\Contracts\ModalComponent as ModalComponentContract;

/**
 * Base class for modal content components.
 *
 * Extend this class for any Livewire component that should open inside
 * the microweber-livewire-modal stack. Compatible with the wire-elements/modal
 * API (closeModal, skipPreviousModals, forceClose, static option methods).
 */
abstract class ModalComponent extends Component implements ModalComponentContract
{
    public bool $forceClose = false;

    public int $skipModals = 0;

    public bool $destroySkipped = false;

    /**
     * Optional per-instance settings consumed by the modal shell / skins.
     * Keys: overlay (bool), overlayClose (bool), width (string), skin (string),
     * showCloseButton (bool), closeOnEscape (bool), showBackdrop (bool).
     *
     * @var array<string, mixed>
     */
    public array $modalSettings = [];

    /**
     * @var array<string, string>
     */
    protected static array $maxWidths = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-md md:max-w-lg',
        'xl' => 'sm:max-w-md md:max-w-xl',
        '2xl' => 'sm:max-w-md md:max-w-xl lg:max-w-2xl',
        '3xl' => 'sm:max-w-md md:max-w-xl lg:max-w-3xl',
        '4xl' => 'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-4xl',
        '5xl' => 'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-5xl',
        '6xl' => 'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-5xl 2xl:max-w-6xl',
        '7xl' => 'sm:max-w-md md:max-w-xl lg:max-w-3xl xl:max-w-5xl 2xl:max-w-7xl',
    ];

    public function destroySkippedModals(): self
    {
        $this->destroySkipped = true;

        return $this;
    }

    public function skipPreviousModals(int $count = 1, bool $destroy = false): self
    {
        return $this->skipPreviousModal($count, $destroy);
    }

    public function skipPreviousModal(int $count = 1, bool $destroy = false): self
    {
        $this->skipModals = $count;
        $this->destroySkipped = $destroy;

        return $this;
    }

    public function forceClose(): self
    {
        $this->forceClose = true;

        return $this;
    }

    public function closeModal(): void
    {
        $this->dispatch(
            'closeModal',
            force: $this->forceClose,
            skipPreviousModals: $this->skipModals,
            destroySkipped: $this->destroySkipped,
        );
    }

    /**
     * @param  array<int|string, mixed>  $events
     */
    public function closeModalWithEvents(array $events): void
    {
        $this->emitModalEvents($events);
        $this->closeModal();
    }

    /**
     * Open another modal from *within* this modal component (nested modals).
     *
     * A component-scoped `$this->dispatch('openModal', …)` is not routed to the
     * Modal container across Livewire component boundaries in Livewire 4, so this
     * bridges to the global browser dispatch (`window.Livewire.dispatch`) — the
     * same path the blade `$dispatch('openModal', …)` triggers reliably.
     *
     * @param  array<string, mixed>  $arguments        Mount arguments for the opened component.
     * @param  array<string, mixed>  $modalAttributes  Per-instance modal options (skin, width, …).
     */
    public function openModal(string $component, array $arguments = [], array $modalAttributes = []): void
    {
        $payload = [
            'component' => $component,
            'arguments' => $arguments,
            'modalAttributes' => $modalAttributes,
        ];

        $this->js('window.Livewire.dispatch("openModal", ' . json_encode(
            $payload,
            JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP
        ) . ')');
    }

    public static function modalMaxWidth(): string
    {
        $value = config('livewire-modal.component_defaults.modal_max_width', '2xl');

        return is_scalar($value) ? (string) $value : '2xl';
    }

    public static function modalMaxWidthClass(): string
    {
        $width = static::modalMaxWidth();

        if (! array_key_exists($width, static::$maxWidths)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Modal max width [%s] is invalid. The width must be one of the following [%s].',
                    $width,
                    implode(', ', array_keys(static::$maxWidths)),
                ),
            );
        }

        return static::$maxWidths[$width];
    }

    public static function closeModalOnClickAway(): bool
    {
        return (bool) config('livewire-modal.component_defaults.close_on_click_away', true);
    }

    public static function closeModalOnEscape(): bool
    {
        return (bool) config('livewire-modal.component_defaults.close_on_escape', true);
    }

    public static function closeModalOnEscapeIsForceful(): bool
    {
        return (bool) config('livewire-modal.component_defaults.close_on_escape_is_forceful', false);
    }

    public static function dispatchCloseEvent(): bool
    {
        return (bool) config('livewire-modal.component_defaults.dispatch_close_event', false);
    }

    public static function destroyOnClose(): bool
    {
        return (bool) config('livewire-modal.component_defaults.destroy_on_close', true);
    }

    public static function showCloseButton(): bool
    {
        return (bool) config('livewire-modal.component_defaults.show_close_button', true);
    }

    public static function showBackdrop(): bool
    {
        return (bool) config('livewire-modal.component_defaults.show_backdrop', true);
    }

    public static function modalSkin(): string
    {
        $value = config('livewire-modal.skin', 'default');

        return is_scalar($value) ? (string) $value : 'default';
    }

    /**
     * @param  array<int|string, mixed>  $events
     */
    private function emitModalEvents(array $events): void
    {
        foreach ($events as $component => $event) {
            $params = [];

            if (is_array($event)) {
                $eventName = $event[0] ?? null;
                $params = isset($event[1]) && is_array($event[1]) ? $event[1] : [];
                $event = $eventName;
            }

            if (! is_string($event)) {
                continue;
            }

            if (is_numeric($component)) {
                $this->dispatch($event, ...array_values($params));
            } else {
                $dispatched = $this->dispatch($event, ...array_values($params));
                if ($dispatched instanceof Event) {
                    $dispatched->to((string) $component);
                }
            }
        }
    }
}
