@extends('admin::layouts.app')

@section('content')

    <div class="px-5">
        @php
            $isShopAttribute = 0;
            if (isset($isShop)) {
                 $isShopAttribute = 1;
            }
        @endphp

        <livewire:admin-category-manage is_shop="{{$isShopAttribute}}" />

    </div>

@endsection
