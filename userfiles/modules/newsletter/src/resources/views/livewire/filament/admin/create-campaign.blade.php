@extends('microweber-module-newsletter::livewire.filament.admin.layout')

@section('newsletter-content')

    <div class="text-center mt-12 mb-6">
        <h1 class="text-2xl font-bold">
            Creating a new email marketing campaign
        </h1>
        <p class="text-gray-600">
            Continue with  the wizard to create a new email marketing campaign
        </p>
    </div>
    <div class="mb-12">
        {{$this->form}}
    </div>

@endsection
