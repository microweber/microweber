{{-- AI-705 / task-2026-05-16-2dbae0 — Welcome sub-line replaces
     the static "Here's what's happening" filler with an actionable
     counter strip ("3 new comments · 1 new order · 0 new messages")
     per designer spec §2 AD4. Empty state collapses to a single
     "All caught up." line. Counter values come from the SAME query
     as the matching Filament navigation badge so the two surfaces
     never disagree (single source of truth). --}}
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
