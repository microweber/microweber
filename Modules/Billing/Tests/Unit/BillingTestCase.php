<?php

namespace Modules\Billing\Tests\Unit;

use Tests\TestCase;
use Modules\Billing\Providers\BillingServiceProvider;
use Modules\Billing\Providers\BillingFilamentAdminPanelProvider;
use Filament\FilamentServiceProvider;

class BillingTestCase extends TestCase
{
   //use DatabaseTransactions;

    protected function tearDown(): void
    {
        // Clean up stale output buffers left by Filament/Livewire rendering
        // when non-admin users hit admin routes.
        while (ob_get_level() > 1) {
            ob_end_clean();
        }

        parent::tearDown();
    }
}
