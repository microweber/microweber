<?php

namespace Modules\Newsletter\Filament\Admin\Resources\WorkflowResource\Pages;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Newsletter\Filament\Admin\Resources\WorkflowResource;
use Filament\Resources\Pages\EditRecord;
use Modules\Newsletter\Livewire\Admin\WorkflowBuilder;

class EditWorkflow extends EditRecord
{
    protected static string $resource = WorkflowResource::class;

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'lg' => 3,
                ])
                    ->schema([
                        Section::make('Workflow details')
                            ->description('Configure the workflow trigger and activation state.')
                            ->schema([
                                $this->getFormContentComponent(),
                            ])
                            ->columnSpan([
                                'lg' => 1,
                            ]),
                        Section::make('Workflow builder')
                            ->description('Build the automation flow using the server-rendered builder until the visual canvas is fully wired.')
                            ->schema([
                                Livewire::make(WorkflowBuilder::class, [
                                    'workflow' => $this->getRecord(),
                                ])
                                    ->key('workflow-builder-' . $this->getRecord()->getKey()),
                            ])
                            ->columnSpan([
                                'lg' => 2,
                            ]),
                    ]),
            ]);
    }
}
