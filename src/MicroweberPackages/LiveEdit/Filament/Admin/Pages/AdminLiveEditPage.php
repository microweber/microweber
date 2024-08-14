<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages;


use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class AdminLiveEditPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $description = '';
    protected static ?string $slug = 'live-edit';


    protected static string $view = 'microweber-live-edit::iframe-page';
    protected static string $layout = 'filament-panels::components.layout.live-edit';


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

}
