# `Newsletter` module

> **Slug:** `newsletter`
> **Tier:** 1
>
> Tier-1 module — owns its own data + exposes a public API.
>
> *(Auto-generated from filesystem survey on 2026-04-25;
> hand-edit to add operator-side context. The canonical
> shape lives in [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md);
> use `Modules/Settings/docs/README.md` as the
> hand-curated example.)*

## Domain

*Hand-edit this section to describe what the module does
operationally and which sibling modules it interacts
with.*

## Data model

Migrations under `Modules/Newsletter/database/migrations/`:

  - `database/migrations/2024_02_28_164053_create_newsletter_subscribers_table.php`
  - `database/migrations/2024_02_28_164106_create_newsletter_templates_table.php`
  - `database/migrations/2024_02_28_164145_create_newsletter_sender_accounts_table.php`
  - `database/migrations/2024_02_28_164159_create_newsletter_lists_table.php`
  - `database/migrations/2024_02_28_164214_create_newsletter_subscribers_lists_table.php`
  - `database/migrations/2024_02_28_164255_create_newsletter_campaigns_table.php`
  - `database/migrations/2024_02_28_164310_create_newsletter_campaigns_send_log_table.php`
  - `database/migrations/2024_02_28_164445_create_newsletter_campaigns_pixel_table.php`
  - `database/migrations/2024_02_28_164457_create_newsletter_campaigns_clicked_link_table.php`
  - `database/migrations/2025_03_22_100000_add_automation_fields_to_newsletter_campaigns.php`
  - `database/migrations/2025_03_22_100001_create_newsletter_automation_queue_table.php`
  - `database/migrations/2025_03_22_200000_create_workflows_table.php`
  - `database/migrations/2025_03_22_200001_create_workflow_nodes_table.php`
  - `database/migrations/2025_03_22_200002_create_workflow_executions_table.php`
  - `database/migrations/2025_03_22_200003_create_workflow_execution_steps_table.php`
  - `database/migrations/2025_04_04_100711_2025_04_04_100000_update_newsletter_lists_table.php`
  - `database/migrations/2025_04_04_100801_2025_04_04_100100_update_newsletter_subscribers_table.php`
  - `database/migrations/2025_04_04_100859_2025_04_04_100200_update_newsletter_campaigns_table.php`
  - `database/migrations/2025_04_04_100945_2025_04_04_100300_update_newsletter_subscribers_lists_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Newsletter\Models\NewsletterAutomationQueue` | `Models/NewsletterAutomationQueue.php` |
| `Modules\Newsletter\Models\NewsletterCampaign` | `Models/NewsletterCampaign.php` |
| `Modules\Newsletter\Models\NewsletterCampaignClickedLink` | `Models/NewsletterCampaignClickedLink.php` |
| `Modules\Newsletter\Models\NewsletterCampaignPixel` | `Models/NewsletterCampaignPixel.php` |
| `Modules\Newsletter\Models\NewsletterCampaignsSendLog` | `Models/NewsletterCampaignsSendLog.php` |
| `Modules\Newsletter\Models\NewsletterList` | `Models/NewsletterList.php` |
| `Modules\Newsletter\Models\NewsletterSenderAccount` | `Models/NewsletterSenderAccount.php` |
| `Modules\Newsletter\Models\NewsletterSubscriber` | `Models/NewsletterSubscriber.php` |
| `Modules\Newsletter\Models\NewsletterSubscriberList` | `Models/NewsletterSubscriberList.php` |
| `Modules\Newsletter\Models\NewsletterTemplate` | `Models/NewsletterTemplate.php` |
| `Modules\Newsletter\Models\Workflow` | `Models/Workflow.php` |
| `Modules\Newsletter\Models\WorkflowExecution` | `Models/WorkflowExecution.php` |
| `Modules\Newsletter\Models\WorkflowExecutionStep` | `Models/WorkflowExecutionStep.php` |
| `Modules\Newsletter\Models\WorkflowNode` | `Models/WorkflowNode.php` |

## API endpoints

Route files:

  - `routes/admin.php`
  - `routes/api.php`
  - `routes/web.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Newsletter\Http\Controllers\Admin\AdminController`
  - `Modules\Newsletter\Http\Controllers\Admin\NewsletterCampaignExportController`
  - `Modules\Newsletter\Http\Controllers\Admin\NewsletterListExportController`
  - `Modules\Newsletter\Http\Controllers\Admin\NewsletterSenderAccountController`
  - `Modules\Newsletter\Http\Controllers\Admin\NewsletterSubscriberExportController`
  - `Modules\Newsletter\Http\Controllers\Admin\NewsletterUploadSubscribersListController`
  - `Modules\Newsletter\Http\Controllers\Api\NewsletterApiController`

## Service classes

  - `Modules\Newsletter\Services\AbandonedCartService`
  - `Modules\Newsletter\Services\CampaignAutomationService`
  - `Modules\Newsletter\Services\ImportSubscribersFileReader`
  - `Modules\Newsletter\Services\WorkflowEngine`

## Events

  - `Modules\Newsletter\Listeners\NewsletterAutomationSubscriber`

