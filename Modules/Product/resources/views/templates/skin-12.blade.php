@php
/*

type: layout

name: Product Cards

description: Products using x-product-card component

*/
@endphp

<script>
    mw.require('shop.js');
</script>

<x-row class="g-4" id="products-{{ $params['id'] }}">
    @if(empty($data))
        <p class="mw-pictures-clean">No products added. Please add products to the module.</p>
    @else
        @foreach($data as $item)
            @php
                $itemData = content_data($item['id']);
                $in_stock = !isset($itemData['qty']) || $itemData['qty'] == 'nolimit' || intval($itemData['qty']) > 0;
                $firstPrice = '';
                $originalPrice = '';
                if(isset($item['prices']) && is_array($item['prices'])){
                    $vals = array_values($item['prices']);
                    $firstPrice = !empty($vals) ? currency_format(reset($vals)) : '';
                }
                if(isset($item['original_price']) && $item['original_price'] != ''){
                    $originalPrice = currency_format($item['original_price']);
                }
            @endphp
            <x-col size="12" size-md="6" size-lg="4" size-xl="3">
                <x-product-card
                    :title="$item['title'] ?? ''"
                    :image="$item['image'] ?? ''"
                    :link="$item['link'] ?? ''"
                    :price="$firstPrice"
                    :original-price="$originalPrice"
                    :description="\Illuminate\Support\Str::limit($item['description'] ?? '', 100)"
                    :in-stock="$in_stock"
                    :content-id="$item['id']"
                    :add-to-cart-text="$add_to_cart_text ?? __('Add to cart')"
                    class="shadow-sm"
                    itemscope
                    itemtype="{{ $schema_org_item_type_tag }}"
                />
            </x-col>
        @endforeach
    @endif
</x-row>

@if(isset($pages_count) && $pages_count > 1 && isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif