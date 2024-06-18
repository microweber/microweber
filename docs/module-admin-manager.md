
```php

ModuleAdmin::registerSettings('shop', 'microweber-module-shop::settings');

ModuleAdmin::registerLiveEditSettingsUrl('shop', site_url('admin-live-edit/shop-settings'));

ModuleAdmin::registerLiveEditPanelPage(\MicroweberPackages\Modules\Shop\Http\Livewire\ShopModuleSettings::class);
ModuleAdmin::registerPanelPage(\MicroweberPackages\Modules\Shop\Http\Livewire\ShopSettings::class);


```
