{{--
<div class=" col-12 col-sm-6 col-md-4 col-lg-4 mb-4">


</div>
--}}



<div class="ms-0 ms-md-2 mb-3 mb-md-0">

    @php
        $itemValue = '';
        if (isset($filters['priceRange'])) {
            $itemValue = $filters['priceRange'];
        }
    @endphp

    @livewire('admin-filter-item-value-range', [
        'name'=>'Price Range',
        'itemValue'=>$itemValue,
        'itemValueKey'=>'priceBetween'
    ])

</div>

