<?php

namespace MicroweberPackages\Menu\Http\Livewire\Admin;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
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

    public function editAction(): Action
    {
        return Action::make('edit')
            ->mountUsing(function (Form $form, array $arguments) {
                $record = Menu::find($arguments['id']);
                $form->fill($record->toArray());

            })
            ->form([
                TextInput::make('id')
                    ->required()
                    ->hidden(),

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
            ->action(function (array $data, array $arguments) {

                dd($data);
                dd($arguments);
//                $record = Menu::find($arguments['id']);
//
//                return EditAction::make()
//                    ->record($record)
//
            })->slideOver();
    }


    public function render(): View
    {
        return view('menu::livewire.admin.menus-list', [
            'menus' => Menu::all(),
        ]);
    }
}
