<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">
    @php
        $selectedItem = [];
        if (isset($filters['productId'])) {
            $selectedItem = $filters['productId'];
        }
    @endphp
    @livewire('admin-filter-item-product', [
        'name'=>'Product',
        'selectedItem'=>$selectedItem,
        'showDropdown'=> session()->get('showFilterProductId'),
        'onChangedEmitEvents' => [
          'setFirstPageOrdersList'
        ]
    ])
</div>
