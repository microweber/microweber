<?php

namespace Modules\Teamcard\Filament;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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
use Modules\Teamcard\Models\Teamcard;
use MicroweberPackages\LiveEdit\Filament\Admin\Tables\LiveEditModuleTable;
use Modules\Testimonials\Models\Testimonial;

class TeamcardTableList extends LiveEditModuleTable implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public string|null $rel_id = null;
    public string|null $rel_type = null;
    public string|null $module_id = null;

    public function editFormArray()
    {
        return [
            Hidden::make('rel_id')
                ->default($this->rel_id),
            Hidden::make('rel_type')
                ->default($this->rel_type),
            TextInput::make('name')
                ->label('Team Member Name')
                ->helperText('Enter the full name of the team member.'),
            MwFileUpload::make('file')
                ->label('Team Member Picture')
                ->helperText('Upload a picture of the team member.'),
            Textarea::make('bio')
                ->label('Team Member Bio')
                ->helperText('Provide a short biography of the team member.'),
            TextInput::make('role')
                ->label('Team Member Role')
                ->helperText('Specify the role of the team member in the team.'),
            TextInput::make('website')
                ->label('Team Member Website')
                ->helperText('Enter the personal or professional website of the team member.'),
        ];
    }

    public function table(Table $table): Table
    {

        $query = Teamcard::query()->where('rel_id', $this->rel_id)->where('rel_type', $this->rel_type);

        // Check if there are testimonials for this module and if not, add default ones
        $teamcardCount = $query->count();
        if ($teamcardCount == 0) {
            $defaultContent = file_get_contents(module_path('teamcard') . '/default_content.json');
            $defaultContent = json_decode($defaultContent, true);
            if (isset($defaultContent['teamcard'])) {
                foreach ($defaultContent['teamcard'] as $testimonial) {
                    $newTestimonial = new Teamcard();
                    $newTestimonial->fill($testimonial);
                    $newTestimonial->rel_id = $this->rel_id;
                    $newTestimonial->rel_type = $this->rel_type;
                    $newTestimonial->save();
                }
            }
        }

        return $table
            ->query($query)
            ->defaultSort('position', 'asc')
            ->columns([
                ImageColumn::make('file')
                    ->label('Picture')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Name'),
            ])
            ->filters([
                // ...
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
            ])
            ->reorderable('position')
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('modules.teamcard::teamcard-table-list');
    }

}
