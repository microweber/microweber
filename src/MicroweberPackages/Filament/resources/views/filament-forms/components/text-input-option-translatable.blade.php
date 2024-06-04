@php
    $datalistOptions = $getDatalistOptions();
    $extraAlpineAttributes = $getExtraAlpineAttributes();
    $id = $getId();
    $isConcealed = $isConcealed();
    $isDisabled = $isDisabled();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $mask = $getMask();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixLabel = $getSuffixLabel();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
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
        class="fi-fo-text-input"
        :attributes="
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class([])
        "
    >

        <div
        >
        @foreach($supportedLocales as $supportedLocale)

            <div x-show="$store.translationLocale.locale == '{{$supportedLocale}}'">
              <x-filament::input
            :attributes="
                \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                    ->merge($extraAlpineAttributes, escape: false)
                    ->merge([
                        'autocapitalize' => $getAutocapitalize(),
                        'autocomplete' => $getAutocomplete(),
                        'autofocus' => $isAutofocused(),
                        'disabled' => $isDisabled,
                        'id' => $id . '[' . $supportedLocale.']',
                        'inlinePrefix' => $isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel)),
                        'inlineSuffix' => $isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel)),
                        'inputmode' => $getInputMode(),
                        'list' => $datalistOptions ? $id . '-list' : null,
                        'max' => (! $isConcealed) ? $getMaxValue() : null,
                        'maxlength' => (! $isConcealed) ? $getMaxLength() : null,
                        'min' => (! $isConcealed) ? $getMinValue() : null,
                        'minlength' => (! $isConcealed) ? $getMinLength() : null,
                        'placeholder' => $getPlaceholder(),
                        'readonly' => $isReadOnly(),
                        'required' => $isRequired() && (! $isConcealed),
                        'step' => $getStep(),
                        'type' => blank($mask) ? $getType() : 'text',
                        $applyStateBindingModifiers('wire:model') => $statePath . '[' . $supportedLocale.']',
                        'x-data' => (count($extraAlpineAttributes) || filled($mask)) ? '{}' : null,
                        'x-mask' . ($mask instanceof \Filament\Support\RawJs ? ':dynamic' : '') => filled($mask) ? $mask : null,
                    ], escape: false)
            "
        />
            </div>

        @endforeach



        <x-slot:suffix>
            <x-filament::dropdown placement="bottom-end" size="xs">
                <x-slot name="trigger">
                    <button>
                        <div class="flex gap-2 items-center uppercase">
                            <img width="14px" :src="$store.translationLocale.flagUrl" />
                            <span x-text="$store.translationLocale.locale"></span>
                        </div>
                    </button>
                </x-slot>

                <x-filament::dropdown.list>

                    @foreach($supportedLocales as $supportedLocale)
                    <x-filament::dropdown.list.item x-on:click="function() {

                        $store.translationLocale.setLocale('{{$supportedLocale}}');
                        close();

                    }">
                        <div class="flex gap-2 items-center uppercase">
                            <img width="24px" src="{{get_flag_icon_url($supportedLocale)}}" />
                            {{ $supportedLocale }}
                        </div>
                    </x-filament::dropdown.list.item>
                    @endforeach

                </x-filament::dropdown.list>
            </x-filament::dropdown>
        </x-slot:suffix>

        </div>

    </x-filament::input.wrapper>

</x-dynamic-component>
