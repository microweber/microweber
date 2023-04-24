@extends('admin::layouts.app')

@section('content')
    <div class="mx-5">

        @livewire('admin::edit-user.update-profile-information-form', ['userId' => $user->id])
        <x-microweber-ui::section-border/>

        @livewire('admin::edit-user.update-password-form', ['userId' => $user->id])

        <x-microweber-ui::section-border/>

        @livewire('admin::edit-user.delete-user-form', ['userId' => $user->id])

    </div>
@endsection
