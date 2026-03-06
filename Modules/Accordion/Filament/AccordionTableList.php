<?php

namespace Modules\Accordion\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Component;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\Multilanguage\Forms\Actions\TranslateFieldAction;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Accordion\Models\Accordion;
use NeuronAI\Chat\Messages\UserMessage;

class AccordionTableList extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public string|null $rel_id = null;
    public string|null $rel_type = null;

    public function editFormArray()
    {
        return [
            Hidden::make('multilanguage')
                ->visible(MultilanguageHelpers::multilanguageIsEnabled()),

            TextInput::make('title')
                ->label('Title')
                ->hintAction(
                    TranslateFieldAction::make('title')->label('')
                )
                ->required(),
            MwIconPicker::make('icon')
                ->label('Icon'),
            Textarea::make('content')
                ->label('Content')
                ->hintAction(
                    TranslateFieldAction::make('content')->label('')
                )
                ->required(),

            Hidden::make('rel_id')
                ->default($this->rel_id),
            Hidden::make('rel_type')
                ->default($this->rel_type),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Accordion::query()->where('rel_id', $this->rel_id)->where('rel_type', $this->rel_type))
            ->defaultSort('position', 'asc')
            ->reorderable('position')
            ->emptyStateHeading('No accordion items found')
             ->columns([
                TextColumn::make('title')
                    ->action( EditAction::make('edit'))
                    ->label('Title'),
            ])
            ->headerActions([
                CreateAction::make('createAccordionWithAi')
                    ->visible(app()->has('ai'))
                    ->createAnother(false)
                    ->label('Create with AI')
                    ->form([
                        Textarea::make('createAccordionWithAiSubject')
                            ->label('Subject')
                            ->required()
                            ->helperText('Describe the topic for which you need accordion items generated'),

                        TextInput::make('createAccordionWithAiContentNumber')
                            ->numeric()
                            ->default(5)
                            ->label('Number of items')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $prompt = "Generate content items with title and detailed content about: " . $data['createAccordionWithAiSubject'];

                        $numberOfItems = $data['createAccordionWithAiContentNumber'] ?? 5;

                        $class = new class {
                            public string $title;
                            public string $content;
                        };

                        /*
                         * @var \Modules\Ai\Agents\BaseAgent $agent
                         */
                        $agent = app('ai.agents')->agent('base');

                        for ($i = 0; $i < $numberOfItems; $i++) {
                            $resp = $agent->structured(
                                new UserMessage($prompt),
                                $class::class
                            );
                            $resp = json_decode(json_encode($resp), true);

                            if ($resp) {
                                $accordion = new Accordion();
                                $accordion->title = $resp['title'] ?? 'Title';
                                $accordion->content = $resp['content'] ?? 'Content';
                                $accordion->rel_id = $this->rel_id;
                                $accordion->rel_type = $this->rel_type;
                                $accordion->save();
                            }
                        }

                        $this->resetTable();
                    }),

                CreateAction::make('create')
                    ->slideOver()
                    ->form($this->editFormArray())
            ])
            ->actions([
                EditAction::make('edit')
                    ->slideOver()
                    ->form($this->editFormArray()),
                Action::make('copy')
                    ->label('Copy')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (Accordion $record) {
                        $newAccordion = $record->replicate();
                        $newAccordion->push();

                        $this->resetTable();
                    }),
                DeleteAction::make('delete'),

            ])
            ->reorderable('position')
            ->bulkActions([

                    DeleteBulkAction::make()

            ]);
    }

//    public function create()
//    {
//        return CreateAction::make()
//            ->slideOver()
//            ->form($this->editFormArray());
//    }

    public function render()
    {
        return view('modules.accordion::accordion-table-list');
    }

}
