<x-filament-panels::page>

    <div>
        <div class="">
            <div class="text-green-500 text-3xl">
                Your theme is installed successfully.
            </div>
            <div class="text-2xl">
                {{ $item['name'] }}
            </div>
            <div>
                <img src="{{$item['screenshotUrl']}}" alt="{{ $item['name'] }}"
                     class="mt-6 w-full h-64 object-cover object-center rounded-lg shadow-md">
            </div>
            <div class="mt-6 text-gray-500">
                {{ $item['description'] }}
            </div>
            <div class="mt-6">
                <x-filament::button
                    icon="heroicon-m-cog"
                    href="{{ $item['url'] }}"
                    tag="a"
                >
                    Apply Theme
                </x-filament::button>

                <x-filament::button
                    icon="heroicon-m-x-mark"
                    color="danger"
                    href="{{ $item['url'] }}"
                    tag="a"
                >
                    Unistall Theme
                </x-filament::button>


            </div>
            <div>
                Author: <a href="{{ $item['authorUrl'] }}" target="_blank"
                           class="text-blue-500 hover:text-blue-700">{{ $item['author']}}</a>
            </div>
            <div>
                Version: {{ $item['version'] }}
            </div>
            <div>
                License: {{ $item['license'] }}
            </div>
            <div>
                Tags: {{ implode(', ', $item['tags']) }}
            </div>
        </div>
    </div>

</x-filament-panels::page>
