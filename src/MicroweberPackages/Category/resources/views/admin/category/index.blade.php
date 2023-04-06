@extends('admin::layouts.app')

@section('content')

    <module type="categories/manage" @if(isset($isShop)) is_shop="1" @endif />

@endsection
