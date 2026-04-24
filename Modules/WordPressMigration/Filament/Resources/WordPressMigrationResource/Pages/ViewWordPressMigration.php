<?php

namespace Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource;
use Modules\WordPressMigration\Models\WordPressMigrationJob;

class ViewWordPressMigration extends ViewRecord
{
    protected static string $resource = WordPressMigrationResource::class;

    protected function getHeaderActions(): array
    {
        /** @var WordPressMigrationJob $record */
        $record = $this->record;

        return [
            Actions\Action::make('preview')
                ->label('Preview staging')
                ->icon('heroicon-o-eye')
                ->color('primary')
                ->url('/admin/word-press-migration-preview-page?job=' . $record->id),
            Actions\Action::make('logs')
                ->label('Logs')
                ->icon('heroicon-o-list-bullet')
                ->color('gray')
                ->url(WordPressMigrationResource::getUrl('logs', ['record' => $record])),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Job')
                    ->schema([
                        TextEntry::make('id')->label('ID'),
                        TextEntry::make('status')
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
                        TextEntry::make('mode')->placeholder('—'),
                        TextEntry::make('source_host'),
                        TextEntry::make('source_url')->columnSpanFull(),
                        TextEntry::make('last_probed_at')
                            ->dateTime('M d, Y H:i')
                            ->placeholder('—'),
                        TextEntry::make('started_at')
                            ->dateTime('M d, Y H:i')
                            ->placeholder('—'),
                        TextEntry::make('finished_at')
                            ->dateTime('M d, Y H:i')
                            ->placeholder('—'),
                        TextEntry::make('last_error')
                            ->columnSpanFull()
                            ->placeholder('—'),
                    ])
                    ->columns(2),
                Section::make('Probe result')
                    ->schema([
                        KeyValueEntry::make('probe_result')
                            ->state(fn (WordPressMigrationJob $record): array => $record->probe_result
                                ? $record->probe_result->getArrayCopy()
                                : [])
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
                Section::make('Progress')
                    ->schema([
                        KeyValueEntry::make('progress')
                            ->state(fn (WordPressMigrationJob $record): array => $record->progress
                                ? $record->progress->getArrayCopy()
                                : [])
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
            ]);
    }
}
