<div>

    <div class="mw-settings-section">
        <div class="mw-settings-section-sidebar">
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

        <div class="mw-settings-section-content">
            {{ $getChildComponentContainer() }}
        </div>
    </div>

</div>
