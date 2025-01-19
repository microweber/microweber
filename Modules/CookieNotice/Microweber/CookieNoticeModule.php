<?php

namespace Modules\CookieNotice\Microweber;

use Illuminate\Support\Facades\Cookie;
use MicroweberPackages\Microweber\Abstract\BaseModule;
use MicroweberPackages\Module\AbstractModule;
use Modules\ContactForm\Filament\ContactFormModuleSettings;
use Modules\ContactForm\Models\Form;
use Modules\CookieNotice\Filament\Pages\CookieNoticeModuleSettingsAdmin;
use Modules\CookieNotice\Services\CookieNoticeManager;


class CookieNoticeModule extends BaseModule
{
    public static string $name = 'CookieNoticeModule';
    public static string $module = 'cookie_notice';
    public static string $icon = 'heroicon-o-user-group';
    public static string $categories = 'mics';
    public static int $position = 570;
    public static string $settingsComponent = CookieNoticeModuleSettingsAdmin::class;
    public static string $templatesNamespace = 'modules.cookie_notice::templates';
    protected static bool $shouldRegisterNavigation = false;

    public function render()
    {
        $viewData = $this->getViewData();

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        $cookiePolicyUrl = get_option('cookie_policy_url', 'cookie_notice');
        $cookieNoticeTitle = get_option('cookie_notice_title', 'cookie_notice');
        $cookieNoticeText = get_option('cookie_notice_text', 'cookie_notice');
        $backgroundColor = get_option('background_color', 'cookie_notice');
        $textColor = get_option('text_color', 'cookie_notice');

        $viewData['settings'] = [
            'cookiePolicyURL' => $cookiePolicyUrl ?? site_url('privacy-policy'),
            'cookieNoticeTitle' => $cookieNoticeTitle ?? 'Cookie Notice',
            'cookieNoticeText' => $cookieNoticeText ?? 'This website uses cookies to ensure you get the best experience on our website.',
            'backgroundColor' => $backgroundColor ?? '#ffffff',
            'textColor' => $textColor ?? '#000000'
        ];

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

}
