<?php

namespace Modules\KitchenSink\Filament\Pages;

/**
 * Sample page — every UI component on ONE flat page (no tabs).
 *
 * Extends KitchenSink so it reuses the exact same form() schema (shared
 * RichFormSchemas + all Filament inputs), state properties and getIcons()
 * with zero duplication; only the view differs — a flat layout that stacks
 * the shared partials (components gallery, icon gallery, interactive widgets)
 * plus the Filament form and media picker. A real page demonstrating the
 * shared schema parts in use.
 */
class SamplePage extends KitchenSink
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';

    protected string $view = 'modules.kitchen_sink::sample-page';

    protected static ?int $navigationSort = 100;

    public static string $description = 'Every UI component on one page — built from the shared RichFormSchemas and the kitchen-sink partials.';

    public static function getNavigationLabel(): string
    {
        return 'Sample Page';
    }

    public function getTitle(): string
    {
        return 'Sample Page — all components';
    }
}
