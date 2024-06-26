@php
    use Filament\Support\Facades\FilamentView;

    $id = $getId();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @if ($isDisabled())
        <div
            x-data="{
                state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            }"
            x-html="state"
            class="fi-fo-rich-editor fi-disabled prose block w-full max-w-none rounded-lg bg-gray-50 px-3 py-3 text-gray-500 shadow-sm ring-1 ring-gray-950/10 dark:prose-invert dark:bg-transparent dark:text-gray-400 dark:ring-white/10 sm:text-sm"
        ></div>
    @else
        <x-filament::input.wrapper
            :valid="! $errors->has($statePath)"
            :attributes="
                \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                    ->class(['fi-fo-rich-editor max-w-full overflow-x-auto'])">
            <div
                x-data="richEditorFormComponent({
                            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: false) }},
                        })"
                x-ignore
                x-on:trix-attachment-add="
                    if (! $event.attachment.file) return

                    let attachment = $event.attachment

                    $wire.upload(
                        `componentFileAttachments.{{ $statePath }}`,
                        attachment.file,
                        () => {
                            $wire
                                .getFormComponentFileAttachmentUrl('{{ $statePath }}')
                                .then((url) => {
                                    attachment.setAttributes({
                                        url: url,
                                        href: url,
                                    })
                                })
                        },
                    )
                "
                x-on:trix-change="
                    let value = $event.target.value

                    $nextTick(() => {
                        if (! $refs.trix) {
                            return
                        }

                        state = value
                    })
                "
                @if ($isLiveDebounced())
                    x-on:trix-change.debounce.{{ $getLiveDebounce() }}="
                        $nextTick(() => {
                            if (! $refs.trix) {
                                return
                            }

                            $wire.call('$refresh')
                        })
                    "
                @endif

                {{ $getExtraAlpineAttributeBag() }}
            >
                <input
                    id="trix-value-{{ $id }}"
                    x-ref="trixValue"
                    type="text"
                />


                <textarea
                    @if ($isAutofocused())
                        autofocus
                    @endif
                    id="{{ $id }}"
                    input="trix-value-{{ $id }}"
                    placeholder="{{ $getPlaceholder() }}"
                    toolbar="trix-toolbar-{{ $id }}"
                    @if ($isLiveOnBlur())
                        x-on:blur="$wire.call('$refresh')"
                    @endif
                    x-ref="trix"
                    wire:ignore
                    {{
                        $getExtraInputAttributeBag()->class([
                            'prose min-h-[theme(spacing.48)] max-w-none !border-none px-3 py-1.5 text-base text-gray-950 dark:prose-invert focus-visible:outline-none dark:text-white sm:text-sm sm:leading-6',
                        ])
                    }}
                ></textarea>
            </div>
        </x-filament::input.wrapper>
    @endif
</x-dynamic-component>
