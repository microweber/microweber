<?php

namespace Modules\MediaLibrary\Filament\Admin\Pages;

use Filament\Pages\Page;

class MediaLibrary extends Page
{

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'modules.media_library::filament.admin.pages.media-library-page';

    public static function canAccess(): bool
    {
        return is_admin();
    }

    public static function registerNavigationItems(): void
    {

    }
}
