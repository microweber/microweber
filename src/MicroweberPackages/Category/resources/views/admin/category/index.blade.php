@extends('admin::layouts.app')

@section('content')

    <div class="col-xxl-10 col-lg-11 col-12 mx-auto">
        @php
            $isShopAttribute = 0;
            if (isset($isShop)) {
                 $isShopAttribute = 1;
            }
        @endphp

        <livewire:admin-category-manage is_shop="{{$isShopAttribute}}" />

    </div>

@endsection
