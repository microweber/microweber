<?php

namespace Modules\Comments\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwMediaBrowser;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CommentsModuleSettingsLiveEdit extends LiveEditModuleSettings
{
    public string $module = 'comments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }
}
