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

    public function register(Panel $panel): void
    {
//         $panel->resources([
//            \MicroweberPackages\User\Http\Resources\UserResource::class,
//        ]);

    }

    public function boot(Panel $panel): void
    {
        $panel->resources([
      //      \MicroweberPackages\User\Filament\Resources\UsersResource::class,
        ]);
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

//
//    public static function make(): static
//    {
//        return app(static::class);
//    }
//
//    public function getId(): string
//    {
//        return 'users';
//    }
//
//    public function panel(Panel $panel): Panel
//    {
//
//        return $panel;
//    }
//
//    public function register(Panel $panel): void
//    {
//
//        $panel->resources([
//            UserResource::class,
//        ]);
//
////        $panel
////       //  ->id('admin-users')
////            ->resources([
////              //  UserResource::class,
////            ])
////            ->pages([
////             //   Profile::class,
////            ])->widgets([
////                UsersStatsWidget::class
////            ]);
//    }
//
//    public function boot(Panel $panel): void
//    {
//    }
//
//    public static function configure(): void
//    {
//    }
//
//    public static function configureColors(): void
//    {
//    }
//
//    public static function configureColorShades(): void
//    {
//    }
//
//    public static function configureComponents(): void
//    {
//    }
//
//    public static function configureIcons(): void
//    {
//    }
//
//    public static function getColors(): array
//    {
//        return [];
//    }
//
//    public static function getComponents(): array
//    {
//        return [];
//    }
//
//    public static function getIcons(): array
//    {
//        return [];
//    }
}
