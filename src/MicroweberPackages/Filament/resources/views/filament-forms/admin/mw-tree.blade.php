@props(['selectedCategories', 'selectedPage', 'skipCategories', 'contentType', 'skipPageId', 'isShopFilter'])

@php
    $statePath = $getStatePath();
@endphp
<x-dynamic-component
    :component="$getFieldWrapperView()"
>

<div>
    @php
        // Unique per tree field: $this->getId() is the Livewire component id,
        // which is the SAME for every mw-tree on the form — so without the
        // per-field treeKey, two trees (e.g. a post's Parent page + Categories)
        // share one container id and render into the same spot (duplicated).
        $suffix = $this->getId() . '-' . ($treeKey ?? 'main');

        // Prepare the options array
        $options = [
            'suffix' => $suffix,
            'selectable' => true,
              'selectedData' => [],
        ];

        if (isset($singleSelect) && $singleSelect) {
            $options['singleSelect'] = true;
        }
        if (isset($selectedPage) && $selectedPage) {
            $options['selectedData'][] = [
                'id' => $selectedPage,
                'type' => 'page',
            ];
        }

        if (isset($selectedCategories) && is_array($selectedCategories) && !empty($selectedCategories)) {
            foreach ($selectedCategories as $categoryId) {
                $options['selectedData'][] = [
                    'id' => $categoryId,
                    'type' => 'category',
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
        wire:ignore
        x-data="mwTreeFormComponent({
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            options: {{ json_encode($options, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) }},
            params: {{ json_encode($params, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) }}
        })"
    >
        <div id="mw-tree-edit-content-{{$suffix}}"></div>
    </div>
</div>

</x-dynamic-component>
