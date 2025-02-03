<?php

namespace Modules\Backup\Filament\Pages;

use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Pages\Page;

class CreateBackup extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'modules.backup::filament.pages.create-backup';


    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Order')
                        ->schema([
                            // ...
                        ]),
                    Wizard\Step::make('Delivery')
                        ->schema([
                            // ...
                        ]),
                    Wizard\Step::make('Billing')
                        ->schema([
                            // ...
                        ]),
                ])
            ]);

    }

}
