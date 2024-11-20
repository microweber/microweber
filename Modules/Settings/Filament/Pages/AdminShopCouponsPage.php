<?php

namespace Modules\Settings\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopCouponsPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-coupon';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Coupons';

    protected static string $description = 'Configure your shop coupons settings';

    protected static ?string $navigationGroup = 'Shop Settings';

}
