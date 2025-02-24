<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminRobotsPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-settings';
    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';
    protected static ?string $title = 'Robots.txt';
    protected static string $description = 'Configure robots.txt file settings';
    protected static ?string $navigationGroup = 'Website Settings';

    public function form(Form $form): Form
    {
        $defaultRobotsTxt = "User-agent: *\n" .
            "Disallow: /admin/\n" .
            "Disallow: /api/\n" .
            "Disallow: /userfiles/modules/\n" .
            "Disallow: /userfiles/templates/\n\n" .
            "Sitemap: " . url('sitemap.xml');

        return $form
            ->schema([
                Section::make('Robots.txt Settings')
                    ->description('Configure your robots.txt file content')
                    ->schema([
                        Textarea::make('options.website.robots_txt')
                            ->label('Robots.txt Content')
                            ->helperText(new HtmlString('The robots.txt file tells search engine crawlers which URLs they can access on your site.<br>Learn more about <a href="https://developers.google.com/search/docs/crawling-indexing/robots/intro" target="_blank" class="text-primary-600 hover:text-primary-500">robots.txt</a>.'))
                            ->placeholder($defaultRobotsTxt)
                            ->rows(10)
                            ->columnSpanFull()
                            ->live(),
                    ])
            ]);
    }
}
