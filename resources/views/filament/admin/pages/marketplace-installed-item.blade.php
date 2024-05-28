<x-filament-panels::page>

    <div>

<div class="">
            <div class="bg-white shadow-xl ">
                <div class="p-12 bg-white border-b border-gray-200">
                    <div class="text-green-500 text-3xl">
                        Your theme is installed successfully.
                    </div>
                    <div class="text-2xl">
                        {{ $item['name'] }}
                    </div>
                    <div>
                        <img src="{{$item['screenshotUrl']}}" alt="{{ $item['name'] }}" class="mt-6 w-full h-64 object-cover object-center rounded-lg shadow-md">
                    </div>
                    <div class="mt-6 text-gray-500">
                        {{ $item['description'] }}
                    </div>
                    <div class="mt-6">
                        <a href="{{ $item['url'] }}" target="_blank" class="text-blue-500 hover:text-blue-700">Apply Theme</a>
                        <a href="{{ $item['url'] }}" target="_blank" class="text-blue-500 hover:text-blue-700">Unistall</a>
                    </div>
                    <div>
                        Author: <a href="{{ $item['authorUrl'] }}" target="_blank" class="text-blue-500 hover:text-blue-700">{{ $item['author']}}</a>
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
        </div>
    </div>

</x-filament-panels::page>
