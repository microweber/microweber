<?php

namespace Modules\Ai\Filament\Resources\AgentChatResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Modules\Ai\Filament\Resources\AgentChatResource;

class ListAgentChats extends ListRecords
{
    protected static string $resource = AgentChatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Chat')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('search')
                ->form([
                    TextInput::make('search')
                        ->label('Search')
                        ->placeholder('Search by title or description...')
                        ->live()
                        ->debounce(500),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['search'],
                            fn (Builder $query, $search): Builder => $query
                                ->where('title', 'like', "%{$search}%")
                                ->orWhere('description', 'like', "%{$search}%")
                        );
                }),

            Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from')
                        ->label('Created From'),
                    DatePicker::make('created_until')
                        ->label('Created Until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                }),
        ];
    }
}
