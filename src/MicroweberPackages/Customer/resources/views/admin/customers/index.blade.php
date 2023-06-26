@extends('customer::admin.layout')

@section('icon')
<i class="mdi mdi-account-search module-icon-svg-fill"></i>
@endsection

@section('title', _e('Customers', true))

@section('content')

<div>
    <livewire:admin-customers-list />
</div>

@endsection
