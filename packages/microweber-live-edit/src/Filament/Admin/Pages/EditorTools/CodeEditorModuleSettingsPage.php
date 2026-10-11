<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages\EditorTools;

use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CodeEditorModuleSettingsPage extends LiveEditModuleSettings
{
    public string $module = 'editor/code_editor';

    protected string $view = 'filament-panels::components.layout.simple-form';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Code editor')
                    ->extraAttributes(['class' => 'mw-live-edit-settings-tabs-code-editor'])
                    ->schema([
                        Tabs\Tab::make('HTML Editor')
                            ->extraAttributes(['class' => 'mw-live-edit-settings-tab-html-editor'])
                            ->schema([
                                View::make('microweber-live-edit::editor-tools.render-code-editor')
                            ]),
                        Tabs\Tab::make('CSS Editor')
                            ->extraAttributes(['class' => 'mw-live-edit-settings-tab-css-editor'])
                            ->schema([
                                View::make('microweber-live-edit::editor-tools.render-css-editor')
                            ]),


                    ]),


            ]);
    }
}
