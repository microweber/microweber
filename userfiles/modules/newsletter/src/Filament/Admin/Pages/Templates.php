<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;

use Dotswan\FilamentGrapesjs\Fields\GrapesJs;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;

class Templates extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $slug = 'newsletter/templates';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.templates';

    public static function table(Table $table): Table
    {

        $editForm = [

            TextInput::make('title')
                ->label('Title')
                ->required(),

            GrapesJs::make('text')
                ->id('text'),


        ];

        return $table
            ->heading('Templates')
            ->query(NewsletterTemplate::query())
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('created_at')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('Add Template')
                    ->url(fn() => route('filament.admin.pages.newsletter.create-template')),

            ])
            ->actions([
                Action::make('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn(NewsletterTemplate $template) => route('filament.admin.pages.newsletter.template-editor').'?id='.$template->id),
                DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

}
