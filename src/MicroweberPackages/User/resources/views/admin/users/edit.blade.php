@extends('admin::layouts.app')

@section('content')
    <div class="mx-5">

        @livewire('admin::edit-user.update-profile-information-form')
        <x-microweber-ui::section-border/>

        @livewire('admin::edit-user.update-password-form')

        <x-microweber-ui::section-border/>

        @livewire('admin::edit-user.delete-user-form')

    </div>
@endsection
