<?php

namespace Modules\Comments\Filament\Pages;

use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;


class CommentsModuleSettingsAdmin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static string $moduleId = 'comments';
    protected static ?string $navigationGroup = 'Modules';
    protected static ?string $navigationLabel = 'Comments Settings';
    protected static ?string $title = 'Comments Settings';


}
