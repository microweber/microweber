<?php

declare(strict_types=1);

namespace Modules\StaticPageCache\Filament;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class StaticPageCacheSettingsPage extends AdminSettingsPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bolt';

    protected string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Page Cache';

    protected static string $description = 'Configure global page caching';

    protected static string|\UnitEnum|null $navigationGroup = 'System Settings';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Global Page Cache')
                    ->icon('heroicon-m-bolt')
                    ->view('mw-filament::sections.section')
                    ->description('Enable or disable full-page caching for guest visitors. Cached pages load much faster.')
                    ->schema([
                        Toggle::make('options.website.enable_full_page_cache')
                            ->label('Enable Global Page Cache')
                            ->helperText(new HtmlString(
                                'When enabled, full HTML pages are cached and served to guest visitors.<br>'
                                . 'Logged-in users always see fresh content.<br>'
                                . 'Cache is automatically cleared when content is saved.'
                            ))
                            ->live(),
                    ]),
            ]);
    }
}