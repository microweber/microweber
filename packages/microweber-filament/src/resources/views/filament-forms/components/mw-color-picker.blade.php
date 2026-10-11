@php
    use Filament\Support\Facades\FilamentView;

    $isDisabled = $isDisabled();
    $isLive = $isLive();
    $isLiveOnBlur = $isLiveOnBlur();
    $isLiveDebounced = $isLiveDebounced();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $liveDebounce = $getLiveDebounce();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixLabel = $getSuffixLabel();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center"
>



    <x-filament::input.wrapper
        :disabled="$isDisabled"
        :inline-prefix="$isPrefixInline"
        :inline-suffix="$isSuffixInline"
        :prefix="$prefixLabel"
        :prefix-actions="$prefixActions"
        :prefix-icon="$prefixIcon"
        :prefix-icon-color="$getPrefixIconColor()"
        :suffix="$suffixLabel"
        :suffix-actions="$suffixActions"
        :suffix-icon="$suffixIcon"
        :suffix-icon-color="$getSuffixIconColor()"
        :valid="! $errors->has($statePath)"
        :attributes="
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class('fi-fo-color-picker')
        "
    >
        <div
            wire:ignore
            x-data="{
                state: $wire.$entangle('{{ $statePath }}'),
                isAutofocused: @js($isAutofocused()),
                isDisabled: @js($isDisabled),
                isLive: @js($isLive),
                isLiveDebounced: @js($isLiveDebounced),
                isLiveOnBlur: @js($isLiveOnBlur),
                liveDebounce: @js($liveDebounce),
                mwColorPicker: null,

                init() {
                    if (!(this.state === null || this.state === '')) {
                        this.setState(this.state)
                    }

                    if (this.isAutofocused) {
                        this.togglePanelVisibility()
                    }

                    this.$refs.input.addEventListener('change', (event) => {
                        this.setState(event.target.value)
                        if (this.mwColorPicker && this.mwColorPicker.setColor) {
                            this.mwColorPicker.setColor(event.target.value)
                        }
                    })

                    // Watch for state changes to keep picker in sync
                    this.$watch('state', (newValue, oldValue) => {
                        if (newValue !== oldValue && this.mwColorPicker && this.mwColorPicker.setColor) {
                            this.mwColorPicker.setColor(newValue)
                        }
                    })

                    if (this.isLive || this.isLiveDebounced || this.isLiveOnBlur) {
                        new MutationObserver(() =>
                            this.isOpen() ? null : this.commitState(),
                        ).observe(this.$refs.panel, {
                            attributes: true,
                            childList: true,
                        })
                    }
                },

                initColorPicker() {
                    if (typeof mw !== 'undefined' && mw.colorPicker) {
                        // Destroy existing picker if it exists
                        if (this.mwColorPicker && this.mwColorPicker.destroy) {
                            this.mwColorPicker.destroy()
                        }

                        // Clear the container
                        // this.$refs.colorPickerContainer.innerHTML = ''

                        // Create new picker
                        this.mwColorPicker = mw.colorPicker({
                            element: this.$refs.colorPickerContainer,
                           // mode: 'inline',
                            onchange: (color) => {
                                if (this.state !== color) {
                                    this.setState(color)
                                    this.handleColorChange(color)
                                }
                            }
                        })

                        // Set initial color if we have one
                        if (this.state && this.mwColorPicker.setColor) {
                            this.mwColorPicker.setColor(this.state)
                        }
                    }
                },

                handleColorChange(color) {
                    if (this.isLiveOnBlur || !(this.isLive || this.isLiveDebounced)) {
                        return
                    }

                    setTimeout(
                        () => {
                            if (this.state !== color) {
                                return
                            }

                            this.commitState()
                        },
                        this.isLiveDebounced ? this.liveDebounce : 250,
                    )
                },

                togglePanelVisibility() {
                    if (this.isDisabled) {
                        return
                    }

                    const panel = this.$refs.panel
                    if (panel.style.display === 'block') {
                        panel.style.display = 'none'
                    } else {
                        panel.style.display = 'block'
                        // Initialize color picker when panel opens
                        setTimeout(() => {
                            this.initColorPicker()
                        }, 100)
                    }
                },

                setState(value) {
                    this.state = value
                    this.$refs.input.value = value

                    // Update the color preview
                    if (this.$refs.colorPreview) {
                        this.$refs.colorPreview.style.backgroundColor = value
                    }
                },

                isOpen() {
                    return this.$refs.panel.style.display === 'block'
                },

                commitState() {
                    if (
                        JSON.stringify(this.$wire.__instance.canonical) ===
                        JSON.stringify(this.$wire.__instance.ephemeral)
                    ) {
                        return
                    }

                    this.$wire.$commit()
                },
            }"
            x-on:keydown.esc="isOpen() && $event.stopPropagation()"
            {{ $getExtraAlpineAttributeBag()->class(['flex']) }}
        >
            <x-filament::input
                x-on:focus="togglePanelVisibility()"
                x-on:keydown.enter.stop.prevent="togglePanelVisibility()"
                x-ref="input"
                :attributes="
                    \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                        ->merge([
                            'autocomplete' => 'off',
                            'disabled' => $isDisabled,
                            'id' => $getId(),
                            'inlinePrefix' => $isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel)),
                            'inlineSuffix' => $isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel)),
                            'placeholder' => $getPlaceholder(),
                            'required' => $isRequired() && (! $isConcealed()),
                            'type' => 'text',
                            'x-model' . ($isLiveDebounced ? '.debounce.' . $liveDebounce : null) => 'state',
                            'x-on:blur' => $isLiveOnBlur ? 'isOpen() ? null : commitState()' : null,
                        ], escape: false)
                "
            />

            <div
                class="flex min-h-full items-center pe-3"
                x-on:click="togglePanelVisibility()"
            >
                <div
                    x-ref="colorPreview"
                    class="h-5 w-5 select-none rounded-full"
                    x-bind:class="{
                        'ring-1 ring-inset ring-gray-200 dark:ring-white/10': ! state,
                    }"
                    x-bind:style="{ 'background-color': state }"
                ></div>
            </div>

            <div
                wire:ignore.self
                wire:key="{{ $this->getId() }}.{{ $statePath }}.{{ $field::class }}.panel"
                x-cloak
                x-ref="panel"
                class="fi-fo-color-picker-panel absolute z-20 hidden rounded-lg shadow-lg p-4 bg-white dark:bg-gray-800"
                style="display: none;"
            >
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Choose Color</span>
                    {{-- task-2026-05-31 AI-817 follow-up: close-X button was icon-only with no accessible name —
                         screen readers announced "button" with no purpose. WCAG 4.1.2 Level A failure.
                         Carries to every ColorPicker consumer (Logo / Social-links / Background / Theme). --}}
                    <button
                        type="button"
                        x-on:click="togglePanelVisibility()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        aria-label="Close color picker"
                        title="Close color picker"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div wire:ignore x-ref="colorPickerContainer" class="mw-color-picker-container"></div>
            </div>
        </div>
    </x-filament::input.wrapper>
</x-dynamic-component>
