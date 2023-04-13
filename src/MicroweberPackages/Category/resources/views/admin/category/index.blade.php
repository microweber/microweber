@extends('admin::layouts.app')

@section('content')

    <div class="px-5">

        @php
        $enableShop = 0;
        if(isset($isShop)) {
            $enableShop = 1;
        }
        @endphp

        <module type="categories/manage" is_shop="{{$enableShop}}" id="category-manage" />

    </div>

@endsection
