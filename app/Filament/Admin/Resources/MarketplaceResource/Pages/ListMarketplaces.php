<?php

namespace App\Filament\Admin\Resources\MarketplaceResource\Pages;

use App\Filament\Admin\Resources\MarketplaceResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

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
                ->modalContent(view('marketplace::livewire.filament.admin.show-list-licenses'))
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
