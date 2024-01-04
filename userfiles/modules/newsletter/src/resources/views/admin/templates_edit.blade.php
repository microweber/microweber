@extends('microweber-module-newsletter::admin.layout')

@section('content-admin-newsletter')

    <div>
        <module type="newsletter/edit_template" template-id="{{$templateId}}" />
    </div>

@endsection
