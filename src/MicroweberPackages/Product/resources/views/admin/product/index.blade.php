@extends('admin::layouts.app')

@section('content')
<div class="d-flex">

    @include('content::admin.content.index-page-category-tree', [
       'is_shop'=>1,
   ])

    <div class="module-content w-75 pe-3">

        <livewire:admin-products-list />
        <livewire:admin-content-bulk-options />

    </div>
</div>

@include('content::admin.content.index-scripts')
@endsection
