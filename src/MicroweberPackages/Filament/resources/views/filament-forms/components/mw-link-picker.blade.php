@php
    use Filament\Forms\Components\TextInput\Actions\HidePasswordAction;
    use Filament\Forms\Components\TextInput\Actions\ShowPasswordAction;

    $datalistOptions = $getDatalistOptions();
    $extraAlpineAttributes = $getExtraAlpineAttributes();
    $hasInlineLabel = $hasInlineLabel();
    $id = $getId();
    $isConcealed = $isConcealed();
    $isDisabled = $isDisabled();
    $isPasswordRevealable = $isPasswordRevealable();
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

    if ($isPasswordRevealable) {
        $xData = '{ isPasswordRevealed: false }';
    } elseif (count($extraAlpineAttributes) || filled($mask)) {
        $xData = '{}';
    } else {
        $xData = null;
    }

    if ($isPasswordRevealable) {
        $type = null;
    } elseif (filled($mask)) {
        $type = 'text';
    } else {
        $type = $getType();
    }

    $selectedData = $getSelectedData();
    $contentId = $getContentId();
    $categoryId = $getCategoryId();
    $url = $getUrl();
@endphp

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
        {{ $getLabel() }}
    </x-slot>

    <div

        x-data="{
            statePreview: '{{$url}}',
            categoryId: '{{$categoryId}}',
            contentId: '{{$contentId}}',
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
        }"

        x-cloak
        wire:ignore
    >
        <input

            {{
               $getExtraInputAttributeBag()->merge(
                   [
                       'id' => $getId() . 'json',
                       $applyStateBindingModifiers('wire:model') => $statePath,
                   ], escape: false)
                }}


            type="hidden">

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
                ->class(['fi-fo-text-input overflow-hidden'])
        "
    >

        <x-filament::input


            x-on:click="function() {

                var selectedIndex = 0;

                if (categoryId) {
                    selectedIndex = 1;
                }
                if (contentId) {
                    selectedIndex = 2;
                }

               var linkEditor = new mw.LinkEditor({
                    mode: 'dialog',
                    selectedIndex: selectedIndex
                });

                if (categoryId > 0) {
                   linkEditor.setValue({id: categoryId, type: 'category'})
                } else if (contentId > 0) {
                    linkEditor.setValue({id: contentId, type: 'content'})
                } else (url) {
                    linkEditor.setValue(url);
                }

                linkEditor.promise().then((data) => {
                    var modal = linkEditor.dialog;
                    if(data) {
                       state = data;
                       statePreview = data.url;
                    }
                    modal.remove();

                });

            }"


            :attributes="
                \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                    ->merge($extraAlpineAttributes, escape: false)
                    ->merge([
                        'autocapitalize' => $getAutocapitalize(),
                        'autocomplete' => $getAutocomplete(),
                        'autofocus' => $isAutofocused(),
                        'disabled' => $isDisabled,
                        'id' => $id,
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
                        'type' => $type,
                        'x-model'=> 'statePreview',
                        'x-bind:type' => $isPasswordRevealable ? 'isPasswordRevealed ? \'text\' : \'password\'' : null,
                        'x-mask' . ($mask instanceof \Filament\Support\RawJs ? ':dynamic' : '') => filled($mask) ? $mask : null,
                    ], escape: false)
                    ->class([
                        '[&::-ms-reveal]:hidden' => $isPasswordRevealable,
                    ])
            "
        />
    </x-filament::input.wrapper>

    @if ($datalistOptions)
        <datalist id="{{ $id }}-list">
            @foreach ($datalistOptions as $option)
                <option value="{{ $option }}" />
            @endforeach
        </datalist>
    @endif

    </div>
</x-dynamic-component>
