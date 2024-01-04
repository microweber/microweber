@extends('microweber-module-newsletter::admin.layout')

@section('content-admin-newsletter')

    <div>
        <module type="newsletter/privacy_settings" data-no-hr="true"/>
        <module type="newsletter/settings" />
    </div>

@endsection
