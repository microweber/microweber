
<x-filament-widgets::widget>
    <div class="mw-welcome-widget">
        <div class="mw-welcome-widget-left">
            <h1 class="mw-welcome-widget-heading">{{ $this->getGreeting() }}</h1>
            @php $counters = $this->getCounters(); @endphp
            @if ($this->isAllCaughtUp())
                <p class="mw-welcome-widget-empty">All caught up.</p>
            @elseif (! empty($counters))
                <p class="mw-welcome-widget-counters" aria-label="New activity summary">
                    @foreach ($counters as $i => $counter)
                        @if ($i > 0)
                            <span class="mw-welcome-widget-counter-sep" aria-hidden="true">·</span>
                        @endif
                        <a href="{{ $counter['url'] }}"
                           class="mw-welcome-widget-counter"
                           aria-label="{{ $counter['count'] }} {{ $counter['count'] === 1 ? $counter['label_singular'] : $counter['label_plural'] }} — view list">
                            <span class="mw-welcome-widget-counter-count">{{ $counter['count'] }}</span>
                            <span class="mw-welcome-widget-counter-label">{{ $counter['count'] === 1 ? $counter['label_singular'] : $counter['label_plural'] }}</span>
                        </a>
                    @endforeach
                </p>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
