<?php

namespace Modules\Marketplace\Filament\Admin;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;

use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Filament\Support\AdminDisplayName;
use MicroweberPackages\Filament\Tables\Columns\BadgesColumn;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Module\ModuleManager;
use MicroweberPackages\Package\MicroweberComposerClient;
use Modules\Marketplace\Models\MarketplaceItem;

class MarketplaceResource extends Resource
{
    protected static ?string $model = MarketplaceItem::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-storefront';
    protected static string | null $navigationLabel = 'Marketplace';

    protected static string | \UnitEnum | null $navigationGroup = 'Marketplace';
    protected static ?int $navigationSort = 1;

    protected static ?string $breadcrumb = 'Marketplace';

    // task-2026-05-23-b66561 / AI-1047 — match sidebar nav label (singular 'Marketplace').
    // Filament uses $pluralLabel for the list-page heading; overriding it prevents
    // the auto-pluralisation that would render 'Marketplaces'.
    protected static ?string $pluralLabel = 'Marketplace';

    protected static ?string $slug = 'marketplace';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description', 'type'];
    }
    public static string $description = 'Extend your website with modules and themes';
    public function getDescription(): string
    {

        return static::$description;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                Tables\Columns\Layout\Stack::make([

                    ImageUrlColumn::make('screenshot_link')
                        ->backgroundCropped(176)
                        ->imageUrl(function (MarketplaceItem $marketplaceItem) {
                            return $marketplaceItem->screenshot_link;
                        })->columnSpanFull(),

                    Tables\Columns\TextColumn::make('name')
                        ->searchable()
                        // AI-785 (task-2026-05-17-4905c8) — pass module/template
                        // names through the AdminDisplayName helper so CamelCase
                        // folder identifiers ("LayoutContent", "AiWizard",
                        // "CustomFields") render as "Layout content", "AI wizard",
                        // "Custom fields" on the Marketplace card. Names that
                        // are already display-format (e.g. "Car Services")
                        // pass through unchanged (idempotent).
                        ->formatStateUsing(fn (?string $state): string => AdminDisplayName::format($state))
                        ->columnSpanFull()
                        ->weight(FontWeight::Bold),

                    // AI-786 (task-2026-05-17-4905c8) — render description on
                    // each marketplace card. The MarketplaceItem.description
                    // field exists but was hidden from the card layout — user
                    // had to open the slide-over to read what each card was.
                    // Truncated to 120 chars (Filament default ellipsis) so
                    // long descriptions don't blow up the card grid; full
                    // description still visible in the view-details slide-over.
                    // task-2026-05-23-70fca2 / AI-1048 — hide row when description is empty;
                    // MarketplaceItem now defaults to '' instead of 'No description'.
                    Tables\Columns\TextColumn::make('description')
                        ->limit(120)
                        ->color('gray')
                        ->size(\Filament\Support\Enums\TextSize::Small)
                        ->columnSpanFull()
                        ->placeholder(null)
                        ->extraAttributes(['class' => 'mw-marketplace-card-description']),

                    BadgesColumn::make('badges')->badges(function (MarketplaceItem $marketplaceItem) {
                        $badges = [];
                        if ($marketplaceItem['has_current_install'] == 1) {
                            $badges[] = [
                                'label' => 'Installed',
                                'color' => 'success',
                            ];
                        } else {
                            if ($marketplaceItem['available_for_install'] == 1) {
                                $badges[] = [
                                    'label' => 'Available for install',
                                    'color' => 'primary',
                                ];
                            }
                            if ($marketplaceItem['is_paid'] == 1) {
                                // task-2026-05-23-e858f6 / AI-1049 — Premium badge uses
                                // 'warning' (amber/gold) for semantic "paid tier" meaning.
                                // 'success' (green) was the source of the "teal" report —
                                // the Free badge below was the dominant green on the page.
                                $badges[] = [
                                    'label' => 'Premium',
                                    'color' => 'warning',
                                ];
                            } else {
                                // Free tier: 'gray' rather than 'success' (green) so
                                // a non-installed free package doesn't look like a success state.
                                $badges[] = [
                                    'label' => 'Free',
                                    'color' => 'gray',
                                ];
                            }
                        }
                        return $badges;
                    })

                ])
                ->space(3)
                ->alignment(Alignment::Center),

            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 3,
            ])
            ->paginationPageOptions([
                24,
                50,
                100,
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'microweber-module' => 'Modules',
                        'microweber-template' => 'Templates',
                    ]),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'installed' => 'Installed',
                        'available' => 'Available for Install',
                        'has_update' => 'Has Updates',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'installed') {
                            return $query->where('has_current_install', 1);
                        } elseif ($data['value'] === 'available') {
                            return $query->where('has_current_install', 0)
                                         ->where('available_for_install', 1);
                        } elseif ($data['value'] === 'has_update') {
                            return $query->where('has_update', 1);
                        }
                        return $query;
                    }),
                SelectFilter::make('pricing')
                    ->label('Pricing')
                    ->options([
                        'free' => 'Free',
                        'premium' => 'Premium',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'free') {
                            return $query->where('is_paid', 0);
                        } elseif ($data['value'] === 'premium') {
                            return $query->where('is_paid', 1);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('update')
                    ->label('Update')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->visible(function (MarketplaceItem $marketplaceItem) {
                        return $marketplaceItem->has_update == 1 && $marketplaceItem->has_current_install == 1;
                    })
                    ->requiresConfirmation()
                    ->modalHeading(function (MarketplaceItem $marketplaceItem) {
                        return "Update {$marketplaceItem->name}";
                    })
                    ->modalDescription('This will update the module to the latest version. Are you sure?')
                    ->modalSubmitActionLabel('Yes, Update')
                    ->action(function (MarketplaceItem $marketplaceItem) {
                        try {
                            $runner = new MicroweberComposerClient();
                            $results = $runner->requestInstall([
                                'require_name' => $marketplaceItem->internal_name,
                                'require_version' => $marketplaceItem->version,
                            ]);
                            
                            // Clear marketplace cache
                            Cache::forget('livewire-marketplace');
                            
                            if (isset($results['success'])) {
                                return redirect()->back()->with('success', "{$marketplaceItem->name} has been updated successfully.");
                            } else {
                                return redirect()->back()->with('error', $results['error'] ?? 'Update failed.');
                            }
                        } catch (\Exception $e) {
                            return redirect()->back()->with('error', $e->getMessage());
                        }
                    }),
                
                Tables\Actions\Action::make('uninstall')
                    ->label('Uninstall')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(function (MarketplaceItem $marketplaceItem) {
                        return $marketplaceItem->has_current_install == 1;
                    })
                    ->requiresConfirmation()
                    ->modalHeading(function (MarketplaceItem $marketplaceItem) {
                        return "Uninstall {$marketplaceItem->name}";
                    })
                    ->modalDescription('This will completely remove the module. This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, Uninstall')
                    ->action(function (MarketplaceItem $marketplaceItem) {
                        try {
                            $moduleManager = new ModuleManager();
                            // Extract module name from internal_name
                            $moduleName = explode('/', $marketplaceItem->internal_name);
                            $moduleName = end($moduleName);
                            
                            $moduleManager->uninstall(['for_module' => $moduleName]);
                            
                            // Clear marketplace cache
                            Cache::forget('livewire-marketplace');
                            
                            return redirect()->back()->with('success', "{$marketplaceItem->name} has been uninstalled successfully.");
                        } catch (\Exception $e) {
                            return redirect()->back()->with('error', $e->getMessage());
                        }
                    }),
                
                Tables\Actions\Action::make('refresh-cache')
                    ->label('Refresh')
                    ->icon('heroicon-o-arrow-path')
                    ->color('secondary')
                    ->action(function () {
                        Cache::forget('livewire-marketplace');
                        return redirect()->back()->with('success', 'Marketplace cache has been refreshed.');
                    }),

                Tables\Actions\EditAction::make('view-details')
                    ->modalHeading('View Marketplace Item')
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false)
                    ->icon('heroicon-m-cloud-arrow-down')
                    ->slideOver()
                    ->form([

                        Section::make('Package Details Section')
                            ->heading(false)
                            ->columns(2)
                            ->schema([

                                Placeholder::make('Package Screenshot')
                                    ->label(false)
                                    ->content(function (MarketplaceItem $marketplaceItem) {
                                        return view('mw-filament::components.placeholder-image-cropped',[
                                            'image' => $marketplaceItem->screenshot_link
                                        ]);
                                    }),

                                Section::make('Package Information')
                                    ->heading(false)
                                    ->columnSpan(1)
                                    ->columns(1)
                                    ->schema([

                                        Placeholder::make('Package Name')
                                            ->label(false)
                                            ->content(function (MarketplaceItem $marketplaceItem) {
                                                return new HtmlString("<h2 class='text-2xl'>{$marketplaceItem->name}</h2>");
                                            }),

                                        Placeholder::make('Package Details')
                                            ->label(false)
                                            ->content(function (MarketplaceItem $marketplaceItem) {
                                                $html = "<p class='text-sm'>{$marketplaceItem->description}</p>";
                                                if ($marketplaceItem['version']) {
                                                    $html .= "<p class='text-sm'>Version: {$marketplaceItem->version}</p>";
                                                }
                                                if ($marketplaceItem['homepage']) {
                                                    $html .= "<p class='text-sm'>Homepage: <a href='{$marketplaceItem->homepage}' target='_blank'>{$marketplaceItem->homepage}</a></p>";
                                                }
                                                if ($marketplaceItem['authorName']) {
                                                    $html .= "<p class='text-sm'>Author: {$marketplaceItem->authorName} <a class='bold' href='mail:{$marketplaceItem->authorEmail}'>{$marketplaceItem->authorEmail}</a> </p>";
                                                }
                                                if ($marketplaceItem['license']) {
                                                    $html .= "<p class='text-sm'>License: {$marketplaceItem->license} </p>";
                                                }
                                                return new HtmlString($html);
                                            }),

                                        Actions::make([

                                            Action::make('installPackageVersion')
                                                ->label('Download & install')
                                                ->icon('heroicon-m-cloud-arrow-down')
                                                ->slideOver()
                                                ->modalIcon('heroicon-m-cloud-arrow-down')
                                                ->modalIconColor('success')
                                                ->modalHeading(function (MarketplaceItem $marketplaceItem) {
                                                    return "Install {$marketplaceItem->name}";
                                                })
                                                ->form([

                                                    TextInput::make('license_key')
                                                        ->label('License Key')
                                                        ->rules([
                                                            fn (Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {

                                                                $updateApi = mw('update');
                                                                $validateLicense = $updateApi->save_license([
                                                                    'local_key' => $value
                                                                ]);
                                                                if (isset($validateLicense['is_active'])) {
                                                                    return true;
                                                                } else {
                                                                    $fail('Invalid license key.');
                                                                }
                                                            },
                                                        ])
                                                        ->required(function (MarketplaceItem $marketplaceItem) {
                                                            if ($marketplaceItem['request_license'] == 1) {
                                                                return true;
                                                            } else {
                                                                return false;
                                                            }
                                                        })
                                                        ->hidden(function (MarketplaceItem $marketplaceItem) {
                                                            if ($marketplaceItem['request_license'] == 1) {
                                                                return false;
                                                            } else {
                                                                return true;
                                                            }
                                                        })
                                                        ->hint(function (MarketplaceItem $marketplaceItem) {
                                                            return new HtmlString("<a href='https://microweber.com/pricing#white-label' target='_blank'>You don't have a license key?</a>");
                                                        })
                                                        ->columnSpanFull(),

                                                    Select::make('version')
                                                        ->label('Version')
                                                        ->hint(function (MarketplaceItem $marketplaceItem) {
                                                            return new HtmlString("<p class='text-sm'>Latest Version: {$marketplaceItem->version}</p>");
                                                        })
                                                        ->options(function (MarketplaceItem $marketplaceItem) {
                                                            return json_decode($marketplaceItem->versions, TRUE);
                                                        })
                                                        ->default(function (MarketplaceItem $marketplaceItem) {
                                                            return $marketplaceItem->version;
                                                        })
                                                        ->required()
                                                        ->columnSpanFull(),
                                                    Placeholder::make('screenshot')
                                                        ->label(false)
                                                        ->content(function (MarketplaceItem $marketplaceItem) {
                                                            $screenshotHtml = view('mw-filament::components.placeholder-image-cropped',[
                                                                'image' => $marketplaceItem->screenshot_link,
                                                                'height' => '20rem'
                                                            ])->render();
                                                            return new HtmlString("$screenshotHtml");
                                                        }),
                                                ])
                                                ->action(function (MarketplaceItem $marketplaceItem, $data) {

                                                    try {

                                                        $runner = new MicroweberComposerClient();
                                                        $results = $runner->requestInstall([
                                                            'require_name' => $marketplaceItem->internal_name, 'require_version' => $data['version']
                                                        ]);
                                                        $install = $runner->requestInstall($results['form_data_module_params']);
                                                        if (isset($install['success'])) {
                                                            return redirect(route('filament.admin.pages.marketplace.installed-item') . '?item=' . $marketplaceItem->internal_name);
                                                        }
                                                    } catch (\Exception $e) {
                                                        return $e->getMessage();
                                                    }

                                                })
                                                ->requiresConfirmation()
                                            ])

                                    ])
                            ])
                    ]),

            ])
            // task-2026-05-23-78fbf1 / AI-1052 — empty bulkActions removes card
            // checkboxes that appeared inert (no visible bulk-action toolbar in
            // Filament v5 contentGrid layout). Individual install/update/uninstall
            // actions remain on each card. AI-1052a filed to restore bulk operations
            // once the grid-view selection affordance is resolved.
            // task-2026-05-23-78fbf1 / AI-1052 — checkboxes removed.
            // In Filament v5 contentGrid layout, the bulk-action selection toolbar
            // did not render visibly when cards were checked — the checkboxes appeared
            // completely inert. Empty array removes the selection affordance.
            // Per-card install/update/uninstall actions remain in ->actions([...]) above.
            // AI-1052a filed to restore bulk operations once grid-view selection UX resolved.
            ->bulkActions([]);
    }

public static function infolist(Schema $schema): Schema
    {
        return $schema
        ->components([

        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Modules\Marketplace\Filament\Admin\MarketplaceResource\Pages\ListMarketplaces::route('/')
        ];
    }
}
