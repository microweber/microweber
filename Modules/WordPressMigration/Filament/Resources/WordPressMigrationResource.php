<?php

namespace Modules\WordPressMigration\Filament\Resources;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages;
use Modules\WordPressMigration\Models\WordPressMigrationJob;

/**
 * Phase 9 admin surface — the "one place to see every WordPress
 * import that has ever run on this install."
 *
 * The resource is Eloquent-backed on {@see WordPressMigrationJob};
 * every row in `wp_migration_jobs` is one entry in the list. The
 * Index page gives the operator a historical scroll of probes +
 * imports (status, mode, progress, timing), Create launches the
 * existing URL-probe / WXR-upload form on the import page, View
 * drills into a single job with its probe payload and progress
 * JSON, and the Logs page renders a per-item success/fail table
 * sourced from the content_data + staging snapshots.
 *
 * Why we reuse the existing import page for Create:
 *   The import flow is stateful (probe → probe summary → start)
 *   and lives on {@see \Modules\WordPressMigration\Filament\Pages\WordPressMigrationImportPage}.
 *   The Create action on this resource redirects there rather than
 *   duplicating the form — one URL input, one WXR upload, one set
 *   of validation rules. The resource's job is discoverability and
 *   history; starting new migrations stays on the purpose-built
 *   page.
 */
class WordPressMigrationResource extends Resource
{
    protected static ?string $model = WordPressMigrationJob::class;

    protected static ?string $recordTitleAttribute = 'source_host';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Tools';

    protected static ?string $navigationLabel = 'WordPress imports';

    protected static ?string $modelLabel = 'WordPress import';

    protected static ?string $pluralModelLabel = 'WordPress imports';

    protected static ?string $slug = 'word-press-migration-resource';

    protected static ?int $navigationSort = 100;

    public static function getGloballySearchableAttributes(): array
    {
        return ['source_host', 'source_url', 'mode', 'status'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Source')
                    ->schema([
                        TextInput::make('source_url')
                            ->label('WordPress site URL')
                            ->required()
                            ->url()
                            ->maxLength(500)
                            ->helperText('Required for URL-based probes (REST/RSS/sitemap). Leave blank for WXR uploads.'),
                        Select::make('mode')
                            ->label('Preferred mode')
                            ->options([
                                'rest' => 'REST (wp-json)',
                                'rss' => 'RSS feed',
                                'sitemap' => 'Sitemap crawl',
                                'wxr' => 'WXR upload',
                            ])
                            ->placeholder('Auto-detect on probe')
                            ->nullable(),
                        FileUpload::make('wxr_upload')
                            ->label('WXR export (.xml)')
                            ->acceptedFileTypes(['application/xml', 'text/xml', 'text/plain'])
                            ->visible(fn (callable $get) => $get('mode') === 'wxr')
                            ->helperText('Upload a WordPress WXR export file when you cannot reach the source site.'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('source_host')
                    ->label('Source')
                    ->searchable()
                    ->sortable()
                    ->description(fn (WordPressMigrationJob $record): string => (string) $record->source_url)
                    ->limit(40),
                Tables\Columns\TextColumn::make('mode')
                    ->label('Mode')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'rest' => 'success',
                        'rss' => 'info',
                        'sitemap' => 'warning',
                        'wxr' => 'gray',
                        default => 'gray',
                    })
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        WordPressMigrationJob::STATUS_PROBING => 'gray',
                        WordPressMigrationJob::STATUS_READY => 'info',
                        WordPressMigrationJob::STATUS_RUNNING => 'warning',
                        WordPressMigrationJob::STATUS_FINISHED => 'success',
                        WordPressMigrationJob::STATUS_UNREACHABLE,
                        WordPressMigrationJob::STATUS_FAILED => 'danger',
                        WordPressMigrationJob::STATUS_CANCELED => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('progress_imported')
                    ->label('Imported')
                    ->state(fn (WordPressMigrationJob $record) => (int) (($record->progress['imported'] ?? 0)))
                    ->alignRight(),
                Tables\Columns\TextColumn::make('last_probed_at')
                    ->label('Last probed')
                    ->dateTime('M d, Y H:i')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('finished_at')
                    ->label('Finished')
                    ->dateTime('M d, Y H:i')
                    ->since()
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        WordPressMigrationJob::STATUS_PROBING => 'Probing',
                        WordPressMigrationJob::STATUS_READY => 'Ready',
                        WordPressMigrationJob::STATUS_RUNNING => 'Running',
                        WordPressMigrationJob::STATUS_FINISHED => 'Finished',
                        WordPressMigrationJob::STATUS_UNREACHABLE => 'Unreachable',
                        WordPressMigrationJob::STATUS_FAILED => 'Failed',
                        WordPressMigrationJob::STATUS_CANCELED => 'Canceled',
                    ]),
                Tables\Filters\SelectFilter::make('mode')
                    ->options([
                        'rest' => 'REST',
                        'rss' => 'RSS',
                        'sitemap' => 'Sitemap',
                        'wxr' => 'WXR',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (WordPressMigrationJob $record): string => '/admin/word-press-migration-preview-page?job=' . $record->id),
                Tables\Actions\Action::make('logs')
                    ->label('Logs')
                    ->icon('heroicon-o-list-bullet')
                    ->color('gray')
                    ->url(fn (WordPressMigrationJob $record): string => static::getUrl('logs', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWordPressMigrations::route('/'),
            'create' => Pages\CreateWordPressMigration::route('/create'),
            'view' => Pages\ViewWordPressMigration::route('/{record}'),
            'logs' => Pages\WordPressMigrationLogsPage::route('/{record}/logs'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && (bool) (auth()->user()->is_admin ?? false);
    }
}
