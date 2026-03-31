<?php

namespace Modules\Newsletter\Filament\Admin\Resources;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Modules\Newsletter\Filament\Admin\Resources\WorkflowResource\Pages\CreateWorkflow;
use Modules\Newsletter\Filament\Admin\Resources\WorkflowResource\Pages\EditWorkflow;
use Modules\Newsletter\Filament\Admin\Resources\WorkflowResource\Pages\ManageWorkflows;
use Modules\Newsletter\Models\Workflow;
use Modules\Newsletter\Models\WorkflowExecution;
use Modules\Newsletter\Services\WorkflowEngine;

class WorkflowResource extends Resource
{
    protected static ?string $model = Workflow::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Automation Workflows';
    protected static ?string $label = 'Workflow';
    protected static ?string $pluralLabel = 'Automation Workflows';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->rows(2),

                Grid::make(2)
                    ->schema([
                        Select::make('trigger_type')
                            ->options(Workflow::getTriggerTypes())
                            ->required()
                            ->live(),

                        Select::make('trigger_event')
                            ->options(Workflow::getTriggerEvents())
                            ->visible(fn (callable $get) => $get('trigger_type') === Workflow::TRIGGER_TYPE_EVENT)
                            ->required(fn (callable $get) => $get('trigger_type') === Workflow::TRIGGER_TYPE_EVENT),
                    ]),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('trigger_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'event' => 'Event',
                        'schedule' => 'Schedule',
                        'manual' => 'Manual',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'event' => 'info',
                        'schedule' => 'warning',
                        'manual' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('trigger_event')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('execution_count')
                    ->label('Executions')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('success_count')
                    ->label('Success')
                    ->numeric()
                    ->sortable()
                    ->color('success'),

                TextColumn::make('failure_count')
                    ->label('Failed')
                    ->numeric()
                    ->sortable()
                    ->color('danger'),

                IconColumn::make('is_active')
                    ->boolean(),

                TextColumn::make('last_triggered_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('build')
                    ->label('Build')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (Workflow $record): string => route('filament.admin-newsletter.pages.edit-workflow', ['record' => $record->id])),

                Action::make('execute')
                    ->label('Run Now')
                    ->icon('heroicon-o-play')
                    ->requiresConfirmation()
                    ->modalHeading('Execute Workflow')
                    ->modalDescription('This will manually trigger the workflow. Continue?')
                    ->action(function (Workflow $record) {
                        $engine = app(WorkflowEngine::class);
                        $execution = $engine->start($record, [], WorkflowExecution::SOURCE_MANUAL);
                        $engine->execute($execution);

                        return new HtmlString('<span class="text-success">Workflow executed successfully!</span>');
                    })
                    ->visible(fn (Workflow $record): bool => $record->is_active),

                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageWorkflows::route('/'),
            'create' => CreateWorkflow::route('/create'),
            'edit' => EditWorkflow::route('/{record}/edit'),
        ];
    }
}
