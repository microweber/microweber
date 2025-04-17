@props(['selectedCategories', 'selectedPage', 'skipCategories', 'contentType', 'skipPageId', 'isShopFilter'])


@php
    use Filament\Support\Facades\FilamentView;

    $id = $getId();
    $statePath = $getStatePath();
@endphp
<x-dynamic-component
    :component="$getFieldWrapperView()"
>


    @php
        $suffix = '';

        $suffix = $this->getId();



    @endphp




<div>
    @php

        $id = $this->getId();
        $suffix = $this->getId();

        // Prepare the options array
        $options = [
            'suffix' => $suffix,
            'selectable' => true,
              'selectedData' => [],
        ];

        if (isset($singleSelect) && $singleSelect) {
            $options['singleSelect'] = true;
        }

        // Prepare params array
        $params = [];
        if (isset($skipCategories) && $skipCategories) {
            $params['skip_categories'] = 1;
        }
        if (isset($contentType) && $contentType) {
            $params['content_type'] = $contentType;
        }
        if (isset($isShopFilter)) {
            $params['is_shop'] = intval($isShopFilter);
        }
    @endphp




    <div

        ax-load="visible"

        ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('mw-tree-component', 'mw-filament/forms') }}"

        x-data="mwTreeFormComponent({
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            options: {{ json_encode($options) }},
            params: {{ json_encode($params) }}
        })"

    >
        <div wire:ignore id="mw-tree-edit-content-{{$suffix}}"></div>
    </div>
</div>

</x-dynamic-component>


