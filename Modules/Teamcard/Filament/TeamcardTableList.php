<?php

namespace Modules\Teamcard\Filament;

use Filament\Forms\Components\{
    Hidden,
    Textarea,
    TextInput
};
use Filament\Forms\{
    Concerns\InteractsWithForms,
    Contracts\HasForms
};
use Filament\Tables\Actions\{
    CreateAction,
    DeleteAction,
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
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Tables\LiveEditModuleTable;
use Modules\Teamcard\Models\Teamcard;

/**
 * Team Card Table List Component
 *
 * Manages the display and manipulation of team member cards in the admin panel
 */
class TeamcardTableList extends LiveEditModuleTable implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public ?string $rel_id = null;
    public ?string $rel_type = null;
    public ?string $module_id = null;

    /**
     * Define the form fields for creating/editing team cards
     */
    protected function editFormArray(): array
    {
        return [
            Hidden::make('rel_id')
                ->default($this->rel_id),
            Hidden::make('rel_type')
                ->default($this->rel_type),
            TextInput::make('name')
                ->label('Team Member Name')
                ->helperText('Enter the full name of the team member.')
                ->required(),
            MwFileUpload::make('file')
                ->label('Team Member Picture')
                ->helperText('Upload a picture of the team member.')
                ->required(),
            Textarea::make('bio')
                ->label('Team Member Bio')
                ->helperText('Provide a short biography of the team member.')
                ->required(),
            TextInput::make('role')
                ->label('Team Member Role')
                ->helperText('Specify the role of the team member in the team.')
                ->required(),
            TextInput::make('website')
                ->label('Team Member Website')
                ->helperText('Enter the personal or professional website of the team member.')
                ->url(),
        ];
    }

    /**
     * Configure the data table
     */
    public function table(Table $table): Table
    {
        $query = $this->getTeamCardQuery();
        $this->initializeDefaultTeamCards($query);

        return $table
            ->query($query)
            ->defaultSort('position', 'asc')
            ->columns([
                ImageColumn::make('file')
                    ->label('Picture')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->form($this->editFormArray())
            ])
            ->actions([
                EditAction::make()
                    ->slideOver()
                    ->form($this->editFormArray()),
                DeleteAction::make()
                    ->requiresConfirmation()
            ])
            ->reorderable('position')
            ->bulkActions([])
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
