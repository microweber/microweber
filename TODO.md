# TODO.md  Migration to Filament v5 (from v3)  Extremely Granular Checklist

Current date: March 04, 2026  
Target: Filament ^5.0  ^5.3.x (Livewire ^4.x, Tailwind ^4.x compatible)  
Testing: **PHPUnit only**  no Pest  
Goal: complete upgrade, modern patterns, high test coverage, zero regressions  
Estimation scale: Fibonacci (1, 2, 3, 5, 8, 13, 21, 34+)

## Phase 0  Preparation & Infrastructure (blocker phase  finish before touching resources)

- [x] 2026-03-04 Upgrade Laravel to latest compatible version (11.x or 12.x) ................................ 13
- [x] 2026-03-04 Read full upgrade guide for target Laravel version
- [x] 2026-03-04 Update composer.json: laravel/framework constraint (changed ^12 -> ^11)
- [x] 2026-03-04 Update illuminate/* packages one group at a time
- [x] 2026-03-04 Run composer why to detect locked old versions (arcanedev/support blocks Laravel 12)
- [x] 2026-03-04 Execute composer update --with-all-dependencies
- [x] 2026-03-04 Fix all Laravel deprecation notices in logs
- [x] 2026-03-04 Run php artisan optimize:clear after upgrade
- [x] 2026-03-04 Verify php artisan migrate:fresh --seed works
- [x] 2026-03-04 Test homepage loads without errors
- [x] 2026-03-04 Test admin login route responds 200
- [x] 2026-03-04 Commit upgrade as separate git commit
- [x] 2026-03-04 Install Filament v5 core packages ....................................................... 5
- [x] 2026-03-04 Filament v5.3.1 already installed (actions, forms, tables, infolists, support)
- [x] 2026-03-04 Livewire v4.2.1 confirmed - no dependency conflicts
- [x] 2026-03-04 Verified all filament/* packages at v5.3.1
- [x] 2026-03-04 No composer cache issues encountered
- [x] 2026-03-04 Execute official panel installer ........................................................ 8
- [x] 2026-03-04 Run php artisan filament:install --panels
- [x] 2026-03-04 Answer prompts: panel ID = 'admin'?
- [x] 2026-03-04 Answer: enable login page? ? yes
- [x] 2026-03-04 Answer: enable registration? ? no
- [x] 2026-03-04 Answer: enable password reset? ? yes
- [x] 2026-03-04 Inspect generated app/Providers/Filament/AdminPanelProvider.php
- [x] 2026-03-04 Verify route /admin exists and redirects to login
- [ ] Run Filament v5 upgrade helper script .................................................... 8
  - [ ] composer require filament/upgrade:"^5.0" --dev
  - [ ] Execute vendor/bin/filament-v5
  - [ ] Read every line of output carefully
  - [ ] Apply file renames / replacements manually
  - [ ] Re-run script after fixing conflicts
  - [ ] Commit upgrade changes separately
- [ ] Upgrade **all** custom Livewire components to v4 syntax .................................. 34
  - [ ] Global search: replace wire:model="..." ? wire:model.live="..."
  - [ ] Global search: replace wire:model.debounce ? wire:model.debounce.500ms
  - [ ] Fix wire:click="method" ? keep or move to Alpine if complex
  - [ ] Replace wire:keydown.enter ? wire:keydown.enter="method"
  - [ ] Update polling: wire:poll ? wire:poll.10s="refresh"
  - [ ] Replace $this->emit() ? $this->dispatch()
  - [ ] Replace @this ? $wire in Blade
  - [ ] Audit all @entangle directives
  - [ ] Fix mount() / hydrate() / dehydrate() hooks
  - [ ] Test each upgraded component in isolation
  - [ ] Check custom JS files: quick-settings.js, mw-ai.js, captcha-alpine.js
  - [ ] Run browser dev tools ? no Livewire errors in console
- [ ] Migrate custom CSS/JS/assets to Tailwind v4 + Filament v5 ................................ 21
  - [ ] Update tailwind.config.js content array (add Filament paths)
  - [ ] Replace removed classes: space-x-4 ? gap-x-4, etc.
  - [ ] Convert colors to OKLCH or use Filament palette
  - [ ] Implement dark: prefix consistently
  - [ ] Run php artisan filament:assets --force
  - [ ] Verify filament:assets published to public/vendor/filament
  - [ ] Test dark mode toggle in admin
  - [ ] Fix any z-index / shadow conflicts
  - [ ] Audit resources/css/app.css for overrides
  - [ ] Remove old Tailwind CDN links if exist
- [ ] Finalize panel architecture decision .................................................... 13
  - [ ] Write pros/cons table in docs/filament-migration.md
  - [ ] Single panel: faster initial migration
  - [ ] Multi-panel: better isolation (admin / customer / billing)
  - [ ] Draft 23 PanelProvider classes in sandbox branch
  - [ ] Decide tenant model usage (if any)
  - [ ] Document chosen approach + reasoning
- [ ] Refactor / create PanelProvider(s) with v5 fluent API ................................. 21
  - [ ] Set ->default() on primary panel
  - [ ] Set ->id('admin') ->path('admin')
  - [ ] Chain ->login() ->registration(false) ->passwordReset()
  - [ ] Set ->colors(['primary' => '#2563eb', 'gray' => 'slate'])
  - [ ] Set ->font('Inter') or system font stack
  - [ ] Use discoverResources(in: ..., for: ...)
  - [ ] Use discoverPages(in: ..., for: ...)
  - [ ] Use discoverWidgets(in: ..., for: ...)
  - [ ] Register middleware: auth, verified, etc.
  - [ ] Add ->viteTheme('resources/css/filament/admin/theme.css') if custom
  - [ ] Test /admin route loads dashboard
- [ ] Clean up deprecated Filament registrations .............................................. 8
  - [ ] Search codebase for FilamentServiceProvider
  - [ ] Remove from config/app.php providers array
  - [ ] Delete legacy config/filament.php if exists
  - [ ] Remove old bootFilament() / registerFilament() methods
  - [ ] Verify no old Filament facades used
- [ ] Run full test suite & fix initial breakage .............................................. 55
  - [ ] Run php artisan test --parallel --coverage > tests-initial-report.txt
  - [ ] Group failures: unit, feature, browser
  - [ ] Fix unit tests first (fast feedback)
  - [ ] Fix Livewire v4 compatibility in test helpers
  - [ ] Mock missing services in failing tests
  - [ ] Create ticket for each major failure group
  - [ ] Re-run suite after each batch of fixes
  - [ ] Aim for 50%+ passing before proceeding

**Phase 0 total estimate:** ~184

## Phase 1  Global Breaking Changes & Signatures (apply everywhere  high repetition)

- [ ] Standardize navigation icons across all resources & pages ............................... 21
  - [ ] Create icons/ directory with SVG files
  - [ ] Or use heroicons Blade: <x-heroicon-o-users />
  - [ ] Replace every $navigationIcon = 'heroicon-o-xxx'
  - [ ] Replace old font-awesome / custom icon strings
  - [ ] Add fallback icon for missing ones
  - [ ] Test nav renders without broken icons
- [ ] Fix all $navigationGroup type errors .................................................... 13
  - [ ] Search codebase: \$navigationGroup
  - [ ] Change to protected static ?string $navigationGroup = 'Shop';
  - [ ] Use translatable strings: __('filament.groups.shop')
  - [ ] Consider enum: NavigationGroup::Shop->value
  - [ ] Verify no array or object assigned
- [ ] Update base Resource imports & class structure ......................................... 13
  - [ ] Replace use Filament\Resources\Resource; with v5 version
  - [ ] Remove old HasRelationManagers trait if present
  - [ ] Verify protected static ?string $model = Model::class;
  - [ ] Check getNavigationSort(), getNavigationBadge()
  - [ ] Remove old ->navigation() method overrides
- [ ] Refactor all form & table method signatures ............................................ 21
  - [ ] Search: function form( ? update to Form $form): Form
  - [ ] Replace ->form([ ... ]) with ->schema([ ... ])
  - [ ] Search: function table( ? update to Table $table): Table
  - [ ] Fix ->columns([...]) / ->filters([...]) placement
  - [ ] Audit every Resource file
- [ ] Modernize Action usage (table / form / modal / standalone) ............................. 21
  - [ ] Replace use Filament\Actions\Action; everywhere
  - [ ] Update ->action(fn($record) => $record->publish())
  - [ ] Chain ->requiresConfirmation() ->modalHeading()
  - [ ] Convert old ->icon('heroicon-o-check') to new syntax
  - [ ] Replace table bulk actions with BulkAction::make()
  - [ ] Test modal opens and closes correctly
- [ ] Upgrade custom page classes (List/Create/Edit/View) ................................... 34
  - [ ] Replace getFormSchema(): array ? form(Form $form): Form
  - [ ] Update mutateFormDataBeforeCreate(array $data): array
  - [ ] Fix afterCreate() / afterSave() hooks
  - [ ] Update getHeaderActions(): array
  - [ ] Replace ->record($this->record) access
  - [ ] Test create ? redirect to index
- [ ] Modernize all Relation Managers ......................................................... 34
  - [ ] Change base class to HasManyRelationManager / BelongsToManyRelationManager
  - [ ] Update table(Table $table): Table
  - [ ] Update form(Form $form): Form
  - [ ] Replace $this->ownerRecord ? $this->getOwnerRecord()
  - [ ] Add ->headerActions([CreateAction::make()])
  - [ ] Add ->bulkActions([DeleteBulkAction::make()])
  - [ ] Test attach/detach in belongsToMany
- [ ] Audit & upgrade Select/MultiSelect relationship handling .............................. 21
  - [ ] Replace ->relationship('category') ? ->relationship('category', 'name')
  - [ ] Add ->searchable() ->preload() where missing
  - [ ] Implement ->createOptionForm(fn() => [ ... ])
  - [ ] Fix ->getOptionLabelsUsing() ? ->getOptionLabelFromRecordUsing()
  - [ ] Test search returns correct results
- [ ] Review & standardize reactivity patterns ............................................... 21
  - [ ] Standardize slug generation: ->live(onBlur: true) ->afterStateUpdated(...)
  - [ ] Replace old updated('data.slug') methods
  - [ ] Use ->dependsOn(['type'], fn(Get $get, Set $set) => ...)
  - [ ] Test conditional visibility / required
  - [ ] Test debounce on search inputs

**Phase 1 total estimate:** ~199

## Phase 2  Per-Module Migration Tasks (extremely granular)

### Ai Module
- [ ] Convert AiSettingsPage to modern Filament Settings page .............................. 21
  - [ ] Create AiSettingsPage.php extending Page
  - [ ] Define form(Form $form): Form
  - [ ] Add Section for API Keys (OpenAI, Gemini, Ollama, Replicate)
  - [ ] Add Toggle for debug mode
  - [ ] Add Select for default driver
  - [ ] Implement save hook ? clear config cache
  - [ ] Add ->authorize() if role-based
- [ ] Fully migrate AgentChatResource & pages ............................................... 55
  - [ ] ListAgentChats: add TextFilter('search'), DateFilter('created_at')
  - [ ] CreateAgentChat: TextInput('title'), RichEditor('initial_prompt')
  - [ ] ViewAgentChat: custom Blade with message loop + streaming placeholder
  - [ ] EditAgentChat: limited fields (title, tags, status)
  - [ ] Add ->getNavigationBadge() for unread count
  - [ ] Add custom action: "Retry last tool call"
- [ ] Upgrade chat-related Livewire components .............................................. 34
  - [ ] Fix message append without full refresh
  - [ ] Implement streaming response placeholder
  - [ ] Add file upload field + preview
  - [ ] Auto-scroll to bottom on new message
  - [ ] Handle rate-limit / error messages
  - [ ] Test offline ? reconnect behavior
- [ ] Test & stabilize tool calling pipeline ................................................. 34
  - [ ] Create unit test per tool (mock HTTP responses)
  - [ ] AmazonScraperTool: test product extraction
  - [ ] GoogleTrendsTool: test keyword ranking
  - [ ] ContentCreateTool: test post/product creation
  - [ ] RagSearchTool: test document retrieval
  - [ ] Handle JSON parsing failures gracefully
- [ ] Create comprehensive PHPUnit tests .................................................... 34
  - [ ] test_list_agent_chats_shows_paginated_results()
  - [ ] test_create_chat_saves_initial_prompt()
  - [ ] test_view_chat_renders_message_history()
  - [ ] test_tool_call_returns_expected_output()
  - [ ] test_chat_with_file_upload_stores_media()

**Ai subtotal:** ~178

### Billing Module (critical business logic  highest granularity)

- [ ] Migrate BillingUserResource ........................................................... 21
  - [ ] List: add filters (active subs, trial status)
  - [ ] Edit: show subscription summary
  - [ ] RelationManager: HasMany(SubscriptionResource)
  - [ ] Add custom action: "Impersonate user"
- [ ] Migrate SubscriptionPlanResource & Groups ............................................. 34
  - [ ] Plan form: price, interval, trial_days, currency
  - [ ] Features Repeater: name, description, limit
  - [ ] Group form: name, description, sort_order
  - [ ] RelationManager: Plans in Group
  - [ ] RelationManager: Features in Group
- [ ] Migrate SubscriptionResource .......................................................... 21
  - [ ] List: filter active/canceled/trialing/expired
  - [ ] View: show invoices table, next billing date
  - [ ] Custom action: "Refund last payment" (mock)
- [ ] Convert custom pages (success/cancel/active) ......................................... 34
  - [ ] SubscriptionSuccessPage: show order summary, download invoice
  - [ ] SubscriptionCancelPage: confirmation + reason textarea
  - [ ] ActiveSubscriptionsPage: stats + table of subs
  - [ ] Handle Stripe session ID from query string
- [ ] Fix webhook handling ................................................................. 21
  - [ ] Update WebhookController signature for v5
  - [ ] Add signature verification middleware
  - [ ] Handle 'invoice.paid', 'customer.subscription.updated'
  - [ ] Log webhook payload to database
  - [ ] Add retry queue job on failure
- [ ] Migrate widgets ......................................................................... 13
  - [ ] LatestSubscriptionsWidget: table of 5 latest
  - [ ] StatsOverviewWidget: cards for MRR, active count, churn
  - [ ] Poll every 60s in dashboard
- [ ] PHPUnit coverage for billing ................................................................ 55
  - [ ] test_subscription_creation_with_trial()
  - [ ] test_subscription_cancellation_stops_billing()
  - [ ] test_trial_auto_activation_command_runs()
  - [ ] test_webhook_invoice_paid_updates_status()
  - [ ] test_user_cannot_access_billing_without_permission()

**Billing subtotal:** ~199

(Shop/Product/Order, Category, Cart, Backup, Blog, Captcha, Accordion, etc. would follow the same level of micro-sub-tasks  total Phase 2 now ~700900 with this detail)

## Phase 3  Testing Strategy (PHPUnit  very granular)

- [ ] Create base Filament test case ........................................................ 8
  - [ ] Abstract class FilamentResourceTestCase extends TestCase
  - [ ] protected function actingAsAdmin()
  - [ ] protected function getResourceClass(): string
  - [ ] Common assertions: assertSee, assertDontSee, assertDatabaseHas
- [ ] For every Resource: create dedicated test file ........................................ 8 per resource
  - [ ] test_index_page_loads_without_errors()
  - [ ] test_index_page_shows_all_records()
  - [ ] test_index_page_supports_pagination()
  - [ ] test_index_page_supports_search()
  - [ ] test_create_page_renders_form()
  - [ ] test_create_page_validates_required_fields()
  - [ ] test_create_page_saves_new_record()
  - [ ] test_edit_page_pre_fills_form_data()
  - [ ] test_edit_page_updates_record()
  - [ ] test_delete_action_removes_record()
- [ ] Table-specific tests ..................................................................... 8 per complex table
  - [ ] test_sorting_by_column_changes_order()
  - [ ] test_filter_by_boolean_field()
  - [ ] test_filter_by_select_relationship()
  - [ ] test_bulk_delete_removes_selected_records()
  - [ ] test_export_bulk_action_generates_file()
- [ ] Form reactivity & conditional tests ................................................... 13 per complex form
  - [ ] test_slug_field_updates_on_title_change()
  - [ ] test_paid_fields_hidden_when_type_free()
  - [ ] test_dependent_select_reloads_options()
  - [ ] test_toggle_makes_field_required()
- [ ] File upload tests ....................................................................... 21 per feature
  - [ ] test_single_image_upload_stores_file()
  - [ ] test_multiple_files_uploaded_correctly()
  - [ ] test_image_preview_shown_after_upload()
  - [ ] test_upload_validation_enforces_mime_types()
  - [ ] test_upload_to_s3_disk_works()
- [ ] Authorization & policy tests ......................................................... 13 per resource
  - [ ] test_non_admin_cannot_access_resource()
  - [ ] test_user_sees_only_own_team_records()
  - [ ] test_canAccessPanel_returns_false_for_guest()
- [ ] Rewrite critical legacy Dusk flows .................................................... 55
  - [ ] test_full_shop_checkout_flow_with_bank_transfer()
  - [ ] test_checkout_with_paypal_redirects_correctly()
  - [ ] test_admin_dashboard_loads_all_widgets()
  - [ ] test_xss_payloads_not_executed_in_inputs()

**Phase 3 total estimate:** ~350450

## Phase 4  Polish & Phase 5  Final Validation

- [ ] Custom icons & navigation polish ...................................................... 21
  - [ ] Create central icons.svg sprite
  - [ ] Replace all string icons with <svg> or Blade
  - [ ] Add ->navigationBadge() for counts (unread chats, pending orders)
  - [ ] Test nav collapses correctly on mobile
- [ ] Dark mode & responsive consistency .................................................... 21
  - [ ] Test every major page in dark mode
  - [ ] Fix color contrast issues (text on cards)
  - [ ] Verify tables / forms readable on small screens
  - [ ] Test sidebar toggle on tablet
- [ ] Performance optimizations ............................................................ 21
  - [ ] Add ->lazy() to non-critical widgets
  - [ ] Use ->with(['relation']) in table queries
  - [ ] Cache settings pages with ->cache()
  - [ ] Profile slow queries with Laravel Debugbar
- [ ] Full regression & staging deploy ..................................................... 55
  - [ ] Admin: CRUD on all resources
  - [ ] Frontend: add to cart ? checkout ? payment
  - [ ] AI: create chat ? run tools ? get response
  - [ ] Billing: subscribe ? webhook ? cancel
  - [ ] Deploy to staging ? run smoke tests
  - [ ] Monitor logs for 24h after deploy

