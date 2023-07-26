@extends('admin::layouts.app')

@section('topbar2-links-left')

    <div class="mw-toolbar-back-button-wrapper">
        <div class="main-toolbar mw-modules-toolbar-back-button-holder mb-3 " id="mw-modules-toolbar" style="">
            <a href="{{admin_url('users')}}">
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 96 960 960" width="28"><path d="M480 896 160 576l320-320 47 46.666-240.001 240.001H800v66.666H286.999L527 849.334 480 896Z"></path></svg>
            </a>
        </div>
    </div>

@endsection

@section('content')
    <div class="mx-sm-5 mx-1">

        @livewire('admin::edit-user.update-profile-information-form', ['userId' => $user->id])
        <x-microweber-ui::section-border/>

        @livewire('admin::edit-user.update-status-and-role-form', ['userId' => $user->id])
        <x-microweber-ui::section-border/>
        @livewire('admin::edit-user.delete-user-form', ['userId' => $user->id])
        <x-microweber-ui::section-border/>
        @livewire('admin::edit-user.login-as-user-form', ['userId' => $user->id])

    </div>
@endsection
