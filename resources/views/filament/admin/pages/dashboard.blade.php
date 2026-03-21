<x-filament-panels::page class="fi-dashboard-page">
    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    <div class="relative">
        {{-- Loading overlay --}}
        <div class="mw-dashboard-loading-overlay">
            <div class="mw-dashboard-loading-spinner">
                <svg class="mw-dashboard-loading-spinner-icon" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round"></circle>
                </svg>
                <span class="mw-dashboard-loading-spinner-text">{{ __('Loading dashboard...') }}</span>
            </div>
        </div>

        <x-filament-widgets::widgets
            :columns="$this->getColumns()"
            :data="
                [
                    ...(property_exists($this, 'filters') ? ['filters' => $this->filters] : []),
                    ...$this->getWidgetData(),
                ]
            "
            :widgets="$this->getVisibleWidgets()"
        />
    </div>
</x-filament-panels::page>
