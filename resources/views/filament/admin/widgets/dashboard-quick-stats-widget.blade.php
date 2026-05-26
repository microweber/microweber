<x-filament-widgets::widget>
    @php $hero = $this->getHeroStat(); @endphp

    <div class="mw-hero-stat-card mw-quick-stat-card--{{ $hero['color'] }}" x-data="{ activeTab: 'monthly' }">
        <div class="mw-hero-stat-header">
            <div class="mw-hero-stat-icon mw-quick-stat-card-icon--{{ $hero['color'] }}">
                <x-filament::icon :icon="$hero['icon']" class="mw-quick-stat-icon-svg" />
            </div>
            <span class="mw-hero-stat-label">{{ $hero['label'] }}</span>
            <a href="{{ $hero['url'] }}" class="mw-hero-stat-link">View all &rarr;</a>
        </div>
        <div class="mw-hero-stat-tabs">
            @foreach (['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'yearly' => 'Yearly'] as $key => $label)
                <button
                    type="button"
                    class="mw-hero-stat-tab"
                    :class="activeTab === '{{ $key }}' ? 'mw-hero-stat-tab--active' : ''"
                    @click="activeTab = '{{ $key }}'"
                >{{ $label }}</button>
            @endforeach
        </div>
        <div class="mw-hero-stat-value-area">
            @foreach ($hero['ranges'] as $key => $data)
                <div x-show="activeTab === '{{ $key }}'" x-cloak style="display: none;">
                    <p class="mw-hero-stat-value">{{ $data['total'] }}</p>
                    <p class="mw-hero-stat-orders">{{ $data['count'] }} orders</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mw-quick-stats-grid mw-quick-stats-secondary">
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
