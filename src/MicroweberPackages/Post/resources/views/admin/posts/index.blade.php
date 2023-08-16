@php
$extendParams = [];
if(isset($isIframe) && $isIframe == true) {
    $extendParams['disableNavBar'] = true;
    $extendParams['disableTopBar'] = true;
}
@endphp

@extends('admin::layouts.app', $extendParams)

@section('content')

    <div class="d-flex">

        @include('content::admin.content.index-page-category-tree', [
             'is_blog'=>1,
         ])

        <div class="module-content col-xxl-10 col-lg-11 col-12 mx-auto">

            <livewire:admin-posts-list />
            <livewire:admin-content-bulk-options />

        </div>
    </div>

    @include('content::admin.content.index-scripts')

@endsection
