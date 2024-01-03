@extends('microweber-module-newsletter::admin.layout')

@section('content-admin-newsletter')

    <div>
        @livewire('admin-newsletter-dashboard-stats')
        <module type="newsletter/campaigns"/>
    </div>

@endsection
