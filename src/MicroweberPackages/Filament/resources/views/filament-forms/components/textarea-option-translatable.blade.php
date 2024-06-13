@php
    use Filament\Support\Facades\FilamentView;

    $hasInlineLabel = $hasInlineLabel();
    $isConcealed = $isConcealed();
    $isDisabled = $isDisabled();
    $rows = $getRows();
    $shouldAutosize = $shouldAutosize();
    $statePath = $getStatePath();

    $initialHeight = (($rows ?? 2) * 1.5) + 0.75;
@endphp

<div>

    <div>
        <div>
            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="mountedTableActionsData.0.name">


    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
         {{ $getLabel() }}
    </span>


            </label>
        </div>
    <x-filament::tabs>

    @foreach($supportedLanguages as $supportedLanguage)

            <x-filament::tabs.item
                x-on:click="function() {

                        $store.translationLocale.setLocale('{{$supportedLanguage['locale']}}');

                    }"
                alpine-active="$store.translationLocale.locale === '{{$supportedLanguage['locale']}}'"

            >
                <div class="flex gap-2 items-center uppercase">
                    <img width="14px" src="{{$supportedLanguage['iconUrl']}}" />
                    {{ $supportedLanguage['abr'] }}
                </div>

            </x-filament::tabs.item>

    @endforeach

    </x-filament::tabs>

    @foreach($supportedLanguages as $supportedLanguage)

        <div x-show="$store.translationLocale.locale === '{{$supportedLanguage['locale']}}'">

            <x-dynamic-component
                :component="$getFieldWrapperView()"
                :field="$field"
                :has-inline-label="$hasInlineLabel"
            >
                <x-slot
                    name="label"
                    @class([
                        'sm:pt-1.5' => $hasInlineLabel,
                    ])
                >
                </x-slot>

                <x-filament::input.wrapper
                    :disabled="$isDisabled"
                    :valid="! $errors->has($statePath)"
                    :attributes="
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class(['fi-fo-textarea overflow-hidden'])
        "
                >
        <textarea
            @if ($shouldAutosize)
                @if (FilamentView::hasSpaMode())
                    ax-load="visible"
            @else
                ax-load
            @endif
            ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('textarea', 'filament/forms') }}"
            x-data="textareaFormComponent({ initialHeight: @js($initialHeight) })"
            x-ignore
            x-intersect.once="render()"
            x-on:input="render()"
            x-on:resize.window="render()"
            wire:ignore.style.height
                {{ $getExtraAlpineAttributeBag() }}
            @endif
            {{
                $getExtraInputAttributeBag()
                    ->merge([
                        'autocomplete' => $getAutocomplete(),
                        'autofocus' => $isAutofocused(),
                        'cols' => $getCols(),
                        'disabled' => $isDisabled,
                        'id' => $getId(). '.' . $supportedLanguage['locale'].'',
                        'maxlength' => (! $isConcealed) ? $getMaxLength() : null,
                        'minlength' => (! $isConcealed) ? $getMinLength() : null,
                        'placeholder' => $getPlaceholder(),
                        'readonly' => $isReadOnly(),
                        'required' => $isRequired() && (! $isConcealed),
                        'rows' => $rows,
                        $applyStateBindingModifiers('wire:model') => $statePath. '.' . $supportedLanguage['locale'].'',
                    ], escape: false)
                    ->class([
                        'block w-full border-none bg-transparent px-3 py-1.5 text-base text-gray-950 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6',
                        'resize-none' => $shouldAutosize,
                    ])
                    ->style([
                        "height: {$initialHeight}rem" => $shouldAutosize,
                    ])
            }}
        ></textarea>
                </x-filament::input.wrapper>
            </x-dynamic-component>

        </div>

    @endforeach


    </div>

</div>
