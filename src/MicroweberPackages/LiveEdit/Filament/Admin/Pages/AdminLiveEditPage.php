<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages;


use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use MicroweberPackages\Media\Models\Media;

class AdminLiveEditPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $description = '';
    protected static ?string $slug = 'live-edit';


    protected static string $view = 'microweber-live-edit::iframe-page';
    protected static string $layout = 'filament-panels::components.layout.live-edit';

    use InteractsWithActions;
    use InteractsWithForms;
    public function render(): View
    {
        $params = request()->all();
         return view($this->getView(), $this->getViewData())
            ->layout($this->getLayout(), [
                'livewire' => $this,
                'params' => $params,
                'maxContentWidth' => $this->getMaxContentWidth(),
                ...$this->getLayoutData(),
            ]);
    }



    public function addPageAction(): Action
    {
        return Action::make('addPageAction')
            ->mountUsing(function (Form $form, array $arguments) {


            })
            ->form([
                Hidden::make('id')
                    ->required(),
                TextInput::make('title')

                    ->maxLength(255),

                TextInput::make('description')

                    ->maxLength(2550),

            ])->record(function (array $arguments) {
                $record = Media::find($arguments['id']);
                return $record;
            })
            ->action(function (array $data) {

            })->slideOver();
    }

}
