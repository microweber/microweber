<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages\UnlockPackage;

use Filament\Forms\Components\View;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class UnlockPackageModuleSettingsPage extends LiveEditModuleSettings
{
    public string $module = 'editor/unlock_package';

    protected static string $view = 'filament-panels::components.layout.simple-form';

    public function form(Form $form): Form
    {


        $request = request()->all();
        $params = $this->params ?? [];
        if ($request) {
            $params = array_merge($params, $request);
        }



        return $form
            ->schema([
                View::make('microweber-live-edit::unlock-package.unlock-package-modal')
                    ->viewData(['params' => $params])
                    ->visible(function () use ($params) {
                        return !empty($params);
                    })
            ]);
    }
}
