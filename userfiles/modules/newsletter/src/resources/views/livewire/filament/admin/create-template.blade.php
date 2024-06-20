@extends('microweber-module-newsletter::livewire.filament.admin.layout')

@section('newsletter-content')

    <div class="text-center my-6">
        <h1 class="text-2xl font-bold">
            Email templates gallery
        </h1>
        <p class="text-gray-600">
            Choose a template to start with
        </p>
    </div>
    <div class="grid grid-cols-3 gap-4">
        @foreach($emailTemplates as $template)

            <div class="bg-white rounded shadow w-[20rem]">
                <div style="width: 100%; height: 800px; overflow: hidden; box-sizing: border-box; transform-origin: left top 0px; transform: scale(0.37);">
                    <iframe data-src="{{ $template['demoUrl'] }}" scrolling="no" style="height: 100%; width: 100%; display: block; border: 0px transparent;">

                    </iframe>
                </div>
                <a href="" class="text-blue-600 hover:text-blue-800">
                    <div class="p-4">
                        {{ $template['name'] }}
                    </div>
                </a>
            </div>

        @endforeach

    </div>

@endsection
