<div class="flex justify-between">
    <x-filament::modal width="lg">
        <x-slot name="trigger">
            <x-filament::button
                icon="heroicon-m-plus"
                class="admin-toolbar-buttons admin-toolbar-add"
            >
                Add
            </x-filament::button>
        </x-slot>

        <x-slot name="heading">
            Add new
        </x-slot>

        <x-slot name="description">
            <div class="mb-6 p-4">
               @foreach($links as $link)

                    <a href="{{ $link['url'] }}">
                        <div class="flex gap-6 p-4 group hover:scale-105 transition duration-150 hover:bg-blue-500/10 dark:hover:bg-blue-500/15 rounded-md w-full">
                            <div class="flex items-center justify-center w-20 h-20 bg-blue-500/5 dark:bg-blue-500/10 transition duration-150 group-hover:bg-white dark:group-hover:bg-gray-700 shadow-md rounded p-4">
                                @svg($link['icon'], "h-10 w-10 text-black/80 dark:text-gray-200")
                            </div>
                            <div class="flex flex-col gap-2 w-full">
                               <div class="font-bold dark:text-gray-100">
                                   {{ $link['title'] }}
                                 </div>
                                <div class="text-sm dark:text-gray-400">
                                    {{ $link['description'] }}
                                </div>
                            </div>
                        </div>
                    </a>

               @endforeach
            </div>
        </x-slot>

    </x-filament::modal>

</div>
