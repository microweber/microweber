<?php

namespace Modules\Faq\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Component;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use MicroweberPackages\Multilanguage\Forms\Actions\TranslateFieldAction;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Faq\Models\Faq;
use NeuronAI\Chat\Messages\UserMessage;

class FaqTableList extends Component implements HasForms, HasTable, HasActions
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

            TextInput::make('question')
                ->label('Question')
                ->hintAction(
                    TranslateFieldAction::make('question')->label('')
                )
                ->required(),
            Textarea::make('answer')
                ->label('Answer')
                ->hintAction(
                    TranslateFieldAction::make('answer')->label('')
                )
                ->required(),
            \Filament\Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true),
            Hidden::make('rel_id')
                ->default($this->rel_id),
            Hidden::make('rel_type')
                ->default($this->rel_type),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Faq::query()->where('rel_id', $this->rel_id)->where('rel_type', $this->rel_type))
            ->defaultSort('position', 'asc')
            ->columns([
                TextColumn::make('question')
                    ->label('Question')
                    ->action( EditAction::make('edit'))
                    ->searchable(),

                \Filament\Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->headerActions([
                CreateAction::make('createFaqWithAi')
                    ->visible(app()->has('ai'))
                    ->createAnother(false)
                    ->label('Create with AI')
                    ->form([
                        Textarea::make('createFaqWithAiSubject')
                            ->label('Subject')
                            ->required()
                            ->helperText('Describe the topic for which you need FAQs generated'),

                        TextInput::make('createFaqWithAiContentNumber')
                            ->numeric()
                            ->default(5)
                            ->label('Number of FAQs')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $prompt = "Generate frequently asked questions with detailed answers about: " . $data['createFaqWithAiSubject'];

                        $numberOfFaqs = $data['createFaqWithAiContentNumber'] ?? 5;

                        $class = new class {
                            public string $question;
                            public string $answer;
                        };

                        /*
                         * @var \Modules\Ai\Agents\BaseAgent $agent
                         */
                        $agent = app('ai.agents')->agent('base');

                        for ($i = 0; $i < $numberOfFaqs; $i++) {
                            $resp = $agent->structured(
                                new UserMessage($prompt),
                                $class::class
                            );
                            $resp = json_decode(json_encode($resp), true);

                            if ($resp) {
                                $faq = new Faq();
                                $faq->question = $resp['question'] ?? 'What is this?';
                                $faq->answer = $resp['answer'] ?? 'This is a sample answer.';
                                $faq->is_active = true;
                                $faq->rel_id = $this->rel_id;
                                $faq->rel_type = $this->rel_type;
                                $faq->save();
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
                DeleteAction::make('delete'),
                Action::make('copy')
                    ->label('Copy')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (Faq $record) {
                        $newFaq = $record->replicate();
                        $newFaq->push();

                        $this->resetTable();
                    }),
            ])
            ->reorderable('position');
    }

    public function render()
    {
        return view('modules.faq::faq-table-list');
    }
}
