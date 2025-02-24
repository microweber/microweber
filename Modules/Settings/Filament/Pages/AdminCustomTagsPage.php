<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminCustomTagsPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-settings';
    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';
    protected static ?string $title = 'Custom Tags';
    protected static string $description = 'Configure custom HTML tags for head and footer';
    protected static ?string $navigationGroup = 'Website Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Custom Head Tags')
                    ->description('Add custom HTML tags to the <head> section of your website')
                    ->schema([
                        Textarea::make('options.website.website_head')
                            ->label('Head Tags')
                            ->helperText(new HtmlString('Add custom meta tags, scripts, or other HTML that will be inserted in the &lt;head&gt; section.<br>Be careful as invalid HTML can break your site.'))
                            ->placeholder('<script>
// Your custom JavaScript here
</script>
<!-- Analytics or tracking codes -->')
                            ->rows(8)
                            ->columnSpanFull()
                            ->live(),
                    ]),

                Section::make('Custom Footer Tags')
                    ->description('Add custom HTML tags to the footer of your website')
                    ->schema([
                        Textarea::make('options.website.website_footer')
                            ->label('Footer Tags')
                            ->helperText(new HtmlString('Add custom scripts, tracking codes, or other HTML that will be inserted before the closing &lt;/body&gt; tag.<br>Be careful as invalid HTML can break your site.'))
                            ->placeholder('<script>
// Your custom JavaScript here
</script>
<!-- Analytics or tracking codes -->')
                            ->rows(8)
                            ->columnSpanFull()
                            ->live(),
                    ]),

                Section::make('Custom Ads.txt')
                    ->description('Configure your ads.txt file content')
                    ->schema([
                        Textarea::make('options.website.ads_txt')
                            ->label('Ads.txt Content')
                            ->helperText(new HtmlString('The ads.txt file is used to help prevent unauthorized inventory sales.<br>Learn more about <a href="https://iabtechlab.com/ads-txt/" target="_blank" class="text-primary-600 hover:text-primary-500">ads.txt</a>.'))
                            ->placeholder('google.com, pub-0000000000000000, DIRECT, f08c47fec0942fa0')
                            ->rows(5)
                            ->columnSpanFull()
                            ->live(),
                    ])
            ]);
    }
}
