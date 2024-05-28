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
            'item' => [
                'name' => $item,
                'description' => 'This is a description of the item',
                'version' => '1.0.0',
                'author' => 'Bozhidar Slaveykov',
                'license' => 'MIT',
                'tags' => ['theme', 'mw-devs'],
                'url' => 'https://example.com',
                'authorUrl' => 'https://example.com',
                'screenshotUrl'=> 'https://packages.microweberapi.com/meta/microweber-templates-photographer/171/screenshot.jpg',
                'installed' => true,
                'enabled' => true,
                'settingsUrl' => '#',
                'uninstallUrl' => '#',
            ],
        ];
    }


}
