@extends('microweber-module-newsletter::livewire.filament.admin.layout')

@section('newsletter-content')

    <div class="text-center my-12">
        <h1 class="text-2xl font-bold">
            Email templates gallery
        </h1>
        <p class="text-gray-600">
            Choose a template to start with
        </p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const iframes = document.querySelectorAll('iframe[data-src]');
            iframes.forEach(iframe => {
                iframe.src = iframe.dataset.src;
            });
        });
    </script>
    <div class="grid grid-cols-3 gap-4">
        @foreach($emailTemplates as $template)

            <div class="justify-content-center items-center bg-white rounded shadow h-[20rem]">
                <div style="width: 800px; height: 800px; overflow: hidden; box-sizing: border-box; transform-origin: left top 0px; transform: scale(0.37);">
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
