<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal;

use Exception;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Mechanisms\ComponentRegistry;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use UnitEnum;

/**
 * Livewire modal stack manager.
 *
 * Supports:
 * - Multiple simultaneous open instances (unique IDs per open)
 * - Nested modals (opening B while A is open keeps A open)
 * - Skins and per-modal options (backdrop, escape, click-away, close button)
 */
class Modal extends Component
{
    /**
     * Currently focused (top-of-stack) modal instance id.
     */
    #[Locked]
    public ?string $activeComponent = null;

    /**
     * Open modal instances keyed by unique instance id.
     *
     * @var array<string, array{
     *     name: string,
     *     attributes: array<string, mixed>,
     *     arguments: array<string, mixed>,
     *     modalAttributes: array<string, mixed>,
     *     modalSettings: array<string, mixed>,
     *     zIndex: int
     * }>
     */
    #[Locked]
    public array $components = [];

    /**
     * Ordered stack of open instance ids (bottom → top).
     *
     * @var list<string>
     */
    #[Locked]
    public array $stack = [];

    public function resetState(): void
    {
        $this->components = [];
        $this->stack = [];
        $this->activeComponent = null;
    }

    /**
     * Open a modal component. Always generates a unique instance id so the
     * same component (even with the same attributes) can be opened multiple
     * times without clobbering the previous instance or its event bindings.
     *
     * @param  array<string, mixed>|string|null  $component
     * @param  array<string, mixed>|mixed  $arguments
     * @param  array<string, mixed>|mixed  $modalAttributes
     */
    #[On('openModal')]
    public function openModal(mixed $component = null, mixed $arguments = [], mixed $modalAttributes = []): void
    {
        // Livewire named-arg / Alpine object dispatches may pass a single array.
        if (is_array($component) && isset($component['component']) && is_string($component['component'])) {
            $payload = $component;
            $arguments = [];
            if (isset($payload['arguments']) && is_array($payload['arguments'])) {
                /** @var array<string, mixed> $arguments */
                $arguments = $this->stringifyKeys($payload['arguments']);
            } elseif (isset($payload['attributes']) && is_array($payload['attributes'])) {
                /** @var array<string, mixed> $arguments */
                $arguments = $this->stringifyKeys($payload['attributes']);
            } else {
                $arguments = $this->extractArguments($this->stringifyKeys($payload));
            }

            $modalAttributes = [];
            if (isset($payload['modalAttributes']) && is_array($payload['modalAttributes'])) {
                $modalAttributes = $this->stringifyKeys($payload['modalAttributes']);
            }

            $component = $payload['component'];
        }

        if (! is_string($component) || $component === '') {
            throw new Exception('A modal component name is required.');
        }

        if (! is_array($arguments)) {
            $arguments = [];
        }
        $arguments = $this->stringifyKeys($arguments);

        if (! is_array($modalAttributes)) {
            $modalAttributes = [];
        }
        $modalAttributes = $this->stringifyKeys($modalAttributes);

        $requiredInterface = \MicroweberPackages\LivewireModal\Contracts\ModalComponent::class;
        $componentClass = $this->resolveComponentClass($component);
        $reflect = new ReflectionClass($componentClass);

        if ($reflect->implementsInterface($requiredInterface) === false) {
            throw new Exception("[{$componentClass}] does not implement [{$requiredInterface}] interface.");
        }

        // Unique per open — never hash component+args (that caused the multi-instance bug).
        $id = $this->generateInstanceId();

        /** @var Component $instance */
        $instance = new $componentClass();
        $resolved = $this->resolveComponentProps($arguments, $instance);
        /** @var array<string, mixed> $arguments */
        $arguments = collect($arguments)->merge($resolved)->all();
        $arguments = $this->stringifyKeys($arguments);

        $modalSettings = $this->resolveModalSettings($componentClass, $arguments, $modalAttributes);

        $baseZ = $this->configInt('livewire-modal.base_z_index', 1100);
        $step = $this->configInt('livewire-modal.z_index_step', 10);
        $zIndex = $baseZ + (count($this->stack) * $step);

        $defaults = $this->defaultModalAttributes($componentClass);

        $this->components[$id] = [
            'name' => $component,
            'attributes' => $arguments,
            'arguments' => $arguments,
            'modalAttributes' => array_merge($defaults, $modalAttributes, [
                'zIndex' => $zIndex,
            ]),
            'modalSettings' => $modalSettings,
            'zIndex' => $zIndex,
        ];

        $this->stack[] = $id;
        $this->activeComponent = $id;

        $this->dispatch('activeModalComponentChanged', id: $id, data: [
            'id' => $id,
            'modalSettings' => $modalSettings,
            'zIndex' => $zIndex,
            'stackDepth' => count($this->stack),
        ]);
    }

