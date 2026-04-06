<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeWidget extends Widget
{
    protected static ?int $sort = -2;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.admin.widgets.welcome-widget';

    protected static bool $isLazy = false;

    public function getGreeting(): string
    {
        $user = Auth::user();
        $name = $user?->first_name ?? $user?->username ?? $user?->email ?? 'Admin';

        return "Welcome back, {$name}";
    }

    public function getSubtitle(): string
    {
        return "Here's what's happening";
    }

}
