<?php

namespace Modules\Media\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Media\Models\Media;
use Modules\Media\Models\MediaFolder;
use Modules\Media\Filament\Resources\MediaResource\Pages;
use MicroweberPackages\CdnSync\Services\CdnSyncService;

use MicroweberPackages\FilamentRegistry\GlobalSearch\MicroweberGloballySearchable;
class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-photo';

    protected static string | \UnitEnum | null $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'title';
    use MicroweberGloballySearchable;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'filename', 'description'];
    }

    public static function getNavigationLabel(): string
    {
        return 'Media Library';
    }

    public static function getModelLabel(): string
    {
        return 'Media';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Media Items';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            \Filament\Schemas\Components\Section::make('Media Information')
                ->schema([
                    \Filament\Schemas\Components\Grid::make(['default' => 1, 'md' => 2])
                        ->schema([
                            Forms\Components\Hidden::make('id'),

                            Forms\Components\TextInput::make('title')
                                ->label('Title')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Select::make('folder_id')
                                ->label('Folder')
                                ->options(fn () => MediaFolder::pluck('name', 'id')->toArray())
                                ->searchable()
                                ->placeholder('Select a folder'),

                            Forms\Components\Textarea::make('description')
                                ->label('Description')
                                ->rows(3)
                                ->columnSpan(['default' => 1, 'md' => 2]),
                        ]),
                ]),

            \Filament\Schemas\Components\Section::make('Media File')
                ->schema([
                    Forms\Components\FileUpload::make('filename')
                        ->label('Upload File')
                        ->disk('public')
                        ->directory('uploaded')
                        ->preserveFilenames()
                        ->image()
                        ->maxSize(10240)
                        ->helperText('Maximum file size: 10MB'),
                ]),

            \Filament\Schemas\Components\Section::make('CDN Status')
                ->visible(fn (Media $record) => app('cdn_sync')->isConfigured() && $record->is_synced_to_cdn)
                ->schema([
                    Forms\Components\TextInput::make('cdn_url')
                        ->label('CDN URL')
                        ->disabled(),

                    Forms\Components\Select::make('cdn_provider')
                        ->label('CDN Provider')
                        ->options([
                            's3' => 'Amazon S3',
                            'cloudfront' => 'CloudFront',
                            'rackspace' => 'Rackspace',
                        ])
                        ->disabled(),

                    Forms\Components\Toggle::make('is_synced_to_cdn')
                        ->label('Synced to CDN')
                        ->disabled(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('filename')
                    ->label('Preview')
                    ->square()
                    ->size(60),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('folder.name')
                    ->label('Folder')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('media_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'picture' => 'success',
                        'video' => 'warning',
                        'audio' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('file_size')
                    ->label('Size')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state / 1024, 2) . ' KB' : '-')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_synced_to_cdn')
                    ->label('CDN')
                    ->boolean()
                    ->trueIcon('heroicon-o-cloud')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->visible(fn () => app('cdn_sync')->isConfigured()),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('folder_id')
                    ->label('Folder')
                    ->options(fn () => MediaFolder::pluck('name', 'id')->toArray()),

                SelectFilter::make('media_type')
                    ->label('Type')
                    ->options([
                        'picture' => 'Images',
                        'video' => 'Videos',
                        'audio' => 'Audio',
                        'file' => 'Files',
                    ]),

                Filter::make('cdn_synced')
                    ->label('CDN Synced')
                    ->visible(fn () => app('cdn_sync')->isConfigured())
                    ->query(fn (Builder $query) => $query->where('is_synced_to_cdn', true)),

                Filter::make('not_cdn_synced')
                    ->label('Not CDN Synced')
                    ->visible(fn () => app('cdn_sync')->isConfigured())
                    ->query(fn (Builder $query) => $query->where('is_synced_to_cdn', false)),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
                \Filament\Actions\Action::make('syncToCdn')
                    ->label('Sync to CDN')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->color('success')
                    ->visible(fn (Media $record) => app('cdn_sync')->isConfigured() && !$record->isFullySyncedToCdn())
                    ->action(function (Media $record) {
                        /** @var CdnSyncService $service */
                        $service = app('cdn_sync');
                        $service->sync($record);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Sync to CDN')
                    ->modalDescription('Are you sure you want to sync this media to CDN?')
                    ->modalSubmitActionLabel('Yes, sync now'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    \Filament\Actions\BulkAction::make('bulkSyncToCdn')
                        ->label('Sync to CDN')
                        ->icon('heroicon-o-cloud-arrow-up')
                        ->color('success')
                        ->action(function ($records) {
                            /** @var CdnSyncService $service */
                            $service = app('cdn_sync');
                            $service->bulkSync($records);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Bulk Sync to CDN')
                        ->modalDescription('Are you sure you want to sync selected media to CDN?')
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::class,
            'create' => Pages\CreateMedia::class,
            'edit' => Pages\EditMedia::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('folder');
    }

    public static function canAccess(): bool
    {
        return is_admin();
    }
}
