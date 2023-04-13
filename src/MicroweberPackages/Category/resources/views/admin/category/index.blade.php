@extends('admin::layouts.app')

@section('content')

    <div class="px-5">
        <module type="categories/manage" id="category-manage" @if(isset($isShop)) is_shop="1" @endif />
    </div>

@endsection
