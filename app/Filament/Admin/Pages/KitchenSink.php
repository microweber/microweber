<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;

class KitchenSink extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.kitchen-sink';

    protected static ?string $navigationGroup = 'Other';
    protected static ?int $navigationSort = 99;

    public int $star = 0;
    public int $resetStars = 0;

    public $image = '';

    public function form(Form $form): Form
    {


        return $form
            ->schema([


                Actions::make([


                    Actions\Action::make('star')
                        ->icon('heroicon-m-star')
                        ->requiresConfirmation()
                        ->action(function () {
                            Notification::make()
                                ->title('Saved successfully')
                                ->success()
                                ->send();
                        }),
                    Actions\Action::make('resetStars')
                        ->icon('heroicon-m-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function () {
                            Notification::make()
                                ->title('Saved successfully')
                                ->success()
                                ->send();
                        }),
                ]),


                Tabs::make('Test')
                    ->tabs([
                        Tabs\Tab::make('Image')
                            ->schema([

                                MwFileUpload::make('image')
                                    ->label('Image'),

                                TextInput::make('backgroundColor')
                                    ->type('color')
                                    ->label('Background Color'),
                                TextInput::make('text')
                                    ->inputMode('decimal')
                                    ->label('Text'),

                                ColorPicker::make('hsl_color')
                                    ->hsl(),

                                ColorPicker::make('rgb_color')
                                    ->rgb(),

                                ColorPicker::make('rgba_color')
                                    ->rgba(),


                            ]),

                    ]),

            ]);
    }
}
