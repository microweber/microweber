<div>
    <x-filament::modal width="lg">
        <x-slot name="trigger">
            <x-filament::button
                icon="heroicon-m-plus"
            >
                Add
            </x-filament::button>
        </x-slot>

        <x-slot name="heading">
            Add new
        </x-slot>

        <x-slot name="description">
            <div class="mb-6">
               @foreach($links as $link)

                    <a href="{{ $link['url'] }}">
                        <div class="flex gap-8 p-4 group transition duration-150 hover:bg-blue-500/10 rounded-md w-full">
                            <div class="flex items-center justify-center bg-blue-500/5 transition duration-150 group-hover:bg-white shadow-md rounded p-4">
                                @svg($link['icon'], "h-10 w-10 text-black/80")
                            </div>
                            <div class="flex flex-col gap-2 w-full">
                               <div class="font-bold">
                                   {{ $link['title'] }}
                                 </div>
                                <div class="text-sm">
                                    {{ $link['description'] }}
                                </div>
                            </div>
                        </div>
                    </a>

               @endforeach
            </div>
        </x-slot>

        {{-- Modal content --}}
    </x-filament::modal>
</div>
