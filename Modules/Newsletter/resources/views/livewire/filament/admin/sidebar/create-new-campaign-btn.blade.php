<div class="flex p-4 w-full mb-4">

    <x-filament::button
        href="{{admin_url('newsletter/create-campaign')}}"
        tag="a"
    >
        <div class="flex gap-2 items-center w-full">
            @svg("mw-add-plus", "h-4 w-4") Create campaign
        </div>

    </x-filament::button>

</div>
