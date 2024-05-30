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
            <div class="mb-4">
               @foreach($links as $link)

                    <a href="{{ $link['url'] }}">
                        <div class="flex gap-8 p-4 hover:bg-blue-950/10 rounded-md w-full">
                            <div class="bg-blue-500/5 shadow-md rounded p-4">
                                @svg($link['icon'], "h-12 w-12 text-black")
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
