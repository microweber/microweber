<?php

namespace Modules\Comments\Filament\Pages;

use Filament\Forms\Form;
use Modules\Comments\Filament\CommentsModuleSettings;
use Modules\Settings\Filament\Pages\Settings;

class CommentsModuleSettingsAdmin extends Settings
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static string $moduleId = 'comments';
    protected static ?string $navigationGroup = 'Modules';
    protected static ?string $navigationLabel = 'Comments Settings';
    protected static ?string $title = 'Comments Settings';

    public function form(Form $form): Form
    {
        $settings = new CommentsModuleSettings();
        return $settings->form($form);
    }
}
