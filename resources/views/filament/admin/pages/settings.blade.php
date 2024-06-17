<x-filament-panels::page>

    <div>
        @foreach($settingsGroups as $settingsTitle=>$settings)
            <div class="mb-4">
                <h2 class="text-2xl">{{ $settingsTitle }}</h2>
                <div class="mt-4 bg-white dark:bg-white/5 p-4 rounded-md shadow">
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($settings as $setting)
                            <a href="{{ $setting['url'] }}">
                                <div class="flex gap-4 cursor-pointer transition duration-150 hover:bg-blue-500/5 border border-transparent dark:hover:bg-white/5 dark:hover:border-white/10 hover:border-blue-500/10 rounded-2xl p-8">
                                    <div class="flex items-center justify-center bg-blue-500/10 dark:bg-white/5 transition duration-150 group-hover:bg-white rounded-xl p-4">
                                        @svg($setting['icon'], "h-6 w-6 text-black/90 dark:text-white")
                                    </div>
                                    <div class="w-full flex flex-col justify-center">
                                        <h3 class="font-bold">{{$setting['title']}}</h3>
                                        <div class="text-sm text-gray-500">{{$setting['description']}}</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</x-filament-panels::page>
