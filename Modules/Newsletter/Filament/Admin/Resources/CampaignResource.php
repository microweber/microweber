<?php

namespace Modules\Newsletter\Filament\Admin\Resources;

use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\View;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\Width as MaxWidth;
use Filament\Support\Exceptions\Halt;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Livewire\Attributes\On;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\EditCampaign;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\ManageCampaigns;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\CreateCampaign;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages\ManageTemplates;
use Modules\Newsletter\Filament\Components\SelectTemplate;
use Modules\Newsletter\Filament\Exports\NewsletterCampaignExporter;

// Added exporter import
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use Modules\Newsletter\Models\NewsletterCampaignPixel;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use Modules\Newsletter\Models\NewsletterTemplate;
use Modules\Newsletter\Support\NewsletterPlaceholderSyntax;
use Illuminate\Support\Arr;

class CampaignResource extends Resource
{
    protected static ?string $model = NewsletterCampaign::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-megaphone';

    //    protected static ?string $slug = 'newsletter/sender-accounts';

    //    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'Campaign';
    protected static ?string $pluralLabel = 'Campaigns';
    protected static string | null $navigationLabel = 'Campaigns';

    protected static string | \UnitEnum | null $navigationGroup = 'Campaigns';
    protected static ?int $navigationSort = 2;

    protected static bool $isGloballySearchable = true;

    protected static ?bool $isGlobalSearchForcedCaseInsensitive = true;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'subject', 'from_name', 'from_email'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        $details = [];

        if ($record->subject) {
            $details['Subject'] = \Illuminate\Support\Str::limit($record->subject, 80);
        }

        if ($record->status) {
            $details['Status'] = ucfirst($record->status);
        }

        return $details;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Campaign Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('subject')
                    ->label('Email Subject')
                    ->helperText('The subject line recipients will see in their inbox.')
                    ->maxLength(255),

                Select::make('recipients_from')
                    ->label('Recipients From')
                    ->options([
                        'specific_list' => 'Specific list',
                        'all' => 'All subscribers',
                    ])
                    ->default('specific_list')
                    ->afterStateHydrated(function (Select $component, $state): void {
                        if (blank($state)) {
                            $component->state('specific_list');
                        }
                    })
                    ->required(),

