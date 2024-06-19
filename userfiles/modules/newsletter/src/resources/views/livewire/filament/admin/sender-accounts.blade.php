@extends('microweber-module-newsletter::livewire.filament.admin.layout')

@section('newsletter-content')

    <x-filament::section>
        {{ $this->table }}
    </x-filament::section>

@endsection