    /**
     * Close the topmost modal (or the entire stack when $force is true).
     * Nested parents remain open unless force-closed or skipped.
     */
    #[On('closeModal')]
    public function closeModal(mixed $force = false, mixed $skipPreviousModals = 0, mixed $destroySkipped = false): void
    {
        // Alpine / Livewire may wrap named params in an array.
        if (is_array($force) && array_key_exists('force', $force)) {
            $skipPreviousModals = $force['skipPreviousModals'] ?? 0;
            $destroySkipped = $force['destroySkipped'] ?? false;
            $force = $force['force'] ?? false;
        }

        $force = (bool) $force;
        $skipPreviousModals = is_numeric($skipPreviousModals) ? (int) $skipPreviousModals : 0;
        $destroySkipped = (bool) $destroySkipped;

        if ($force || $this->activeComponent === null) {
            $closedIds = array_keys($this->components);
            $this->resetState();
            $this->dispatch('modalStackCleared', ids: $closedIds);

            return;
        }

        $closedId = $this->popStack();

        for ($i = 0; $i < $skipPreviousModals; $i++) {
            $this->popStack($destroySkipped);
        }

        $this->activeComponent = $this->stack === [] ? null : $this->stack[array_key_last($this->stack)];

        if ($closedId !== null) {
            $this->dispatch('modalInstanceClosed', id: $closedId, activeId: $this->activeComponent);
        }

        if ($this->stack === []) {
            $this->resetState();
            $this->dispatch('modalStackCleared', ids: $closedId !== null ? [$closedId] : []);
        }
    }

    #[On('destroyComponent')]
    public function destroyComponent(string $id): void
    {
        unset($this->components[$id]);
        $this->stack = array_values(array_filter(
            $this->stack,
            static fn (string $stackId): bool => $stackId !== $id,
        ));
        $this->activeComponent = $this->stack === [] ? null : $this->stack[array_key_last($this->stack)];
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return Collection<string, mixed>
     */
    public function resolveComponentProps(array $attributes, Component $component): Collection
    {
        return $this->getPublicPropertyTypes($component)
            ->intersectByKeys($attributes)
            ->map(function (string $className, string $propName) use ($attributes): mixed {
                /** @var class-string $className */
                return $this->resolveParameter($attributes, $propName, $className);
            });
    }

    /**
     * @return Collection<string, string>
     */
    public function getPublicPropertyTypes(Component $component): Collection
    {
        /** @var array<string, mixed> $all */
        $all = is_array($component->all()) ? $component->all() : [];

        /** @var Collection<string, string> $types */
        $types = collect($all)
            ->map(function (mixed $value, mixed $name) use ($component): ?string {
                if (! is_string($name)) {
                    return null;
                }

                try {
                    $property = new ReflectionProperty($component, $name);
                    $type = $property->getType();
                    if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                        return $type->getName();
                    }
                } catch (\ReflectionException) {
                    return null;
                }

                return null;
            })
            ->filter(static fn (?string $v): bool => is_string($v) && $v !== '');

        return $types;
    }

    public function render(): View
    {
        return view('livewire-modal::modal');
    }

    protected function generateInstanceId(): string
    {
        return str_replace('.', '', uniqid('mwlm_', true));
    }

