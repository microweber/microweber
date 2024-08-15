<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Exceptions\Halt;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Livewire\Attributes\On;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\EditCampaign;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\ManageCampaigns;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages\ManageTemplates;
use MicroweberPackages\Modules\Newsletter\Filament\Components\SelectTemplate;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignPixel;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriberList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;

class CampaignResource extends Resource
{
    protected static ?string $model = NewsletterCampaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

//    protected static ?string $slug = 'newsletter/sender-accounts';

//    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'Campaigns';
    protected static ?string $navigationLabel = 'Campaigns';

    protected static ?string $navigationGroup = 'Campaigns';
    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->poll('10s')
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('list.name'),
                TextColumn::make('subscribers')->badge(),
//                TextColumn::make('scheduled'),
//                TextColumn::make('scheduled_at'),
                TextColumn::make('opened')
                    ->badge()
                    ->color(function() {
                        return 'success';
                    }),
                TextColumn::make('clicked')
                    ->badge()
                    ->color(function() {
                        return 'success';
                    }),
                Tables\Columns\ViewColumn::make('status')
                        ->view('microweber-module-newsletter::livewire.filament.columns.campaign-status'),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->hidden(function (NewsletterCampaign $campaign) {
                        if ($campaign->status == NewsletterCampaign::STATUS_DRAFT) {
                            return false;
                        }
                        return true;
                    })
                    ->url(fn (NewsletterCampaign $campaign) => route('filament.admin-newsletter.pages.edit-campaign.{id}', $campaign->id)),

                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->hidden(function (NewsletterCampaign $campaign) {
                        if (($campaign->status == NewsletterCampaign::STATUS_QUEUED)
                            || ($campaign->status == NewsletterCampaign::STATUS_PENDING)
                            || ($campaign->status == NewsletterCampaign::STATUS_PROCESSING)) {
                            return false;
                        }
                        return true;
                    })
                    ->requiresConfirmation()
                    ->icon('heroicon-o-x-circle')->action(function (NewsletterCampaign $campaign) {
                        $campaign->status = NewsletterCampaign::STATUS_CANCELED;
                        $campaign->save();
                    }),

                Tables\Actions\ActionGroup::make([

                    Tables\Actions\Action::make('expand-opened')
                        ->label(function (NewsletterCampaign $campaign) {
                            $html = 'Expand opened' . ' <span class="text-green-500">(' . NewsletterCampaignPixel::where('campaign_id', $campaign->id)->count() . ')</span>';

                            return new HtmlString($html);
                        })
                        ->action(function (NewsletterCampaign $campaign) {

                            $subscriberIds = [];
                            $getOpened = NewsletterCampaignPixel::where('campaign_id', $campaign->id)->get();
                            if ($getOpened) {
                                foreach ($getOpened as $opened) {
                                    $findSubscriber = NewsletterSubscriber::select(['id','email'])->where('email', $opened->email)->first();
                                    if ($findSubscriber) {
                                        $subscriberIds[] = $findSubscriber->id;
                                    }
                                }
                            }

                            if (empty($subscriberIds)) {
                                Notification::make()
                                    ->title('No opened emails from subscribers for this campaign')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $newCampaignName = $campaign->name . ' - Opened';
                            $newCampaignListName = $campaign->name . ' - List of opened';

                            $checkCampaignName = NewsletterCampaign::where('name', $newCampaignName)->first();
                            if ($checkCampaignName) {
                                Notification::make()
                                    ->title('This campaign already expanded. Please continue the campaign.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $newCampaignList = new NewsletterList();
                            $newCampaignList->name = $newCampaignListName;
                            $newCampaignList->save();

                            foreach ($subscriberIds as $subscriberId) {
                                $newSubscriberInList = new NewsletterSubscriberList();
                                $newSubscriberInList->subscriber_id = $subscriberId;
                                $newSubscriberInList->list_id = $newCampaignList->id;
                                $newSubscriberInList->save();
                            }

                            $newCampaign = new NewsletterCampaign();
                            $newCampaign->name = $newCampaignName;
                            $newCampaign->status = NewsletterCampaign::STATUS_DRAFT;
                            $newCampaign->email_content_html = "Hello, {{name}}! <br />How are you today?";
                            $newCampaign->email_content_type = 'design';
                            $newCampaign->list_id = $newCampaignList->id;
                            $newCampaign->recipients_from = 'specific_list';
                            $newCampaign->sender_account_id = $campaign->sender_account_id;
                            $newCampaign->save();

                            return redirect()->route('filament.admin-newsletter.pages.edit-campaign.{id}', $newCampaign->id);

                        })
                        ->icon('heroicon-o-envelope-open'),

                    Tables\Actions\Action::make('expand-clicked')
                        ->label(function (NewsletterCampaign $campaign) {
                            $html = 'Expand clicked' . ' <span class="text-green-500">(' . NewsletterCampaignClickedLink::where('campaign_id', $campaign->id)->count() . ')</span>';

                            return new HtmlString($html);
                        })
                        ->action(function (NewsletterCampaign $campaign) {

                            $subscriberIds = [];
                            $getClicked = NewsletterCampaignClickedLink::where('campaign_id', $campaign->id)->get();
                            if ($getClicked) {
                                foreach ($getClicked as $clicked) {
                                    $findSubscriber = NewsletterSubscriber::select(['id','email'])->where('email', $clicked->email)->first();
                                    if ($findSubscriber) {
                                        $subscriberIds[] = $findSubscriber->id;
                                    }
                                }
                            }

                            if (empty($subscriberIds)) {
                                Notification::make()
                                    ->title('No clicked subscribers found for this campaign')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $newCampaignName = $campaign->name . ' - Clicked';
                            $newCampaignListName = $campaign->name . ' - List of clicked';

                            $checkCampaignName = NewsletterCampaign::where('name', $newCampaignName)->first();
                            if ($checkCampaignName) {
                                Notification::make()
                                    ->title('This campaign already expanded. Please continue the campaign.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $newCampaignList = new NewsletterList();
                            $newCampaignList->name = $newCampaignListName;
                            $newCampaignList->save();

                            foreach ($subscriberIds as $subscriberId) {
                                $newSubscriberInList = new NewsletterSubscriberList();
                                $newSubscriberInList->subscriber_id = $subscriberId;
                                $newSubscriberInList->list_id = $newCampaignList->id;
                                $newSubscriberInList->save();
                            }

                            $newCampaign = new NewsletterCampaign();
                            $newCampaign->name = $newCampaignName;
                            $newCampaign->status = NewsletterCampaign::STATUS_DRAFT;
                            $newCampaign->email_content_html = "Hello, {{name}}! <br />How are you today?";
                            $newCampaign->email_content_type = 'design';
                            $newCampaign->list_id = $newCampaignList->id;
                            $newCampaign->recipients_from = 'specific_list';
                            $newCampaign->sender_account_id = $campaign->sender_account_id;
                            $newCampaign->save();

                            return redirect()->route('filament.admin-newsletter.pages.edit-campaign.{id}', $newCampaign->id);

                        })
                        ->icon('heroicon-o-cursor-arrow-rays'),

                    Tables\Actions\DeleteAction::make(),
                ])
                    ->icon('mw-dots-menu')
                    ->color(Color::Gray)
                    ->iconSize('lg'),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCampaigns::route('/'),
//            'edit' => EditCampaign::route('/{record}/edit'),
        ];
    }
}
