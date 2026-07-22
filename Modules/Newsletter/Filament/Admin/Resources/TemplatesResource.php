<?php

namespace Modules\Newsletter\Filament\Admin\Resources;

use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\View;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages\ManageTemplates;
use Modules\Newsletter\Filament\Components\SelectTemplate;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterTemplate;

class TemplatesResource extends Resource
{
    protected static ?string $model = NewsletterTemplate::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-paint-brush';

//    protected static ?string $slug = 'newsletter/sender-accounts';

//    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'Designs';

    protected static string | \UnitEnum | null $navigationGroup = 'Templates';

    protected static ?int $navigationSort = 1;

    protected static bool $isGloballySearchable = true;

    protected static ?bool $isGlobalSearchForcedCaseInsensitive = true;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('campaigns_count')
                    ->label('Used in')
                    ->counts('campaigns')
                    ->suffix(' campaigns')
                    ->sortable()
                    ->alignCenter()
                    ->color('gray'),
                TextColumn::make('updated_at')
                    ->label('Last Modified')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
->actions([
            \Filament\Actions\Action::make('preview')
                ->icon('heroicon-o-eye')
                ->label('Preview')
                ->modalHeading(fn ($record) => $record->title)
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close')
                ->modalContent(fn ($record) => new \Illuminate\Support\HtmlString(
                    '<div style="width:100%;max-height:500px;overflow-y:auto;border:1px solid #e5e7eb;border-radius:8px;padding:16px;background:#fff;">'
                    . ($record->text ?: '<p style="color:#999;text-align:center;">No HTML preview available</p>')
                    . '</div>'
                )),
            \Filament\Actions\Action::make('Edit')
                ->icon('heroicon-o-pencil')
                ->url(function ($record) {
                    return route('filament.admin-newsletter.pages.template-editor') . '?id=' . $record->id;
                }),
            \Filament\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            \Filament\Actions\BulkActionGroup::make([
                \Filament\Actions\DeleteBulkAction::make(),
            ]),
        ])
        ->emptyStateHeading('No designs yet')
        ->emptyStateDescription('Create an email template to use in your campaigns.')
        ->emptyStateActions([
            \Filament\Actions\CreateAction::make()
                ->label('New Template'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTemplates::route('/'),
        ];
    }
}
