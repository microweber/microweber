<?php

namespace Modules\WhiteLabel\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\View;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwRichEditor;

class WhiteLabelSettingsAdminSettingsPage extends AdminSettingsPage
{

    protected static bool $shouldRegisterNavigation = true;

    protected static string | \BackedEnum | null $navigationIcon = 'modules.white_label-icon';

    protected static string | \UnitEnum | null $navigationGroup = 'Customization Settings';

    protected string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'White Label';

    protected static string $description = 'Configure your White Label settings';
    protected static ?int $navigationSort = 500;

    //   protected static ?string $slug = 'settings/white-label';
    public array $options;
    public array $translatableOptions;


    public array $optionGroups = [
        'white_label'
    ];
    public string $moduleNameForOption = 'white_label';

    protected $listeners = ['license-updated' => 'refreshPage'];

    public function refreshPage()
    {
        // Redirect to the same page to refresh the license status
        return redirect()->to(static::getUrl());
    }

    protected function getHeaderActions(): array
    {
        $haveLicense = $this->checkLicense();

        //if (!$haveLicense) {
            return [
                Action::make('manage-licenses')
                    ->label('Manage Licenses')
                    ->modal()
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->modalContent(view('modules.white_label::filament.admin.show-license-manager'))
                    ->color('primary')
                    ->icon('heroicon-o-key'),
            ];
      //  }

        return [];
    }

    protected function checkLicense(): bool
    {
        $haveLicense = false;
        if (function_exists('have_license')) {
            $haveLicense = have_license('modules/white_label');
        }
        if (defined('MW_WHITE_LABEL_HAS_ACTIVE_SUBSCRIPTION') && MW_WHITE_LABEL_HAS_ACTIVE_SUBSCRIPTION == true) {
            $haveLicense = true;
        }
        return $haveLicense;
    }

    public function form(Schema $schema): Schema
    {
        $haveLicense = $this->checkLicense();

        if (!$haveLicense) {
            return $form->schema([
                Section::make('White Label License Required')
                    ->description('To access White Label features, you need a valid license.')
                    ->schema([
                        View::make('modules.white_label::filament.admin.license-required')
                    ])
            ]);
        }

        return $schema
            ->schema($this->getFormSchema());
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Settings')
                ->tabs([
                    // Branding Tab
                    Tabs\Tab::make('Branding')
                        ->schema([
                            Section::make('Brand Identity')
                                ->schema([
                                    TextInput::make('options.white_label.brand_name')
                                        ->label('Brand Name')
                                        ->helperText('Enter the name of your company')
                                        ->live(),

                                    TextInput::make('options.white_label.admin_logo_login_link')
                                        ->label('Admin Login - White Label URL')
                                        ->helperText('Enter website url of your company')
                                        ->url()
                                        ->live(),
                                ]),

                            Section::make('Logos')
                                ->schema([
                                    MwFileUpload::make('options.white_label.logo_admin')
                                        ->label('Logo for Admin Panel')
                                        ->helperText('Recommended size: 180x35 px')
                                        ->image()
                                        ->live(),

                                    /*                       MwFileUpload::make('options.white_label.logo_live_edit')
                                                               ->label('Logo for Live-Edit Toolbar')
                                                               ->helperText('Recommended size: 50x50 px')
                                                               ->image()
                                                               ->live(),

                                                           MwFileUpload::make('options.white_label.logo_login')
                                                               ->label('Logo for Login Screen')
                                                               ->helperText('Recommended size: max width 290px')
                                                               ->image()
                                                               ->live(),

                                                           MwFileUpload::make('options.white_label.brand_favicon')
                                                               ->label('Brand Favicon')
                                                               ->helperText('Favicon for the admin panel')
                                                               ->image()
                                                               ->live(),*/
                                ]),
                        ]),

                    // Footer & Support Tab
                    Tabs\Tab::make('Footer & Support')
                        ->schema([
                            Section::make('Powered By Settings')
                                ->schema([
                                    Toggle::make('options.white_label.disable_powered_by_link')
                                        ->label('Disable the "Powered By"')
                                        ->helperText('Disable the "Powered By" text in the footer')
                                        ->onIcon('heroicon-o-check')
                                        ->offIcon('heroicon-o-x-mark')
                                        ->live(),

                                    RichEditor::make('options.white_label.powered_by_link')
                                        ->label('Powered By Text')
                                        ->helperText('Enter the text you would like to see displayed in the footer of your website. Usually the text is "Powered by" followed by your company or brand name.')
                                        ->placeholder('HTML code for template footer link')
                                        ->live()
                                        ->visible(fn(callable $get) => !$get('options.white_label.disable_powered_by_link')),
                                ]),

                            Section::make('Support Links')
                                ->schema([
                                    Toggle::make('options.white_label.enable_service_links')
                                        ->label('Enable Support Links')
                                        ->helperText('Enable or disable support links')
                                        ->onIcon('heroicon-o-check')
                                        ->offIcon('heroicon-o-x-mark')
                                        ->live(),

                                    TextInput::make('options.white_label.custom_support_url')
                                        ->label('Custom Support URL')
                                        ->helperText('Enter url of your contact page')
                                        ->url()
                                        ->live()
                                        ->visible(fn(callable $get) => $get('options.white_label.enable_service_links')),
                                ]),
                        ]),

                    // Marketplace Tab
                    Tabs\Tab::make('Marketplace')
                        ->schema([
                            Section::make('Marketplace Settings')
                                ->schema([
                                    Toggle::make('options.white_label.disable_marketplace')
                                        ->label('Microweber Marketplace')
                                        ->helperText('Allow users to see Microweber Marketplace')
                                        ->onIcon('heroicon-o-check')
                                        ->offIcon('heroicon-o-x-mark')
                                        ->default(false)
                                        ->live(),

                                    TextInput::make('options.white_label.marketplace_repositories_urls')
                                        ->label('Custom Marketplace Package Manager URL')
                                        ->helperText('Enter custom marketplace repository URLs (comma separated)')
                                        ->placeholder('URL')
                                        ->live(),

                                    TextInput::make('options.white_label.marketplace_provider_id')
                                        ->label('Marketplace Provider ID')
                                        ->helperText('Enter marketplace provider ID')
                                        ->live(),

                                    TextInput::make('options.white_label.marketplace_access_code')
                                        ->label('Marketplace Access Code')
                                        ->helperText('Enter marketplace access code')
                                        ->live(),
                                ]),
                        ]),

                    // Advanced Tab
                    Tabs\Tab::make('Advanced')
                        ->schema([
                            Section::make('Advanced Settings')
                                ->schema([
                                    Toggle::make('options.white_label.hide_white_label_module_from_list')
                                        ->label('Hide White Label Module from List')
                                        ->helperText('Hide the white label module from list of modules')
                                        ->onIcon('heroicon-o-check')
                                        ->offIcon('heroicon-o-x-mark')
                                        ->default(false)
                                        ->live(),

//                                        TextInput::make('options.white_label.admin_colors_sass')
//                                            ->label('Admin Colors SASS')
//                                            ->helperText('Custom SASS for admin colors')
//                                            ->live(),
                                ]),
                        ]),
                ])
        ];

    }

}
