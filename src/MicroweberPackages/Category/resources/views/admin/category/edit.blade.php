@extends('admin::layouts.app')

@section('content')

    <div class="d-flex">

    <script>
        mw.require('content.js', true);
    </script>

    <div class="module-content w-75 pe-3 mx-auto">
        <div class="row">
            <div class="col-md-12">
                @php
                    $isShopAttribute = 0;
                    if (isset($isShop)) {
                         $isShopAttribute = 1;
                    }
                @endphp

                @if($isShopAttribute == 1)
                    <h3> Add category to Shop</h3>
                @else
                   <h3> Add category to Website</h3>
                @endif

                <module type="categories/edit_category" is_shop="{{$isShopAttribute}}" id="admin-category-edit" data-category-id="{{$id}}"  />
            </div>
        </div>
    </div>

</div>
@endsection
