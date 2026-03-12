<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages\UnlockPackage;

use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class UnlockPackageModuleSettingsPage extends LiveEditModuleSettings
{
    public string $module = 'editor/unlock_package';

    protected string $view = 'filament-panels::components.layout.simple-form';

    public function form(Schema $schema): Schema
    {


        $request = request()->all();
        $params = $this->params ?? [];
        if ($request) {
            $params = array_merge($params, $request);
        }



        return $schema
            ->schema([
                View::make('microweber-live-edit::unlock-package.unlock-package-modal')
                    ->viewData(['params' => $params])
                    ->visible(function () use ($params) {
                        return !empty($params);
                    })
            ]);
    }
}
