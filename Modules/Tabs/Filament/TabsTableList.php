<?php

namespace Modules\Tabs\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
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
use Modules\Tabs\Models\Tab;
use NeuronAI\Chat\Messages\UserMessage;


class TabsTableList extends Component implements HasForms, HasTable, HasActions
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
            ->query(Tab::query()->where('rel_id', $this->rel_id)->where('rel_type', $this->rel_type))
            ->defaultSort('position', 'asc')
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->action( EditAction::make('edit'))
                ,
            ])
            ->filters([
                // ...
            ])
            ->headerActions([
                CreateAction::make('createTabsWithAi')
                    ->visible(app()->has('ai'))
                    ->createAnother(false)
                    ->label('Create with AI')
                    ->form([
                        Textarea::make('createTabsWithAiSubject')
                            ->label('Subject')
                            ->required()
                            ->helperText('Describe the topic for which you need tabs generated'),

                        TextInput::make('createTabsWithAiContentNumber')
                            ->numeric()
                            ->default(5)
                            ->label('Number of tabs')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $prompt = "Generate content items with title and detailed content about: " . $data['createTabsWithAiSubject'];

                        $numberOfItems = $data['createTabsWithAiContentNumber'] ?? 5;

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
                                $tab = new Tab();
                                $tab->title = $resp['title'] ?? 'Tab Title';
                                $tab->content = $resp['content'] ?? 'Tab Content';
                                $tab->rel_id = $this->rel_id;
                                $tab->rel_type = $this->rel_type;
                                $tab->save();
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
                    ->action(function (Tab $record) {
                        $newTab = $record->replicate();
                        $newTab->push();

                        $this->resetTable();
                    }),
            ])
            ->reorderable('position')
            ->bulkActions([
                // BulkActionGroup::make([ DeleteBulkAction::make() ])
            ]);
    }

    public function render()
    {
        return view('modules.tabs::tabs-table-list');
    }
}
