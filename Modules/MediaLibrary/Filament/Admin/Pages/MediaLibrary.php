<?php

namespace Modules\MediaLibrary\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Pages\SimplePage;
use Filament\Pages\Concerns;

class MediaLibrary extends SimplePage
{

    use Concerns\CanAuthorizeAccess;
    use Concerns\HasRoutes;

    protected static bool $shouldRegisterNavigation = false;

    protected bool $hasTopbar = false;

    protected static string $view = 'modules.media_library::filament.admin.pages.media-library-page';

    public static function canAccess(): bool
    {
        return is_admin();
    }

    public static function registerNavigationItems(): void
    {

    }
}
