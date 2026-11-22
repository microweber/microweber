<?php

declare(strict_types=1);

namespace Modules\Currency\Microweber;

use Illuminate\View\View;
use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;

/**
 * Microweber module wrapper for the currency switcher.
 *
 * Registers `<module type="currency">` so the Livewire currency switcher
 * can be dropped onto storefront pages (header, top bar) and edited in Live Edit,
 * like every other functional module (search, cart, menu, logo).
 */
class CurrencyModule extends BaseModule
{
    public static string $name = 'Currency Switcher';

    public static string $module = 'currency';

    public static string $categories = 'shop';

    public static int $position = 110;

    public static string $templatesNamespace = 'modules.currency::templates';

    public function render(): View
    {
        $viewData = $this->getViewData();

        $template = $viewData['template'] ?? 'default';
        if (! view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
