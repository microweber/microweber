<div>
<div class="fi-ta-badges-column flex gap-2">
    @if (!empty($getBadges()))
        @foreach ($getBadges() as $badge)
            <x-filament::badge size="xs" :color="$badge['color']">{{ $badge['label'] }}</x-filament::badge>
        @endforeach
    @endif
</div>
</div>
