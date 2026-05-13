{{-- AI-269 + AI-272 (task-2026-05-13-e64357) — dashboard empty-state
     widget. Shows above the quick-stats grid ONLY when the `content`
     table has zero rows. The four CTA cards drop into a single column
     on mobile (<576px) and a 2x2 grid on tablet+ via the `.mw-empty-
     state-grid` class which uses `repeat(auto-fit, minmax(240px, 1fr))`
     in admin-empty-state.css. Every CTA is a real <a> with a 44x44
     touch-target floor per WCAG 2.5.5. --}}
<x-filament-widgets::widget>
    <div class="mw-dashboard-empty-state">
        <div class="mw-dashboard-empty-state-header">
            <h2 class="mw-dashboard-empty-state-heading">{{ $this->getHeading() }}</h2>
            <p class="mw-dashboard-empty-state-subheading">{{ $this->getSubheading() }}</p>
        </div>

        <div class="mw-empty-state-grid">
            @foreach ($this->getQuickActions() as $action)
                <a href="{{ $action['url'] }}"
                   class="mw-empty-state-cta mw-empty-state-cta--{{ $action['color'] }}"
                   data-mw-empty-state-cta="{{ $action['key'] }}"
                   aria-label="{{ $action['label'] }}: {{ $action['description'] }}">
                    <span class="mw-empty-state-cta-icon mw-empty-state-cta-icon--{{ $action['color'] }}">
                        <x-filament::icon :icon="$action['icon']" class="mw-empty-state-cta-icon-svg" />
                    </span>
                    <span class="mw-empty-state-cta-body">
                        <span class="mw-empty-state-cta-label">{{ $action['label'] }}</span>
                        <span class="mw-empty-state-cta-description">{{ $action['description'] }}</span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
