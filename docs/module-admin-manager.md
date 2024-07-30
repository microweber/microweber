
```php

ModuleAdmin::registerSettings('shop', 'microweber-module-shop::settings');

ModuleAdmin::registerLiveEditSettingsUrl('shop', site_url('admin-live-edit/shop-settings'));

ModuleAdmin::registerLiveEditPanelPage(\MicroweberPackages\Modules\Shop\Http\Livewire\ShopModuleSettings::class);
ModuleAdmin::registerFilamentPage(\MicroweberPackages\Modules\Shop\Http\Livewire\ShopSettings::class);




Livewire::component('microweber-module-sitestats::settings', SiteStatsSettingsComponent::class);
Livewire::component('microweber-module-sitestats::dashboard', SiteStatsDashboard::class);
Livewire::component('microweber-module-sitestats::dashboard-chart', SiteStatsDashboardChart::class);


ModuleAdmin::registerSettings('site_stats', 'microweber-module-sitestats::settings');
ModuleAdmin::registerPanelResource(MyResoure::class)

 
Event::listen(ServingFilament::class, function () {

    ModuleAdmin::registerAdminPanelWidget(SiteStatsDashboardChart::class, 'filament.admin.pages.dashboard');
    ModuleAdmin::registerAdminPanelWidget(SiteStatsDashboard::class, 'filament.admin.pages.dashboard');
});

```


 
