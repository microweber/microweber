<?php

namespace Modules\Payment\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Category\Filament\CategoryModuleSettings;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;

class PaymentModule extends BaseModule
{
    public static string $name = 'Payment';
    public static string $module = 'shop/payments';
    public static string $icon = 'modules.payment::icon';
    public static string $categories = 'online shop';
    public static int $position = 1;
    public static string $settingsComponent = PaymentProviderResource::class;
    public static string $templatesNamespace = 'modules.payment::templates';
    protected static bool $shouldRegisterNavigation = false;


    public function render()
    {
        $viewData = $this->getViewData();
        $viewName = $this->getViewName($viewData['template'] ?? 'default');

        $viewData['providers'] = app()->payment_method_manager->getProviders();

        return view($viewName, $viewData);
    }
}
