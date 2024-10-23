<?php

namespace Modules\Video\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class VideoModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'video';

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Section::make('Video settings')->schema([


                ])
            ]);
    }


}
