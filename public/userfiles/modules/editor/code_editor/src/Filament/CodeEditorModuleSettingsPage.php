<?php

namespace MicroweberPackages\Modules\Editor\CodeEditor\Filament;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CodeEditorModuleSettingsPage extends LiveEditModuleSettings
{
    public string $module = 'editor/code_editor';

    protected static string $view = 'filament-panels::components.layout.simple-form';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Code editor')
                    ->extraAttributes(['class' => 'mw-live-edit-settings-tabs-code-editor'])
                    ->tabs([
                        Tabs\Tab::make('HTML Editor')
                            ->extraAttributes(['class' => 'mw-live-edit-settings-tab-html-editor'])
                            ->schema([
                                View::make('microweber-module-code-editor::admin.render-code-editor')
                            ]),
                        Tabs\Tab::make('CSS Editor')
                            ->extraAttributes(['class' => 'mw-live-edit-settings-tab-css-editor'])
                            ->schema([
                                View::make('microweber-module-code-editor::admin.render-css-editor')
                            ]),


                    ]),


            ]);
    }
}
