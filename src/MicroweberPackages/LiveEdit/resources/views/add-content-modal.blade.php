<div class="mb-6 p-4">
    @foreach($links as $link)

        <a href="{{ $link['url'] }}">
            <div class="flex gap-6 p-4 group hover:scale-105 transition duration-150 hover:bg-blue-500/10 rounded-md w-full">
                <div class="flex items-center justify-center w-20 h-20 bg-blue-500/5 transition duration-150 group-hover:bg-white shadow-md rounded p-4">
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
