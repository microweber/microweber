@php
$extendParams = [];
$extendParams['disableNavBar'] = true;
$extendParams['disableTopBar'] = true;
$extendParams['iframe'] = true;
@endphp

@extends('admin::layouts.app', $extendParams)

@section('content')
<div>
    @livewire($componentName, $componentAttributes, $livewireId)
</div>
@endsection
