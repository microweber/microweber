<?php

namespace MicroweberPackages\User\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

class UsersFilamentPlugin implements Plugin
{
    public function getId(): string
    {
        return 'users';
    }

    public function panel(Panel $panel): Panel
    {
        return $panel;
    }

    public function register(Panel $panel): void
    {
//        dd($panel);
//        $panel
//            ->resources([
//                PostResource::class,
//                CategoryResource::class,
//            ])
//            ->pages([
//                Settings::class,
//            ]);
    }

    public function boot(Panel $panel): void
    {
    }

    public static function configure(): void
    {
    }

    public static function configureColors(): void
    {
    }

    public static function configureColorShades(): void
    {
    }

    public static function configureComponents(): void
    {
    }

    public static function configureIcons(): void
    {
    }

    public static function getColors(): array
    {
        return [];
    }

    public static function getComponents(): array
    {
        return [];
    }

    public static function getIcons(): array
    {
        return [];
    }
}
