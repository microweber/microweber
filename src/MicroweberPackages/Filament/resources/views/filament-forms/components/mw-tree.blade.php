@php
    use Filament\Support\Facades\FilamentView;

    $id = $getId();
    $statePath = $getStatePath();
@endphp
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
>


    @php
        $id = $this->getId();
      //  $statePath = $this->getStatePath();
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

        // Prepare selected data
        if (isset($selectedPage) and $selectedPage) {
            $options['selectedData'][] = [
                'id' => $selectedPage,
                'type' => 'page'
            ];
        }

        if (isset($selectedCategories) and $selectedCategories) {
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



    <?php

    /*
    @if($this->data)
    {{json_encode($this->data['mw_parent_page_and_category_state'],JSON_PRETTY_PRINT)}}
    @endif
     */
    ?>

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


</x-dynamic-component>


