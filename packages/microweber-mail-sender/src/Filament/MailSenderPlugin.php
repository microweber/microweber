<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use MicroweberPackages\MailSender\Filament\Pages\MailSenderSettings;

class MailSenderPlugin implements Plugin
{
    public function getId(): string
    {
        return 'mail-sender';
    }

    public static function make(): static
    {
        /** @var static $instance */
        $instance = new static(); // @phpstan-ignore new.static

        return $instance;
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            MailSenderSettings::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
