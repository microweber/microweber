<x-filament-widgets::widget>
    <div class="mw-quick-stats-grid">
        @foreach ($this->getStats() as $stat)
            <a href="{{ $stat['url'] }}" class="mw-quick-stat-card mw-quick-stat-card--{{ $stat['color'] }}">
                <div class="mw-quick-stat-card-body">
                    <div class="mw-quick-stat-card-icon mw-quick-stat-card-icon--{{ $stat['color'] }}">
                        <x-filament::icon :icon="$stat['icon']" class="mw-quick-stat-icon-svg" />
                    </div>
                    <div class="mw-quick-stat-card-content">
                        <p class="mw-quick-stat-card-label">{{ $stat['label'] }}</p>
                        <p class="mw-quick-stat-card-value">{{ $stat['value'] }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</x-filament-widgets::widget>
