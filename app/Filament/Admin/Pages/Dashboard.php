<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Facades\FilamentIcon;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Route;
use Filament\Pages\Dashboard\Actions\FilterAction;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;


class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersAction;


    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 1;

    protected static string | \UnitEnum | null $navigationGroup = 'Dashboard';

    protected string $view = 'filament.admin.pages.dashboard';

    // Hide the page heading — the WelcomeWidget provides the greeting instead
    public function getHeading(): string|Htmlable
    {
        return '';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }


    public function getWidgets(): array
    {
        $coreWidgets = [
            \App\Filament\Admin\Widgets\WelcomeWidget::class,
            \App\Filament\Admin\Widgets\DashboardQuickStatsWidget::class,
        ];

        $registeredWidgets = FilamentRegistry::getWidgets(self::class, Filament::getCurrentPanel()->getId());

        return array_merge($coreWidgets, $registeredWidgets);
    }
}
