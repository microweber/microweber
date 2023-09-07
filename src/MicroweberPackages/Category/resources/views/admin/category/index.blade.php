@php

    $options = [];
    if (isset($isIframe) && $isIframe == true) {
        $options = [
            'disableNavBar' => true,
            'disableTopBar' => true,
        ];
    }
@endphp

@extends('admin::layouts.app', $options)

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
