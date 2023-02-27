<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">
    @php
        $data = [];

        $data[] = [
            'key'=>'any',
            'value'=>'Any',
        ];

        foreach (app()->shipping_manager->getShippingModules() as $shippingModule) {
            $data[] = [
                'key'=> $shippingModule['module'],
                'value'=> $shippingModule['name'],
            ];
        }

        $selectedItem = [];
        if (isset($filters['shippingService'])) {
            $selectedItem = $filters['shippingService'];
        }
    @endphp
    @livewire('admin-filter-item', [
        'name'=>'Shipping Type',
        'searchable'=>false,
        'selectedItem'=>$selectedItem,
        'selectedItemKey'=>'shippingService',
        'data'=>$data,
        'showDropdown'=> session()->get('showFilterShippingService'),
        'onChangedEmitEvents' => [
          'setFirstPageOrdersList'
        ]
    ])
</div>