    /**
     * @return class-string
     */
    protected function resolveComponentClass(string $component): string
    {
        if (class_exists(ComponentRegistry::class)) {
            $class = app(ComponentRegistry::class)->getClass($component);
            if (is_string($class) && class_exists($class)) {
                return $class;
            }
        }

        // Livewire v4 finder fallback
        if (app()->bound('livewire.finder')) {
            $finder = app('livewire.finder');
            if (is_object($finder) && method_exists($finder, 'resolveClassComponentClassName')) {
                $resolved = $finder->resolveClassComponentClassName($component);
                if (is_string($resolved) && class_exists($resolved)) {
                    return $resolved;
                }
            }
        }

        throw new Exception("Unable to resolve Livewire component class for [{$component}].");
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @param  class-string  $parameterClassName
     */
    protected function resolveParameter(array $attributes, string $parameterName, string $parameterClassName): mixed
    {
        $parameterValue = $attributes[$parameterName] ?? null;

        if ($parameterValue instanceof UrlRoutable) {
            return $parameterValue;
        }

        if (enum_exists($parameterClassName) && (is_string($parameterValue) || is_int($parameterValue))) {
            /** @var class-string<UnitEnum> $parameterClassName */
            if (is_a($parameterClassName, \BackedEnum::class, true)) {
                $enum = $parameterClassName::tryFrom($parameterValue);
                if ($enum !== null) {
                    return $enum;
                }
            }
        }

        $instance = app()->make($parameterClassName);

        if (! $instance instanceof UrlRoutable) {
            return $parameterValue;
        }

        $model = $instance->resolveRouteBinding($parameterValue);

        if (! $model) {
            $modelClass = $instance instanceof Model ? $instance::class : Model::class;
            $key = is_scalar($parameterValue) ? (string) $parameterValue : '';

            throw (new ModelNotFoundException())->setModel($modelClass, [$key]);
        }

        return $model;
    }

    /**
     * @param  class-string  $componentClass
     * @return array<string, mixed>
     */
    protected function defaultModalAttributes(string $componentClass): array
    {
        $closeOnClickAway = method_exists($componentClass, 'closeModalOnClickAway')
            ? (bool) $componentClass::closeModalOnClickAway()
            : $this->configBool('livewire-modal.component_defaults.close_on_click_away', true);

        $closeOnEscape = method_exists($componentClass, 'closeModalOnEscape')
            ? (bool) $componentClass::closeModalOnEscape()
            : $this->configBool('livewire-modal.component_defaults.close_on_escape', true);

        $closeOnEscapeIsForceful = method_exists($componentClass, 'closeModalOnEscapeIsForceful')
            ? (bool) $componentClass::closeModalOnEscapeIsForceful()
            : $this->configBool('livewire-modal.component_defaults.close_on_escape_is_forceful', false);

        $dispatchCloseEvent = method_exists($componentClass, 'dispatchCloseEvent')
            ? (bool) $componentClass::dispatchCloseEvent()
            : $this->configBool('livewire-modal.component_defaults.dispatch_close_event', false);

        $destroyOnClose = method_exists($componentClass, 'destroyOnClose')
            ? (bool) $componentClass::destroyOnClose()
            : $this->configBool('livewire-modal.component_defaults.destroy_on_close', true);

        $showCloseButton = method_exists($componentClass, 'showCloseButton')
            ? (bool) $componentClass::showCloseButton()
            : $this->configBool('livewire-modal.component_defaults.show_close_button', true);

        $showBackdrop = method_exists($componentClass, 'showBackdrop')
            ? (bool) $componentClass::showBackdrop()
            : $this->configBool('livewire-modal.component_defaults.show_backdrop', true);

        if (method_exists($componentClass, 'modalMaxWidth')) {
            $raw = $componentClass::modalMaxWidth();
            $maxWidth = is_scalar($raw) ? (string) $raw : '2xl';
        } else {
            $maxWidth = $this->configString('livewire-modal.component_defaults.modal_max_width', '2xl');
        }

        if (method_exists($componentClass, 'modalMaxWidthClass')) {
            $raw = $componentClass::modalMaxWidthClass();
            $maxWidthClass = is_scalar($raw) ? (string) $raw : '';
        } else {
            $maxWidthClass = '';
        }

        if (method_exists($componentClass, 'modalSkin')) {
            $raw = $componentClass::modalSkin();
            $skin = is_scalar($raw) ? (string) $raw : 'default';
        } else {
            $skin = $this->configString('livewire-modal.skin', 'default');
        }

        return [
            'closeOnClickAway' => $closeOnClickAway,
            'closeOnEscape' => $closeOnEscape,
            'closeOnEscapeIsForceful' => $closeOnEscapeIsForceful,
            'dispatchCloseEvent' => $dispatchCloseEvent,
            'destroyOnClose' => $destroyOnClose,
            'showCloseButton' => $showCloseButton,
            'showBackdrop' => $showBackdrop,
            'maxWidth' => $maxWidth,
            'maxWidthClass' => $maxWidthClass,
            'skin' => $skin,
        ];
    }

    /**
     * @param  class-string  $componentClass
     * @param  array<string, mixed>  $arguments
     * @param  array<string, mixed>  $modalAttributes
     * @return array<string, mixed>
     */
    protected function resolveModalSettings(string $componentClass, array $arguments, array $modalAttributes): array
    {
        /** @var array<string, mixed> $settings */
        $settings = [];

        try {
            $reflect = new ReflectionClass($componentClass);
            if ($reflect->hasProperty('modalSettings')) {
                $defaults = $reflect->getDefaultProperties();
                if (isset($defaults['modalSettings']) && is_array($defaults['modalSettings'])) {
                    $settings = $this->stringifyKeys($defaults['modalSettings']);
                }
            }
        } catch (\ReflectionException) {
            // ignore
        }

        if (isset($arguments['modalSettings']) && is_array($arguments['modalSettings'])) {
            $settings = array_merge($settings, $this->stringifyKeys($arguments['modalSettings']));
        }

        if (isset($modalAttributes['modalSettings']) && is_array($modalAttributes['modalSettings'])) {
            $settings = array_merge($settings, $this->stringifyKeys($modalAttributes['modalSettings']));
        }

        // Map legacy keys onto modalAttributes-compatible options.
        if (array_key_exists('overlay', $settings)) {
            $settings['showBackdrop'] = (bool) $settings['overlay'];
        }
        if (array_key_exists('overlayClose', $settings)) {
            $settings['closeOnClickAway'] = (bool) $settings['overlayClose'];
        }

        return $settings;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function extractArguments(array $payload): array
    {
        $skip = ['component', 'arguments', 'attributes', 'modalAttributes'];
        $out = [];
        foreach ($payload as $key => $value) {
            if (! in_array($key, $skip, true)) {
                $out[$key] = $value;
            }
        }

        return $out;
    }

    protected function popStack(bool $destroy = true): ?string
    {
        if ($this->stack === []) {
            return null;
        }

        $id = array_pop($this->stack);

        if ($id === null) {
            return null;
        }

        $attrs = $this->components[$id]['modalAttributes'] ?? [];
        $shouldDestroy = $destroy || (($attrs['destroyOnClose'] ?? true) === true);

        if ($shouldDestroy) {
            unset($this->components[$id]);
        }

        return $id;
    }

    /**
     * @param  array<mixed>  $input
     * @return array<string, mixed>
     */
    protected function stringifyKeys(array $input): array
    {
        $out = [];
        foreach ($input as $key => $value) {
            $out[(string) $key] = $value;
        }

        return $out;
    }

    protected function configInt(string $key, int $default): int
    {
        $value = config($key, $default);

        return is_numeric($value) ? (int) $value : $default;
    }

    protected function configBool(string $key, bool $default): bool
    {
        $value = config($key, $default);

        return is_bool($value) ? $value : (bool) $value;
    }

    protected function configString(string $key, string $default): string
    {
        $value = config($key, $default);

        return is_scalar($value) ? (string) $value : $default;
    }
}
