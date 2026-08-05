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
use MicroweberPackages\Queue\Filament\Resources\JobResource\Pages\ListJobs;
use MicroweberPackages\Queue\Models\Job;
use MicroweberPackages\Queue\Services\QueueProcessor;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = null;

    protected static ?string $navigationLabel = 'Queue Jobs';

    protected static ?string $modelLabel = 'Queue Job';

    protected static ?string $pluralModelLabel = 'Queue Jobs';

    protected static ?string $slug = 'queue-jobs';

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        $group = config('microweber-queue.filament.navigation_group', 'System Settings');

        return is_string($group) || $group instanceof \UnitEnum ? $group : 'System Settings';
    }

    public static function getNavigationSort(): ?int
    {
        return (int) config('microweber-queue.filament.navigation_sort', 80);
    }

    public static function canAccess(): bool
    {
        if (function_exists('is_admin') && is_admin()) {
            return true;
        }

        try {
            $user = auth()->user();
            if ($user !== null && isset($user->is_admin) && (int) $user->is_admin === 1) {
                return true;
            }
        } catch (\Throwable) {
            // ignore
        }

        // Standalone: allow when no CMS gate is present and user is authenticated
        try {
            return auth()->check();
        } catch (\Throwable) {
            return true;
        }
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Textarea::make('payload')
                ->label('Payload')
                ->rows(12)
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
                    ->limit(60),
                TextColumn::make('queue')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('attempts')
                    ->sortable(),
                TextColumn::make('reserved')
                    ->label('Reserved')
                    ->formatStateUsing(fn ($state): string => ((int) $state === 1) ? 'Yes' : 'No')
                    ->badge()
                    ->color(fn ($state): string => ((int) $state === 1) ? 'warning' : 'gray'),
                TextColumn::make('available_at')
                    ->label('Available')
                    ->formatStateUsing(function ($state): string {
                        if ($state === null || $state === '') {
                            return '—';
                        }

                        return date('Y-m-d H:i:s', (int) $state);
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->formatStateUsing(function ($state): string {
                        if ($state === null || $state === '') {
                            return '—';
                        }

                        return date('Y-m-d H:i:s', (int) $state);
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('queue')
                    ->options(fn (): array => Job::query()
                        ->select('queue')
                        ->distinct()
                        ->orderBy('queue')
                        ->pluck('queue', 'queue')
                        ->all()),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Action::make('dispatch_now')
                    ->label('Run now')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Job $record): void {
                        $record->reserved = null;
                        $record->save();

                        try {
                            $ok = app(QueueProcessor::class)->runJob($record);
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Failed to run job')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();

                            return;
                        }

                        $notification = Notification::make()
                            ->title($ok ? 'Job executed' : 'Job failed');
                        if ($ok) {
                            $notification->success()->send();
                        } else {
                            $notification->danger()->send();
                        }
                    }),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('delete_all_selected')
                        ->label('Delete selected')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                ]),
            ])
            ->headerActions([
                Action::make('process_pending')
                    ->label('Process pending')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->action(function (): void {
                        $count = app(QueueProcessor::class)->process();
                        Notification::make()
                            ->title('Processed ' . $count . ' job(s)')
                            ->success()
                            ->send();
                    }),
                Action::make('clear_queue')
                    ->label('Clear all jobs')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (): void {
                        $result = Job::query()->delete();
                        $deleted = is_int($result) ? $result : 0;
                        Notification::make()
                            ->title('Deleted ' . $deleted . ' job(s)')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
