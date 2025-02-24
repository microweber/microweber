<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminTrustProxiesPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-settings';
    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';
    protected static ?string $title = 'Trust Proxies';
    protected static string $description = 'Configure trusted proxy settings';
    protected static ?string $navigationGroup = 'Website Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Trust Proxies Settings')
                    ->description('Configure trusted proxies for your website')
                    ->schema([
                        Toggle::make('options.website.trust_proxies_enabled')
                            ->label('Enable Trusted Proxies')
                            ->helperText(new HtmlString('Enable this if your website is behind a proxy or load balancer.'))
                            ->live(),

                        Textarea::make('options.website.trust_proxies')
                            ->label('Trusted Proxies')
                            ->helperText(new HtmlString('Enter IP addresses of trusted proxies, one per line.<br>You can use CIDR notation (e.g., 192.168.1.0/24) or specific IPs (e.g., 192.168.1.1).'))
                            ->placeholder("192.168.1.0/24\n10.0.0.0/8")
                            ->rows(5)
                            ->visible(fn ($get) => $get('options.website.trust_proxies_enabled'))
                            ->live(),

                        Toggle::make('options.website.trust_all_proxies')
                            ->label('Trust All Proxies')
                            ->helperText(new HtmlString('WARNING: Only enable this if you are sure about your infrastructure security.<br>This will trust all proxies (*).'))
                            ->visible(fn ($get) => $get('options.website.trust_proxies_enabled'))
                            ->live(),

                        Toggle::make('options.website.trust_cloudflare')
                            ->label('Trust Cloudflare')
                            ->helperText(new HtmlString('Enable this if you are using Cloudflare as your proxy.<br>This will automatically trust Cloudflare\'s IP ranges.'))
                            ->visible(fn ($get) => $get('options.website.trust_proxies_enabled'))
                            ->live(),
                    ])
            ]);
    }
}
