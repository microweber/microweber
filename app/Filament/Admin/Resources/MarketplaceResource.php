<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MarketplaceResource\Pages;
use App\Filament\Admin\Resources\MarketplaceResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;

use Filament\Forms\Get;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Marketplace\Models\MarketplaceItem;
use MicroweberPackages\Module\Models\Module;
use MicroweberPackages\Package\MicroweberComposerClient;
use function Clue\StreamFilter\fun;

class MarketplaceResource extends Resource
{
    protected static ?string $model = MarketplaceItem::class;
    protected static ?string $navigationIcon = 'mw-marketplace';
    protected static ?string $navigationLabel = 'Marketplace';

    protected static ?string $breadcrumb = 'Marketplace';

    protected static ?string $pluralLabel = 'Marketplaces';

    protected static ?string $slug = 'marketplace';

    public static function form(Form $form): Form
    {
        return $form
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
                        ->backgroundCropped(100)
                        ->imageUrl(function (MarketplaceItem $marketplaceItem) {
                            return $marketplaceItem->screenshot_link;
                        })->columnSpanFull(),

                    Tables\Columns\TextColumn::make('name')
                        ->searchable()
                        ->columnSpanFull()
                        ->weight(FontWeight::Bold),

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
                //
            ])
            ->actions([
//                Tables\Actions\ViewAction::make()
//                    ->slideOver()
//                    ->modalCancelAction(false),

                Tables\Actions\EditAction::make('view-details')
                    ->modalHeading('View Marketplace Item')
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false)
                    ->icon('heroicon-m-cloud-arrow-down')
                    ->slideOver()
                    ->form([

                        Forms\Components\Section::make('Package Details Section')
                            ->heading(false)
                            ->columns(2)
                            ->schema([

                                Forms\Components\Placeholder::make('Package Screenshot')
                                    ->label(false)
                                    ->content(function (MarketplaceItem $marketplaceItem) {
                                        return view('filament-forms::components.placeholder-image-cropped',[
                                            'image' => $marketplaceItem->screenshot_link
                                        ]);
                                    }),

                                Forms\Components\Section::make('Package Information')
                                    ->heading(false)
                                    ->columnSpan(1)
                                    ->columns(1)
                                    ->schema([

                                        Forms\Components\Placeholder::make('Package Name')
                                            ->label(false)
                                            ->content(function (MarketplaceItem $marketplaceItem) {
                                                return new HtmlString("<h2 class='text-2xl'>{$marketplaceItem->name}</h2>");
                                            }),

                                        Forms\Components\Placeholder::make('Package Details')
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

                                        Forms\Components\Actions::make([

                                            Forms\Components\Actions\Action::make('installPackageVersion')
                                                ->label('Download & install')
                                                ->icon('heroicon-m-cloud-arrow-down')
                                                ->slideOver()
                                                ->modalIcon('heroicon-m-cloud-arrow-down')
                                                ->modalIconColor('success')
                                                ->modalHeading(function (MarketplaceItem $marketplaceItem) {
                                                    return "Install {$marketplaceItem->name}";
                                                })
                                                ->form([

                                                    Forms\Components\TextInput::make('license_key')
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

                                                    Forms\Components\Select::make('version')
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
                                                    Forms\Components\Placeholder::make('screenshot')
                                                        ->label(false)
                                                        ->content(function (MarketplaceItem $marketplaceItem) {
                                                            $screenshotHtml = view('filament-forms::components.placeholder-image-cropped',[
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
            ->bulkActions([

            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

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
            'index' => Pages\ListMarketplaces::route('/')
        ];
    }
}
