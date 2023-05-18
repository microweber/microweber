@extends('admin::layouts.app')

@section('content')

    <div class="px-5">
        @php
            $isShopAttribute = 0;
            if (isset($isShop)) {
                 $isShopAttribute = 1;
            }
        @endphp
        <module type="categories/manage" id="category-manage" is_shop="{{$isShopAttribute}}" />
    </div>

@endsection
