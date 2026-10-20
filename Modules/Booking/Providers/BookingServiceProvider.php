<?php

namespace Modules\Booking\Providers;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Booking\Filament\Resources\BookingResource;
use Modules\Booking\Services\AvailabilityService;
use Modules\Booking\Services\BookingService;

class BookingServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Booking';

    protected string $moduleNameLower = 'booking';

    public function register(): void
    {
        parent::register();

        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        $this->app->singleton('booking_availability', fn () => new AvailabilityService());
        $this->app->singleton('booking_service', fn () => new BookingService(new AvailabilityService()));

        // Confirm bookings + consume availability when an order is paid.
        $this->app->register(BookingEventServiceProvider::class);

        // Admin "Bookings" management screen.
        FilamentRegistry::registerResource(BookingResource::class);

        FilamentRegistry::registerGlobalSearchEntry(
            'Bookings', '/admin/bookings',
            ['booking', 'reservation', 'calendar', 'room', 'availability', 'check-in'],
            'Shop', ['Section' => 'Shop'],
        );
    }

    public function boot(): void
    {
    }
}
