{{--Customer--}}
<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">
    @php
        $selectedItems = [];
        if (isset($filters['customer'])) {
           // $selectedItems = explode(',', $filters['customer']);
        }
    @endphp
    @livewire('admin-filter-item-users', [
        'name'=>'Customer',
        'selectedItems'=>$selectedItems,
        'showDropdown'=> session()->get('showFilterCustomer'),
        'onChangedEmitEvents' => [
         'setFirstPageOrdersList'
        ]
    ])
</div>
