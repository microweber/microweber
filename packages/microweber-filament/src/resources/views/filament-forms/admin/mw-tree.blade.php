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
        // Resolve human titles for the pre-selected nodes. The tree lazy-loads its
        // node list (empty until the user searches), so the preselected tags have
        // no node to read a title from and render the literal string "undefined".
        // Passing the title in selectedData lets the tag component (tags.js
        // dataTitle -> data[map.title]) show the real page/category name up-front.
        if (isset($selectedPage) && $selectedPage) {
            $pageTitle = \Modules\Content\Models\Content::query()
                ->whereKey($selectedPage)->value('title');
            $options['selectedData'][] = [
                'id' => $selectedPage,
                'type' => 'page',
                'title' => $pageTitle ?: ('#' . $selectedPage),
            ];
        }

        if (isset($selectedCategories) && is_array($selectedCategories) && !empty($selectedCategories)) {
            $categoryTitles = \Modules\Category\Models\Category::query()
                ->whereIn('id', $selectedCategories)
                ->pluck('title', 'id');
            foreach ($selectedCategories as $categoryId) {
                $options['selectedData'][] = [
                    'id' => $categoryId,
                    'type' => 'category',
                    'title' => $categoryTitles[$categoryId] ?? ('#' . $categoryId),
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
