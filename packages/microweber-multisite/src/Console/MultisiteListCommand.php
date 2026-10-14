<?php

declare(strict_types=1);

namespace MicroweberPackages\Multisite\Console;

use Illuminate\Console\Command;
use MicroweberPackages\Multisite\MultisiteManager;

class MultisiteListCommand extends Command
{
    protected $signature = 'multisite:list';

    protected $description = 'List configured multisite domains and their isolated paths';

    public function handle(MultisiteManager $manager): int
    {
        $sites = $manager->sites();

        if (empty($sites)) {
            $this->warn('No multisite domains configured.');
            $this->line('Add one with: <info>php artisan multisite:install your-domain.com</info>');

            return self::SUCCESS;
        }

        $rows = [];
        foreach ($sites as $site) {
            $rows[] = [
                $site,
                $manager->userfilesFolder($site),
                $manager->cachePrefix($site),
                $manager->sessionCookie($site),
            ];
        }

        $this->table(['Domain', 'Userfiles folder', 'Cache prefix', 'Session cookie'], $rows);
        $this->line(count($sites) . ' site(s). Current request site: <info>' . ($manager->current() ?? 'default') . '</info>');

        return self::SUCCESS;
    }
}
