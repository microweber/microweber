@extends('admin::layouts.app')

@section('content')

    <div class="d-flex">

        <livewire:admin-marketplace />

    </div>

    @include('content::admin.content.index-scripts')

@endsection