## Filament admin

  - `Modules\Newsletter\Filament\Admin\Pages\Campaigns`
  - `Modules\Newsletter\Filament\Admin\Pages\CreateCampaign`
  - `Modules\Newsletter\Filament\Admin\Pages\EditCampaign`
  - `Modules\Newsletter\Filament\Admin\Pages\Homepage`
  - `Modules\Newsletter\Filament\Admin\Pages\Lists`
  - `Modules\Newsletter\Filament\Admin\Pages\ProcessCampaign`
  - `Modules\Newsletter\Filament\Admin\Pages\SenderAccounts`
  - `Modules\Newsletter\Filament\Admin\Pages\Subscribers`
  - `Modules\Newsletter\Filament\Admin\Pages\TemplateEditor`
  - `Modules\Newsletter\Filament\Admin\Pages\Templates`
  - `Modules\Newsletter\Filament\Admin\Resources\CampaignResource`
  - `Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\CreateCampaign`
  - `Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\EditCampaign`
  - `Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\ManageCampaigns`
  - `Modules\Newsletter\Filament\Admin\Resources\ListResource`
  - `Modules\Newsletter\Filament\Admin\Resources\ListResource\Pages\ManageLists`
  - `Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource`
  - `Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts`
  - `Modules\Newsletter\Filament\Admin\Resources\SubscribersResource`
  - `Modules\Newsletter\Filament\Admin\Resources\SubscribersResource\Pages\ManageSubscribers`
  - `Modules\Newsletter\Filament\Admin\Resources\TemplatesResource`
  - `Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages\ManageTemplates`
  - `Modules\Newsletter\Filament\Admin\Resources\WorkflowResource`
  - `Modules\Newsletter\Filament\Admin\Resources\WorkflowResource\Pages\CreateWorkflow`
  - `Modules\Newsletter\Filament\Admin\Resources\WorkflowResource\Pages\EditWorkflow`
  - `Modules\Newsletter\Filament\Admin\Resources\WorkflowResource\Pages\ManageWorkflows`
  - `Modules\Newsletter\Filament\Components\SelectTemplate`
  - `Modules\Newsletter\Filament\Exports\NewsletterCampaignExporter`
  - `Modules\Newsletter\Filament\Exports\NewsletterListExporter`
  - `Modules\Newsletter\Filament\Exports\NewsletterSubscriberExporter`
  - `Modules\Newsletter\Filament\Imports\NewsletterSubscriberImporter`
  - `Modules\Newsletter\Filament\NewsletterModuleSettings`
  - `Modules\Newsletter\Filament\Widgets\CampaignsChart`
  - `Modules\Newsletter\Filament\Widgets\MailsOverviewWidget`
  - `Modules\Newsletter\Filament\Widgets\RecentCampaignsWidget`
  - `Modules\Newsletter\Filament\Widgets\StatsOverviewWidget`
  - `Modules\Newsletter\Filament\Widgets\SubscribersChart`

## Tests

Run: `php vendor/bin/phpunit Modules/Newsletter/Tests`

Test files:

  - `Tests/Filament/NewsletterBreadcrumbsTest.php`
  - `Tests/Filament/NewsletterCampaignResourceTest.php`
  - `Tests/Filament/NewsletterResourceTest.php`
  - `Tests/Filament/WorkflowBuilderTest.php`
  - `Tests/NewsletterTestCase.php`
  - `Tests/Unit/AutomatedEmailCampaignTest.php`
  - `Tests/Unit/CampaignSendingTest.php`
  - `Tests/Unit/EmailProvidersTest.php`
  - `Tests/Unit/Filament/CampaignResourceTest.php`
  - `Tests/Unit/Filament/ListResourceTest.php`
  - `Tests/Unit/Filament/SenderAccountsResourceTest.php`
  - `Tests/Unit/Filament/SubscribersResourceTest.php`
  - `Tests/Unit/Filament/TemplatesResourceTest.php`
  - `Tests/Unit/NewsletterCampaignClickedLinkTest.php`
  - `Tests/Unit/NewsletterCampaignPixelTest.php`
  - `Tests/Unit/NewsletterCampaignTest.php`
  - `Tests/Unit/NewsletterCampaignsSendLogTest.php`
  - `Tests/Unit/NewsletterListTest.php`
  - `Tests/Unit/NewsletterSenderAccountTest.php`
  - `Tests/Unit/NewsletterSubscriberImportExportTest.php`
  - `Tests/Unit/NewsletterSubscriberListTest.php`
  - `Tests/Unit/NewsletterSubscriberTest.php`
  - `Tests/Unit/NewsletterTemplateTest.php`
  - `Tests/Unit/ProcessAutomationQueueCommandTest.php`
  - `Tests/Unit/ProcessCampaignSubscriberTest.php`
  - `Tests/Unit/ProcessCampaignsCommandTest.php`
  - `Tests/Unit/SeedDemoDataCommandTest.php`
  - `Tests/Unit/UnsubscribePageTest.php`
  - `Tests/Unit/Workflow/WorkflowExecutionTest.php`
  - `Tests/Unit/Workflow/WorkflowModelTest.php`

## Service providers

  - `Modules\Newsletter\Providers\NewsletterFilamentAdminPanelProvider`
  - `Modules\Newsletter\Providers\NewsletterServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
