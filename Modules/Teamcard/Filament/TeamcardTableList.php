<?php

namespace Modules\Teamcard\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\{
    Hidden,
    Textarea,
    TextInput,
    Toggle
};
use Filament\Forms\{
    Concerns\InteractsWithForms,
    Contracts\HasForms
};
use Filament\Actions\{
    Action,
    BulkActionGroup,
    CreateAction,
    DeleteAction,
    DeleteBulkAction,
    EditAction
};
use Filament\Tables\Columns\{
    ImageColumn,
    TextColumn
};
use Filament\Tables\{
    Concerns\InteractsWithTable,
    Contracts\HasTable,
    Table
};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Tables\LiveEditModuleTable;
use MicroweberPackages\Multilanguage\Forms\Actions\TranslateFieldAction;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Ai\Facades\AiImages;
use Modules\Teamcard\Models\Teamcard;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\StructuredOutput\SchemaProperty;

/**
 * Team Card Table List Component
 *
 * Manages the display and manipulation of team member cards in the admin panel
 */
class TeamcardTableList extends LiveEditModuleTable implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public string|null $rel_id = null;
    public string|null $rel_type = null;

    /**
     * Define the form fields for creating/editing team cards
     */
    public function editFormArray(): array
    {
        return [
            Hidden::make('multilanguage')
                ->visible(MultilanguageHelpers::multilanguageIsEnabled()),
            TextInput::make('name')
                ->label('Team Member Name')
                ->reactive()
                ->helperText('Enter the full name of the team member.')
                ->hintAction(
                    TranslateFieldAction::make('name')->label('')
                )
            ,
            MwFileUpload::make('file')
                ->label('Team Member Picture')
                ->reactive()
                ->helperText('Upload a picture of the team member.')
            ,
            Textarea::make('bio')
                ->label('Team Member Bio')
                ->helperText('Provide a short biography of the team member.')
                ->reactive()
                ->hintAction(
                    TranslateFieldAction::make('bio')->label('')
                )
            ,
            TextInput::make('role')
                ->label('Team Member Role')
                ->helperText('Specify the role of the team member in the team.')
                ->reactive()
                ->hintAction(
                    TranslateFieldAction::make('role')->label('')
                )
            ,
            TextInput::make('website')
                ->label('Team Member Website')
                ->reactive()
                ->helperText('Enter the personal or professional website of the team member.')
                ->url(),
            Hidden::make('rel_id')
                ->default($this->rel_id),
            Hidden::make('rel_type')
                ->default($this->rel_type),
        ];
    }

    /**
     * Configure the data table
     */
    public function table(Table $table): Table
    {
        $query = $this->getTeamCardQuery();
        //$this->initializeDefaultTeamCards($query);

        return $table
            ->query($query)
            ->defaultSort('position', 'asc')
            ->columns([
                ImageColumn::make('file')
                    ->label('Picture')
                    ->action(EditAction::make('edit'))
                    ->circular(),
                TextColumn::make('name')
                    ->label('Name')
                    ->action(EditAction::make('edit'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Role')
                    ->searchable()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make('createTeamcardWithAi')
                    ->visible(app()->has('ai'))
                    ->createAnother(false)
                    ->label('Create with AI')
                    ->form([
                        Textarea::make('createTeamcardWithAiSubject')
                            ->label('Subject')
                            ->placeholder('E.g., Create team members for a tech startup')
                            ->required(),

                        TextInput::make('createTeamcardWithAiContentNumber')
                            ->numeric()
                            ->default(1)
                            ->label('Number of team members')
                            ->required(),

                        Toggle::make('createTeamcardWithAiContentImages')
                            ->visible(app()->has('ai.images'))
                            ->label('Also create images')
                            ->default(false)
                            ->onColor('success')
                            ->inline()
                        ,
                    ])
                    ->action(function (array $data) {

                        $prompt = "Create team member profiles with the following details: " . $data['createTeamcardWithAiSubject'];

                        $numberOfMembers = $data['createTeamcardWithAiContentNumber'] ?? 1;
                        $createImages = $data['createTeamcardWithAiContentImages'] ?? false;

                        $class = new class {
                            #[SchemaProperty(description: 'The name of the team member.', required: true)]
                            public string $name;

                            #[SchemaProperty(description: 'The bio of the team member.', required: true)]
                            public string $bio;

                            #[SchemaProperty(description: 'The role of the team member.', required: true)]
                            public string $role;

                            #[SchemaProperty(description: 'The website URL of the team member.', required: false)]
                            public string $website;
                        };

                        /*
                         *  @var \Modules\Ai\Agents\BaseAgent $agent ;
                         */
                        $agent = app('ai.agents')->agent('base');

                        for ($i = 0; $i < $numberOfMembers; $i++) {

                            $resp = $agent->structured(
                                new UserMessage($prompt)
                                , $class::class,
                                maxRetries: 3

                            );
                            $resp = json_decode(json_encode($resp), true);

                            if ($resp) {
                                $teamcard = new Teamcard();
                                $teamcard->name = $resp['name'] ?? 'John Doe';
                                $teamcard->bio = $resp['bio'] ?? 'This is a team member bio.';
                                $teamcard->role = $resp['role'] ?? 'Team Member';
                                $teamcard->website = $resp['website'] ?? '';
                                $teamcard->rel_id = $this->rel_id;
                                $teamcard->rel_type = $this->rel_type;
                                $teamcard->save();

                                if ($createImages) {
                                    $messagesForImages = [];
                                    $messagesForImages[] = ['role' => 'user', 'content' => 'Create a professional headshot image for a team member: ' . $resp['name'] . ', ' . $resp['role']];
                                    $response = AiImages::generateImage($messagesForImages);
                                    if ($response and isset($response['url']) and $response['url']) {
                                        $teamcard->file = $response['url'];
                                        $teamcard->save();
                                    }
                                }
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
                    ->action(function (Teamcard $record) {
                        $newTeamcard = $record->replicate();
                        $newTeamcard->push();

                        $this->resetTable();
                    }),

                DeleteAction::make('delete')
                    ->requiresConfirmation(),
            ])
            ->reorderable('position')
            ->bulkActions([
                DeleteBulkAction::make()
                    ->requiresConfirmation(),
            ])
            ->emptyStateHeading('No team members yet')
            ->emptyStateDescription('Start by adding your first team member.');
    }

    /**
     * Get the base query for team cards
     */
    protected function getTeamCardQuery()
    {
        return Teamcard::query()
            ->where('rel_id', $this->rel_id)
            ->where('rel_type', $this->rel_type);
    }

    /**
     * Initialize default team cards if none exist
     */
    protected function initializeDefaultTeamCards($query): void
    {
        if ($query->count() > 0) {
            return;
        }

        $defaultContent = $this->getDefaultContent();
        if (!isset($defaultContent['teamcard'])) {
            return;
        }

        foreach ($defaultContent['teamcard'] as $member) {
            $this->createDefaultTeamCard($member);
        }
    }

    /**
     * Get default content from JSON file
     */
    protected function getDefaultContent(): array
    {
        $content = file_get_contents(module_path('teamcard') . '/default_content.json');
        return json_decode($content, true) ?? [];
    }

    /**
     * Create a default team card
     */
    protected function createDefaultTeamCard(array $data): void
    {
        $teamCard = new Teamcard();
        $teamCard->fill($data);
        $teamCard->rel_id = $this->rel_id;
        $teamCard->rel_type = $this->rel_type;
        $teamCard->save();
    }

    /**
     * Render the component
     */
    public function render(): View
    {
        return view('modules.teamcard::teamcard-table-list');
    }
}
