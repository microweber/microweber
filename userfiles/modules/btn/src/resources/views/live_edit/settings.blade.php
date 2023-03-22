@extends('admin::layouts.base')

@section('title', 'Button Settings')


@section('content')


    @livewire('admin-live-edit-button-settings', ['moduleId' => $moduleId])


@endsection
