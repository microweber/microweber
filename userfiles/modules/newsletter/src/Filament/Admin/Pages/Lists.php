<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use MicroweberPackages\CustomField\Fields\Dropdown;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriberList;

class Lists extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $slug = 'newsletter/lists';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.sender-accounts';

    public static function table(Table $table): Table
    {

        $editForm = [
            TextInput::make('name')
                ->label('Name')
                ->placeholder('Enter name'),
        ];

        return $table
            ->heading('List')
            ->query(NewsletterList::query())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('subscribers'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add List')
                    ->form($editForm),
            ])
            ->actions([
                EditAction::make()->form($editForm),
                DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

}
