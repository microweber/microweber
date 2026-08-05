<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Filament\Resources;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use MicroweberPackages\Queue\Filament\Resources\FailedJobResource\Pages\ListFailedJobs;
use MicroweberPackages\Queue\Models\FailedJob;

class FailedJobResource extends Resource
{
    protected static ?string $model = FailedJob::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = null;

    protected static ?string $navigationLabel = 'Failed Jobs';

    protected static ?string $modelLabel = 'Failed Job';

    protected static ?string $pluralModelLabel = 'Failed Jobs';

    protected static ?string $slug = 'failed-jobs';

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        $group = config('microweber-queue.filament.navigation_group', 'System Settings');

        return is_string($group) || $group instanceof \UnitEnum ? $group : 'System Settings';
    }

    public static function getNavigationSort(): ?int
    {
        return (int) config('microweber-queue.filament.navigation_sort', 80) + 1;
    }

    public static function canAccess(): bool
    {
        return JobResource::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Textarea::make('exception')
                ->label('Exception')
                ->rows(14)
                ->disabled()
                ->columnSpanFull(),
            Textarea::make('payload')
                ->label('Payload')
                ->rows(8)
                ->disabled()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('display_name')
                    ->label('Job')
                    ->searchable(query: function ($query, string $search): void {
                        $query->where('payload', 'like', '%' . $search . '%');
                    })
                    ->wrap()
                    ->limit(50),
                TextColumn::make('queue')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('connection')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('exception_summary')
                    ->label('Exception')
                    ->wrap()
                    ->limit(80),
                TextColumn::make('failed_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('queue')
                    ->options(fn (): array => FailedJob::query()
                        ->select('queue')
                        ->distinct()
                        ->orderBy('queue')
                        ->pluck('queue', 'queue')
                        ->all()),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Action::make('retry')
                    ->label('Retry')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (FailedJob $record): void {
                        try {
                            Artisan::call('queue:retry', ['id' => [$record->uuid ?: (string) $record->id]]);
                            Notification::make()
                                ->title('Job re-queued')
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Retry failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('retry_selected')
                        ->label('Retry selected')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $ids = [];
                            foreach ($records as $record) {
                                if ($record instanceof FailedJob) {
                                    $ids[] = $record->uuid !== '' && $record->uuid !== null
                                        ? $record->uuid
                                        : (string) $record->id;
                                }
                            }
                            try {
                                Artisan::call('queue:retry', ['id' => $ids]);
                                Notification::make()
                                    ->title('Retried ' . count($ids) . ' job(s)')
                                    ->success()
                                    ->send();
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->title('Retry failed')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('retry_all')
                    ->label('Retry all')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (): void {
                        try {
                            Artisan::call('queue:retry', ['id' => ['all']]);
                            Notification::make()
                                ->title('All failed jobs re-queued')
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Retry all failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('flush')
                    ->label('Flush failed jobs')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (): void {
                        $result = FailedJob::query()->delete();
                        $deleted = is_int($result) ? $result : 0;
                        Notification::make()
                            ->title('Flushed ' . $deleted . ' failed job(s)')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFailedJobs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
