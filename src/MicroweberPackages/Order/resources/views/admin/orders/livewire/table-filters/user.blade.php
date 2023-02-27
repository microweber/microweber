{{--Registered User--}}
<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">
    @php
        $selectedItem = [];
        if (isset($filters['userId'])) {
            $selectedItem = $filters['userId'];
        }
    @endphp
    @livewire('admin-filter-item-users', [
        'name'=>'Registered User',
        'selectedItem'=>$selectedItem,
        'showDropdown'=> session()->get('showFilterRegisteredUser'),
        'onChangedEmitEvents' => [
            'setFirstPageOrdersList'
        ]
    ])
</div>
