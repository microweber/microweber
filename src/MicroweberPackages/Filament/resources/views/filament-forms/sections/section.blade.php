<div>


    <div class="flex gap-8 bg-white shadow rounded p-12">
        <div class="w-[20rem]">
            <h3 class="font-bold text-xl">
                {{$getHeading()}}
            </h3>
            <div class="text-sm text-black/60 mt-4">
                {{$getDescription()}}
            </div>
        </div>

        <div class="bg-blue-500/5 p-12 rounded w-full">
            {{ $getChildComponentContainer() }}
        </div>
    </div>

</div>
