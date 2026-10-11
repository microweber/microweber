<?php

namespace MicroweberPackages\User\Filament\Pages;

use Filament\Pages\Page;
use MicroweberPackages\User\Filament\Resources\UsersResource;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * task-2026-06-06-AI839 — /admin/profile used to fall through to the admin
 * 404 page. The admin user-menu already exposes "My Account" (which points at
 * UsersResource edit for the signed-in user), but the bare /admin/profile URL
 * — a URL admins reasonably type/bookmark — returned nothing.
 *
 * This page claims the `profile` slug inside the admin panel (so it respects
 * the configurable admin prefix) and immediately redirects to the signed-in
 * user's own edit page. It does NOT register a navigation entry — "My Account"
 * already covers that affordance; this is purely a URL-resolution shim.
 */
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