                Select::make('list_id')
                    ->label('List')
                    ->options(fn () => NewsletterList::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(fn (Get $get): bool => ($get('recipients_from') ?? 'specific_list') === 'specific_list')
                    ->hidden(fn (Get $get): bool => ($get('recipients_from') ?? 'specific_list') !== 'specific_list'),

                Select::make('sender_account_id')
                    ->label('Sender Account')
                    ->options(fn () => NewsletterSenderAccount::all()->mapWithKeys(fn ($account) => [
                        $account->id => $account->from_name . ' (' . $account->from_email . ')',
                    ]))
                    ->searchable()
                    ->preload(),

                TextInput::make('from_name')
                    ->label('From Name')
                    ->maxLength(255),

                TextInput::make('from_email')
                    ->label('From Email')
                    ->email()
                    ->maxLength(255),

                TextInput::make('reply_email')
                    ->label('Reply To Email')
                    ->email()
                    ->maxLength(255),

                DateTimePicker::make('scheduled_at')
                    ->label('Scheduled At')
                    ->seconds(false)
                    ->helperText('Leave empty to keep the campaign unscheduled.'),

                Select::make('email_content_type')
                    ->label('Email Content Type')
                    ->options([
                        'design' => 'Design (visual editor)',
                        'html' => 'HTML (plain text)',
                    ])
                    ->default('design')
                    ->required(),

                Select::make('status')
                    ->options([
                        NewsletterCampaign::STATUS_DRAFT => 'Draft',
                        NewsletterCampaign::STATUS_QUEUED => 'Queued',
                        NewsletterCampaign::STATUS_PENDING => 'Pending',
                        NewsletterCampaign::STATUS_SCHEDULED => 'Scheduled',
                        NewsletterCampaign::STATUS_SENDING => 'Sending',
                        NewsletterCampaign::STATUS_PROCESSING => 'Processing',
                        NewsletterCampaign::STATUS_FINISHED => 'Finished',
                        NewsletterCampaign::STATUS_FAILED => 'Failed',
                        NewsletterCampaign::STATUS_CANCELED => 'Canceled',
                    ])
                    ->default(NewsletterCampaign::STATUS_DRAFT)
                    ->required()
                    ->hidden(fn(callable $get) => $get('id') === null),

                Textarea::make('email_content_html')
                    ->label('Email Content HTML')
                    ->helperText(NewsletterPlaceholderSyntax::basicHelperText())
                    ->hidden(fn(callable $get) => $get('email_content_type') !== 'html'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // audit-test 2026-05-08 PM TASK-019 / TICKET-AN finding #11
            // (regression fix per cycle-56):
            // ->poll('10s') was unconditional — fired a Livewire round-trip
            // every 10s on every Campaigns list view, including pages with
            // only finished/draft campaigns where there is nothing to update.
            // Cycle-54 first attempt used ->poll('10s', condition: fn ()...) but
            // Filament's Table::poll() signature is `string|Closure|null $interval`
            // ONLY — there is no `condition:` named arg. The original cycle-54
            // call therefore raised a fatal error at runtime (HTTP 500 on
            // /admin/newsletter/campaigns). Correct shape per Filament docs:
            // pass a Closure as the single $interval arg. The Closure returns
            // '10s' when polling should run, or null to disable polling.
            // Trigger states:
            //   STATUS_QUEUED      — waiting in dispatcher queue
            //   STATUS_PENDING     — accepted by sender, not yet started
            //   STATUS_PROCESSING  — recipient list being built
            //   STATUS_SENDING     — actively delivering to recipients
            // Stable states (DRAFT / SCHEDULED / FINISHED / FAILED /
            // CANCELED) cannot transition without admin action and don't
            // benefit from polling.
            ->poll(fn () => NewsletterCampaign::query()
                ->whereIn('status', [
                    NewsletterCampaign::STATUS_QUEUED,
                    NewsletterCampaign::STATUS_PENDING,
                    NewsletterCampaign::STATUS_PROCESSING,
                    NewsletterCampaign::STATUS_SENDING,
                ])
                ->exists() ? '10s' : null)
            ->modifyQueryUsing(fn ($query) => $query
                ->with('list')
                ->withCount(['listSubscribers', 'openedPixels', 'clickedLinks']))
            ->columns([
                TextColumn::make('mobile_summary')
                    ->label('Campaign')
                    ->state(fn (NewsletterCampaign $record): string => $record->name)
                    ->searchable(['name', 'subject'])
                    ->description(function (NewsletterCampaign $record): string {
                        return collect([
                            $record->subject,
                            $record->list?->name ? 'List: ' . $record->list->name : null,
                        ])->filter()->implode(' • ');
                    })
                    ->hiddenFrom('md'),
                TextColumn::make('name')
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('subject')
                    ->searchable()
                    ->toggleable()
                    ->visibleFrom('md'),
                TextColumn::make('list.name')
                    ->visibleFrom('sm'),
                TextColumn::make('subscribers')
                    ->color(function () {
                        return 'gray';
                    })
                    ->alignCenter()
                    ->visibleFrom('md'),
//                TextColumn::make('scheduled'),
//                TextColumn::make('scheduled_at'),
                TextColumn::make('opened')
                    ->alignCenter()
                    ->color(function () {
                        return 'success';
                    })
                    ->visibleFrom('md'),
                TextColumn::make('clicked')
                    ->alignCenter()
                    ->color(function () {
                        return 'info';
                    })
                    ->visibleFrom('md'),
                Tables\Columns\ViewColumn::make('status')->alignCenter()
                    ->view('microweber-module-newsletter::livewire.filament.columns.campaign-status'),

                TextColumn::make('status_log')
                    ->visibleFrom('lg'),
            ])
            ->defaultSort('created_at', 'desc')
            // audit-test 2026-05-08 PM TASK-019 / TICKET-AN finding #8:
            // empty filters array forced admins to scroll the whole list to
            // find a status / list / sender. Productivity blocker on shops
            // with 100k+ campaigns. Status options enumerated from the model
            // STATUS_* constants so they stay in sync if a new status lands.
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        NewsletterCampaign::STATUS_DRAFT => 'Draft',
                        NewsletterCampaign::STATUS_QUEUED => 'Queued',
                        NewsletterCampaign::STATUS_PENDING => 'Pending',
                        NewsletterCampaign::STATUS_SCHEDULED => 'Scheduled',
                        NewsletterCampaign::STATUS_SENDING => 'Sending',
                        NewsletterCampaign::STATUS_PROCESSING => 'Processing',
                        NewsletterCampaign::STATUS_FINISHED => 'Finished',
                        NewsletterCampaign::STATUS_FAILED => 'Failed',
                        NewsletterCampaign::STATUS_CANCELED => 'Canceled',
                    ])
                    ->multiple(),
                \Filament\Tables\Filters\SelectFilter::make('list_id')
                    ->label('List')
                    ->relationship('list', 'name')
                    ->searchable()
                    ->preload(),
                \Filament\Tables\Filters\SelectFilter::make('sender_account_id')
                    ->label('Sender Account')
                    ->relationship('senderAccount', 'from_email')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([ // Added header actions
                \Filament\Actions\ExportAction::make()
                    ->exporter(NewsletterCampaignExporter::class)
                    ->icon('heroicon-m-cloud-arrow-down')
                    // audit-test 2026-05-08 PM TASK-019 / TICKET-AN finding #10:
                    // dynamically-generated checkbox loop now wrapped in
                    // Section::make('Columns to export') and the multi-file
                    // toggle in Section::make('Export options'). Gives the
                    // form fieldset / heading semantics SR users expect.
                    ->form(function (\Filament\Actions\ExportAction $action): array {
                        $exportColumns = NewsletterCampaignExporter::getColumns();
                        $columnCheckboxes = [];
                        foreach ($exportColumns as $column) {
                            $columnCheckboxes[] = \Filament\Forms\Components\Checkbox::make($column->getName())
                                ->label($column->getLabel())
                                ->default(true);
                        }
                        return [
                            \Filament\Schemas\Components\Section::make('Columns to export')
                                ->schema($columnCheckboxes),
                            \Filament\Schemas\Components\Section::make('Export options')
                                ->schema([
                                    Checkbox::make('export_multiple')
                                        ->label('Export to multiple files (ZIP)'),
                                ]),
                        ];
                    })
                    ->action(function (array $data) {
                        $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                        $exportMultiple = $data['export_multiple'] ?? false;
                        $url = route('filament.admin-newsletter.export.campaigns', ['columns' => $selectedColumns, 'export_multiple' => $exportMultiple]);
                        return redirect()->to($url);
                    }),
            ])
            // audit-test 2026-05-08 PM TASK-019 / TICKET-AN finding #5:
            // contextual primary actions (Edit / View / Cancel) intentionally
            // come BEFORE the 3-dot ActionGroup so a keyboard user reaches
            // them first via Tab. Delete stays inside the ActionGroup
            // dropdown so it isn't reached on the primary tab cycle. Do not
            // re-order without re-reading this comment. The Cancel
            // ->requiresConfirmation() modal is keyboard-accessible by
            // default (Filament uses a focus-trapped <dialog> with
            // Enter/Space activation on the confirm button).
            ->actions([

                \Filament\Actions\Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->hidden(function (NewsletterCampaign $campaign) {
                        return $campaign->status !== NewsletterCampaign::STATUS_DRAFT;
                    })
                    ->url(fn(NewsletterCampaign $campaign) => route('filament.admin-newsletter.pages.edit-campaign.{id}', $campaign->id)),

                \Filament\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->hidden(function (NewsletterCampaign $campaign) {
                        return $campaign->status === NewsletterCampaign::STATUS_DRAFT;
                    })
                    ->url(fn(NewsletterCampaign $campaign) => route('filament.admin-newsletter.pages.edit-campaign.{id}', $campaign->id)),

                \Filament\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->hidden(function (NewsletterCampaign $campaign) {
                        return !in_array($campaign->status, [
                            NewsletterCampaign::STATUS_QUEUED,
                            NewsletterCampaign::STATUS_PENDING,
                            NewsletterCampaign::STATUS_PROCESSING,
                            NewsletterCampaign::STATUS_SENDING,
                        ]);
                    })
                    ->requiresConfirmation()
                    ->icon('heroicon-o-x-circle')->action(function (NewsletterCampaign $campaign) {
                        $campaign->status = NewsletterCampaign::STATUS_CANCELED;
                        $campaign->save();
                    }),

                ActionGroup::make([

                    \Filament\Actions\Action::make('expand-opened')
                        ->label(function (NewsletterCampaign $campaign) {
                            $html = 'Expand opened' . ' <span class="text-green-500">(' . $campaign->opened . ')</span>';

                            return new HtmlString($html);
                        })
                        ->action(function (NewsletterCampaign $campaign) {
                            $subscriberIds = self::resolveSubscriberIdsForEmails(
                                NewsletterCampaignPixel::query()
                                    ->where('campaign_id', $campaign->id)
                                    ->pluck('email')
                                    ->all()
                            );

                            if (empty($subscriberIds)) {
                                Notification::make()
                                    ->title('No opened emails from subscribers for this campaign')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $newCampaign = self::createExpandedCampaign(
                                $campaign,
                                $subscriberIds,
                                'Opened',
                                'List of opened'
                            );

                            return redirect()->route('filament.admin-newsletter.pages.edit-campaign.{id}', $newCampaign->id);

                        })
                        ->icon('heroicon-o-envelope-open'),

                    \Filament\Actions\Action::make('expand-clicked')
                        ->label(function (NewsletterCampaign $campaign) {
                            $html = 'Expand clicked' . ' <span class="text-green-500">(' . $campaign->clicked . ')</span>';

                            return new HtmlString($html);
                        })
                        ->action(function (NewsletterCampaign $campaign) {
                            $subscriberIds = self::resolveSubscriberIdsForEmails(
                                NewsletterCampaignClickedLink::query()
                                    ->where('campaign_id', $campaign->id)
                                    ->pluck('email')
                                    ->all()
                            );

                            if (empty($subscriberIds)) {
                                Notification::make()
                                    ->title('No clicked subscribers found for this campaign')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            $newCampaign = self::createExpandedCampaign(
                                $campaign,
                                $subscriberIds,
                                'Clicked',
                                'List of clicked'
                            );

                            return redirect()->route('filament.admin-newsletter.pages.edit-campaign.{id}', $newCampaign->id);

                        })
                        ->icon('heroicon-o-cursor-arrow-rays'),

\Filament\Actions\DeleteAction::make(),
                ])
                ->icon('heroicon-o-ellipsis-vertical')
                ->color(Color::Gray)
                ->iconSize('lg'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([ // Added bulk actions
                    \Filament\Actions\ExportBulkAction::make()
                        ->exporter(NewsletterCampaignExporter::class)
                        // audit-test 2026-05-08 PM TASK-019 / TICKET-AN finding #10:
                        // bulk-export form Section grouping (matches the
                        // single-export form above).
                        ->form(function (\Filament\Actions\BulkAction $action): array {
                            $exportColumns = NewsletterCampaignExporter::getColumns();
                            $columnCheckboxes = [];
                            foreach ($exportColumns as $column) {
                                $columnCheckboxes[] = \Filament\Forms\Components\Checkbox::make($column->getName())
                                    ->label($column->getLabel())
                                    ->default(true);
                            }
                            return [
                                \Filament\Schemas\Components\Section::make('Columns to export')
                                    ->schema($columnCheckboxes),
                                \Filament\Schemas\Components\Section::make('Export options')
                                    ->schema([
                                        Checkbox::make('export_multiple')
                                            ->label('Export to multiple files (ZIP)')
                                            ->default(false),
                                    ]),
                            ];
                        })
                        ->action(function (array $data, \Filament\Actions\BulkAction $action) {
                            $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                            $selectedRecordIds = $action->getRecords()->pluck('id')->toArray();
                            $route = route('filament.admin-newsletter.export.campaigns', ['columns' => $selectedColumns, 'selected_ids' => implode(',', $selectedRecordIds), 'export_multiple' => $data['export_multiple'] ?? false]);
                            return redirect()->to($route);
                        }),
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No campaigns yet')
            ->emptyStateDescription('Create your first email campaign to start reaching your subscribers.')
            ->emptyStateActions([
                \Filament\Actions\Action::make('createCampaign')
                    ->label('Create Campaign')
                    ->icon('heroicon-o-rocket-launch')
                    ->url(route('filament.admin-newsletter.pages.create-campaign')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCampaigns::route('/'),
            'create' => CreateCampaign::route('/create'),
            'edit' => EditCampaign::route('/{record}/edit'),
        ];
    }

    protected static function resolveSubscriberIdsForEmails(array $emails): array
    {
        $emails = array_values(array_unique(array_filter($emails)));

        if (empty($emails)) {
            return [];
        }

        return NewsletterSubscriber::query()
            ->whereIn('email', $emails)
            ->pluck('id')
            ->unique()
            ->values()
            ->all();
    }

    protected static function createExpandedCampaign(
        NewsletterCampaign $campaign,
        array $subscriberIds,
        string $campaignSuffix,
        string $listSuffix
    ): NewsletterCampaign {
        $newCampaignName = $campaign->name . ' - ' . $campaignSuffix;
        $newCampaignListName = $campaign->name . ' - ' . $listSuffix;

        if (NewsletterCampaign::where('name', $newCampaignName)->exists()) {
            Notification::make()
                ->title('This campaign already expanded. Please continue the campaign.')
                ->danger()
                ->send();
            throw new Halt();
        }

        $newCampaignList = NewsletterList::query()->create([
            'name' => $newCampaignListName,
        ]);

        NewsletterSubscriberList::query()->insert(array_map(
            fn (int $subscriberId): array => [
                'subscriber_id' => $subscriberId,
                'list_id' => $newCampaignList->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            array_values(array_unique($subscriberIds))
        ));

        return NewsletterCampaign::query()->create([
            'name' => $newCampaignName,
            'status' => NewsletterCampaign::STATUS_DRAFT,
            'email_content_html' => NewsletterPlaceholderSyntax::defaultCampaignBody(),
            'email_content_type' => 'design',
            'list_id' => $newCampaignList->id,
            'recipients_from' => 'specific_list',
            'sender_account_id' => $campaign->sender_account_id,
        ]);
    }
}
