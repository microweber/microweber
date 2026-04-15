<x-filament-panels::page>
    @livewire(\Modules\SiteStats\Filament\Widgets\StatsOverviewCards::class)

    <div class="mt-6">
        @livewire(\Modules\SiteStats\Filament\Widgets\VisitorsChartWidget::class)
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div>
            @livewire(\Modules\SiteStats\Filament\Widgets\TopPagesWidget::class)
        </div>
        <div>
            @livewire(\Modules\SiteStats\Filament\Widgets\ReferrersWidget::class)
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div>
            @livewire(\Modules\SiteStats\Filament\Widgets\LocationsWidget::class)
        </div>
        <div>
            @livewire(\Modules\SiteStats\Filament\Widgets\BrowsersWidget::class)
        </div>
    </div>
</x-filament-panels::page>
