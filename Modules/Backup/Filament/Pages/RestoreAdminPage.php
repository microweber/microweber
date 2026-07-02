<?php

namespace Modules\Backup\Filament\Pages;

// task-2026-05-22-f83bf6 / AI-764 — /admin/restore page.
//
// Shows the same backup-file list as BackupResource but scoped to
// RESTORE actions only (no Create / Upload header CTAs). The page
// deliberately re-uses the Backup model so admins see the same files
// from either entry-point; the mental model is:
//   /admin/backup  → "I want to create or manage backups"
//   /admin/restore → "I want to restore from an existing backup"
//
// Phase 2/3 (progress bar, scheduled restore) are deferred.

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Modules\Backup\Filament\Resources\BackupResource\Pages\ListBackups;
use Modules\Backup\Models\Backup;
use Modules\Backup\SessionStepper;
use Modules\Restore\Restore;

class RestoreAdminPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'modules.backup::filament.pages.restore-admin';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationLabel = 'Restore';

    // task-2026-05-23-cc3b22 / AI-1053 — description for settings hub card.
    public static string $description = 'Restore your website from a previous backup file.';

    protected static string | \UnitEnum | null $navigationGroup = 'System Settings';

    protected static ?int $navigationSort = 51;

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'restore';
    }

    public function getTitle(): string
    {
        return 'Restore';
    }

    public static function getNavigationLabel(): string
    {
        return 'Restore';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Backup::query())
            ->heading('Select a backup file to restore from')
            ->description('Choose a backup and click Restore to begin the recovery process.')
            ->columns([
                Tables\Columns\TextColumn::make('filename')
                    ->label('Filename')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->sortable(),

                Tables\Columns\TextColumn::make('time')
                    ->label('Time')
                    ->sortable(),

                Tables\Columns\TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn (string $state): string => function_exists('format_bytes') ? format_bytes($state) : number_format((int) $state / 1024, 1) . ' KB')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                \Filament\Actions\Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Restore from this backup?')
                    ->modalDescription('This will restore your website content from the selected backup file. Make sure you have a recent backup before proceeding.')
                    ->modalSubmitActionLabel('Yes, restore')
                    ->action(function ($record): void {
                        $sessionId = SessionStepper::generateSessionId(20, [
                            'restoreFile' => $record->filename,
                            'restoreType' => 'overwriteById',
                        ]);

                        $restoreFile = backup_location() . $record->filename;

                        $restore = new Restore();
                        $restore->setSessionId($sessionId);
                        $restore->setFile($restoreFile);
                        $restore->setOvewriteById(true);
                        $restore->setWriteOnDatabase(true);
                        $restore->setBatchRestoring(true);
                        $restore->start();

                        Notification::make()
                            ->success()
                            ->title('Restore started')
                            ->body('The restore process has been initiated.')
                            ->send();
                    }),

                \Filament\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn ($record) => route('admin.backup.download', ['filename' => $record->filename]), shouldOpenInNewTab: true)
                    ->visible(fn ($record) => file_exists(backup_location() . $record->filename)),

                \Filament\Actions\Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        $filepath = backup_location() . $record->filename;
                        if (file_exists($filepath)) {
                            unlink($filepath);
                        }
                        $record->delete();

                        Notification::make()
                            ->success()
                            ->title('Backup deleted')
                            ->send();
                    }),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $filepath = backup_location() . $record->filename;
                                if (file_exists($filepath)) {
                                    unlink($filepath);
                                }
                                $record->delete();
                            }
                        }),
                ]),
            ])
            ->emptyStateHeading('No backups available')
            ->emptyStateDescription('Create a backup first, then you can restore from it here.')
            ->emptyStateIcon('heroicon-o-arrow-path');
    }
}
