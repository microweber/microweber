<?php

namespace MicroweberPackages\User\Filament\Pages;

use Filament\Pages\Page;
use MicroweberPackages\User\Filament\Resources\UsersResource;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class AdminProfileRedirectPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'profile';
    protected static ?string $title = 'My Profile';

    protected string $view = 'user::filament.pages.profile-redirect';

    public static function canAccess(): bool
    {
        return function_exists('is_admin') && is_admin();
    }

    public function mount(): void
    {
        $userId = auth()->id();

        if (! $userId) {
            return;
        }

        // Mirror the proven "My Account" user-menu resolution: prefer the
        // Filament route, fall back to the literal admin-prefixed path if the
        // resource route is not registered on this panel.
        try {
            $url = UsersResource::getUrl('edit', ['record' => $userId]);
        } catch (RouteNotFoundException $e) {
            $url = url(mw_admin_prefix_url() . '/users/' . $userId . '/edit');
        }

        $this->redirect($url);
    }
}
