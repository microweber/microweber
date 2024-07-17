<?php

namespace MicroweberPackages\Menu\Http\Livewire\Admin;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;
use MicroweberPackages\Menu\Models\Menu;

class MenusList extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;


    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->action(function (array $arguments) {

                $record = Menu::find($arguments['id']);

                $record?->delete();
            });
    }

    public function addMenuItemAction(): Action
    {
        return CreateAction::make('addMenuItemAction')
            ->mountUsing(function (Form $form, array $arguments) {

                $form->fill($arguments);
            })
            ->label('Add new menu item')
            ->form([

                TextInput::make('parent_id'),
                TextInput::make('title')
                    // ->required()
                    ->maxLength(255),
                TextInput::make('item_type')
                    // ->required()
                    ->maxLength(255),
            ])
            ->action(function (array $data) {

                $record = Menu::newModelInstance();
                $record->fill($data);
                $record->save();

            })->slideOver();
    }

    public function createAction(): Action
    {
        return CreateAction::make('create')
            ->label('Add new menu')
            ->form([
                Hidden::make('id'),
                TextInput::make('title')
                    // ->required()
                    ->maxLength(255),
                TextInput::make('item_type')
                    // ->required()
                    ->maxLength(255),
            ])
            ->action(function (array $data) {

                $record = Menu::newModelInstance();
                $record->fill($data);
                $record->save();

            })->slideOver();
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->mountUsing(function (Form $form, array $arguments) {
                $record = Menu::find($arguments['id']);
                $form->fill($record->toArray());

            })
            ->form([
                Hidden::make('id')
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('item_type')
                    ->required()
                    ->maxLength(255),
            ])->record(function (array $arguments) {
                $record = Menu::find($arguments['id']);
                return $record;
            })
            ->action(function (array $data) {
                $record = Menu::find($data['id']);
                $record->update($data);
            })->slideOver();
    }


    public function render(): View
    {
        return view('menu::livewire.admin.menus-list', [
            'menus' => Menu::where('item_type', 'menu')->get()
        ]);
    }
}
