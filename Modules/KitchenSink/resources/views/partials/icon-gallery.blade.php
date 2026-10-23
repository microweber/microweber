{{-- Shared SVG-icon gallery. Needs the page's getIcons() — an include shares the
     parent $this. Used by the KitchenSink Icons tab + the flat SamplePage. --}}
<div class="grid grid-cols-4 gap-4 mt-3">
    @foreach($this->getIcons() as $icon)
        {{-- Hover brightens the tile in both modes; never invert to white in dark
             (a previous group-hover:bg-white made tiles glow white under dark). --}}
        <div class="group flex flex-col items-center justify-center rounded-xl p-4 bg-blue-500/10 dark:bg-white/5 hover:bg-blue-500/20 dark:hover:bg-white/10 transition duration-150">
            <div class="flex items-center justify-center">
                @svg('mw-'.$icon, "h-12 w-12 text-gray-900 dark:text-white")
            </div>
            <div class="w-full text-center mt-2 text-xs font-medium text-gray-900 dark:text-white break-words leading-tight">
                mw-{{$icon}}
            </div>
        </div>
    @endforeach
</div>
