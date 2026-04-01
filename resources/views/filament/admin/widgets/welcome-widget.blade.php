<x-filament-widgets::widget>
    <div class="mw-welcome-widget">
        <div class="mw-welcome-widget-left">
            <h1 class="mw-welcome-widget-heading">{{ $this->getGreeting() }}</h1>
            <p class="mw-welcome-widget-subtitle">{{ $this->getSubtitle() }}</p>
        </div>
        <div class="mw-welcome-widget-right">
            <p class="mw-welcome-widget-period">{{ $this->getPeriodLabel() }}</p>
        </div>
    </div>
</x-filament-widgets::widget>
