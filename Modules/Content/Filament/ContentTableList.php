<?php

namespace Modules\Content\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
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
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Content\Models\Content;

class ContentTableList extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public string|null $moduleId = null;
    public string $contentModel = Content::class;
    public array $params = [];

    public function editFormArray($params = [])
    {
        return ContentResource::formArray($params);

    }

    public function table(Table $table): Table
    {

        //  $query = Content::query()->where('is_active', 1);
        $query = $this->contentModel::query()->where('is_active', 1);

        return $table
            ->query($query)
            ->defaultSort('position', 'asc')
            ->columns([
                TextColumn::make('title')
                    ->limit(20)
                    ->label('Title'),
            ])
            ->filters([
                // ...
            ])
            ->headerActions([
                CreateAction::make('create')
                    ->slideOver()
                    ->form(function () {
                        $params = [];
                        $params['contentModel'] = $this->contentModel;
                        return $this->editFormArray($params);
                    })
            ])
            ->actions([
                EditAction::make('edit')
                    ->slideOver()
                    ->form(function (Model $record) {

                        $params = [];
                        if ($record) {
                            $params['id'] = $record->id;
                            $params['contentModel'] = $this->contentModel;
                        }

                        return $this->editFormArray($params);
                        //  ->form()
                    })
                ,
                DeleteAction::make('delete')
            ])
            ->reorderable('position')
            ->bulkActions([
                // BulkActionGroup::make([ DeleteBulkAction::make() ])
            ]);
    }

    public function render()
    {
        return view('modules.content::content-table-list');
    }
}
