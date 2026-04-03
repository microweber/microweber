<div>


    <div class="flex gap-8 bg-white dark:bg-white/5 shadow rounded p-12">
        <div class="w-[20rem]">
            <h3 class="font-bold text-xl flex items-center gap-2">
                @if($getIcon())
                    @svg($getIcon(), 'w-5 h-5 text-gray-500 dark:text-gray-400')
                @endif
                {{$getHeading()}}
            </h3>
            <div class="text-sm text-black/60 dark:text-white mt-4">
                {{$getDescription()}}
            </div>
        </div>

        <div class="bg-blue-500/5 dark:bg-white/5  p-12 rounded w-full">
            {{ $getChildComponentContainer() }}
        </div>
    </div>

</div>
