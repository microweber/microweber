<?php

namespace Modules\Newsletter\Filament\Admin\Pages;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriber;

class Subscribers extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'newsletter/subscribers';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.sender-accounts';

    public static function table(Table $table): Table
    {

        $editForm = [
            TextInput::make('email')
                ->label('Email')
                ->placeholder('Enter email')
                ->required()
                ->email()
                ->unique('newsletter_subscribers', 'email'),
            TextInput::make('name')
                ->label('Name')
                ->placeholder('Enter name'),
            CheckboxList::make('lists')
                ->label('Subscribed for lists')
                ->options(function () {
                    $lists = [];
                    $lists[0] = 'Default';
                   $getLists = NewsletterList::query()->pluck('name', 'id');
                   if ($getLists) {
                       foreach ($getLists as $key => $value) {
                           $lists[$key] = $value;
                       }
                   }
                    return $lists;
                })
        ];

        return $table
            ->heading('Subscribers')
            ->query(NewsletterSubscriber::query())
            ->columns([
                TextColumn::make('email'),
                TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Subscriber')
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
