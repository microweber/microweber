<x-filament-panels::page>

    <div>

        <div class="">
            <div class="text-green-500 text-3xl">
                @if($item['type'] == 'microweber-template')
                Your theme is installed successfully.
                @else
                Your module is installed successfully.
                @endif
            </div>
            <div class="text-2xl">
                {{ $item['name'] }}
            </div>
            <div class="flex gap-4">
            <div>
                <img src="{{$item['screenshot_link']}}" alt="{{ $item['name'] }}"
                     class="mt-6 w-full h-104 object-fi† object-top rounded-lg shadow-md">
            </div>
                <div class="w-[100rem]">
            <div class="mt-6 text-gray-500">
                {{ $item['description'] }}
            </div>
            <div class="mt-6">

                @if($item['type'] == 'microweber-template')
                    <x-filament::button
                        icon="heroicon-m-cog"
                        href="{{ $item['name'] }}"
                        tag="a"
                    >
                        Apply Theme
                    </x-filament::button>

                    <x-filament::button
                        icon="heroicon-m-x-mark"
                        color="danger"
                        href="{{ $item['name'] }}"
                        tag="a"
                    >
                        Unistall Theme
                    </x-filament::button>

                @else
                    <x-filament::button
                        icon="heroicon-m-cog"
                        href="{{ $item['name'] }}"
                        tag="a"
                    >
                        Open Module
                    </x-filament::button>
                @endif


            </div>
            <div>
                Author: <a href="email:{{ $item['author_email'] }}" target="_blank"
                           class="text-blue-500 hover:text-blue-700">{{ $item['author_name']}}</a>
            </div>
            <div>
                Version: {{ $item['version'] }}
            </div>
            <div>
                License: {{ $item['license'] }}
            </div>
            <div>
                Tags: {{ $item['tags'] }}
            </div>
            <div>
                Tags: {{ $item['tags'] }}
            </div>
        </div>
        </div>

    </div>
    </div>

</x-filament-panels::page>
