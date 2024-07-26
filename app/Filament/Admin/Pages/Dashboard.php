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
use MicroweberPackages\Module\Facades\ModuleAdmin;


class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersAction;


    protected static ?string $navigationIcon = 'mw-dashboard';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Dashboard';

    protected static string $view = 'filament.admin.pages.dashboard';



    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()

                ->form([
                    Select::make('period')
                        ->options([
                            'daily' => 'Daily',

                            'weekly' => 'Weekly',
                            'monthly' => 'Monthly',
                            'yearly' => 'Yearly',
                        ]),
                    DatePicker::make('startDate'),
                    DatePicker::make('endDate'),


                ]),
        ];
    }


    public function getWidgets(): array
    {
        $location = 'filament.admin.pages.dashboard';
        $widgets = ModuleAdmin::getAdminPanelWidgets($location);
        return $widgets;
    }
}
