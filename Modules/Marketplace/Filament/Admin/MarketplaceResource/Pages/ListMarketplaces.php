<?php

namespace Modules\Marketplace\Filament\Admin\MarketplaceResource\Pages;

use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Modules\Marketplace\Filament\Admin\MarketplaceResource;

class ListMarketplaces extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = MarketplaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Reload Packages')
                ->link()
                ->color('secondary')
                ->icon('mw-reload'),
            Actions\Action::make('Licenses')
                ->modal('licenses')
                ->modalSubmitAction(false)
              //  ->modalCloseButton(false)
                ->modalCancelAction(false)
                ->modalContent(view('modules.marketplace::filament.admin.show-list-licenses'))
                ->link()
                ->color('secondary')
                ->icon('mw-licenses'),
        ];
    }

    public function getTabs(): array
    {
        return [
         //   null => Tab::make('All'),
            'templates' => Tab::make()->query(fn ($query) => $query->where('type', 'microweber-template')),
            'modules' => Tab::make()->query(fn ($query) => $query->where('type', 'microweber-module'))
        ];
    }
}
