<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class MarketplaceInstalledItem extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.marketplace-installed-item';

    protected static ?string $slug = 'marketplace/installed-item';

    protected ?string $heading = 'Installed Item';

    public function getViewData(): array
    {
        $item = request()->get('item');
        $this->heading = $item;



        return [

        ];
    }


}
