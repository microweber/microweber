@extends('admin::layouts.app')

@section('content')
    <div class="mx-5">

        @livewire('admin::profile.update-profile-information-form')
        <x-microweber-ui::section-border/>

        @livewire('admin::profile.update-password-form')

        <x-microweber-ui::section-border/>

        @livewire('admin::profile.two-factor-authentication-form')
        <x-microweber-ui::section-border/>


        @livewire('admin::profile.logout-other-browser-sessions-form')
        <x-microweber-ui::section-border/>

        @livewire('admin::profile.delete-user-form')

    </div>

@endsection
