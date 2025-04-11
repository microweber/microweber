@props(['selectedCategories', 'selectedPage', 'skipCategories', 'contentType', 'skipPageId', 'isShopFilter'])

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
>
    @php
        $id = $this->getId();
        $statePath = $this->getStatePath();
        $suffix = $this->getId();

        // Prepare the options array
        $options = [
            'suffix' => $suffix,
            'selectable' => true,
        ];

        if (isset($singleSelect) && $singleSelect) {
            $options['singleSelect'] = true;
        }

        // Prepare selected data
        if (isset($selectedPage)) {
            $options['selectedData'][] = [
                'id' => $selectedPage,
                'type' => 'page'
            ];
        }

        if (isset($selectedCategories)) {
            foreach ($selectedCategories as $category) {
                $options['selectedData'][] = [
                    'id' => $category,
                    'type' => 'category'
                ];
            }
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
            options: {{ json_encode($options, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) }},
            params: {{ json_encode($params, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) }}
        })"

    >
        <div wire:ignore id="mw-tree-edit-content-{{$suffix}}"></div>
    </div>
</x-dynamic-component>
