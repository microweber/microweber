# `Newsletter` module

> **Slug:** `newsletter`
> **Tier:** 1
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

### `newsletter_subscribers` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `email` | `string` | nullable |
  | `confirmation_code` | `string` | nullable |
  | `is_subscribed` | `boolean` | nullable, has-default |
  | `timestamps` | `timestamps` | — |
  | `status` | `string` | has-default |
  | `subscribed_at` | `timestamp` | nullable |
  | `unsubscribed_at` | `timestamp` | nullable |
  | `list_id` | `foreignId` | nullable, foreign-key |
  | `confirmation_code` | `string` | nullable |
  | `(unnamed)` | `dropColumn` | — |
  | `(unnamed)` | `dropForeign` | — |
  | `subscriber_email_unique` | `dropUnique` | — |

### `newsletter_templates` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `title` | `string` | nullable |
  | `text` | `longText` | nullable |
  | `json` | `longText` | nullable |
  | `timestamps` | `timestamps` | — |

### `newsletter_sender_accounts` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `from_name` | `string` | nullable |
  | `from_email` | `string` | nullable |
  | `reply_email` | `string` | nullable |
  | `account_type` | `string` | nullable |
  | `smtp_username` | `string` | nullable |
  | `smtp_password` | `string` | nullable |
  | `smtp_host` | `string` | nullable |
  | `smtp_port` | `string` | nullable |
  | `mailchimp_secret` | `string` | nullable |
  | `mailgun_domain` | `string` | nullable |
  | `mailgun_secret` | `string` | nullable |
  | `mandrill_secret` | `string` | nullable |
  | `sparkpost_secret` | `string` | nullable |
  | `amazon_ses_key` | `string` | nullable |
  | `amazon_ses_secret` | `string` | nullable |
  | `amazon_ses_region` | `string` | nullable |
  | `account_pass` | `string` | nullable |
  | `is_active` | `boolean` | nullable, has-default |
  | `timestamps` | `timestamps` | — |

### `newsletter_lists` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `success_email_template_id` | `integer` | nullable |
  | `unsubscription_email_template_id` | `integer` | nullable |
  | `confirmation_email_template_id` | `integer` | nullable |
  | `success_sender_account_id` | `integer` | nullable |
  | `unsubscription_sender_account_id` | `integer` | nullable |
  | `confirmation_sender_account_id` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |
  | `description` | `text` | nullable |
  | `is_public` | `boolean` | has-default |
  | `(unnamed)` | `dropColumn` | — |

### `newsletter_subscribers_lists` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `subscriber_id` | `integer` | nullable |
  | `list_id` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |
  | `subscriber_id` | `index` | — |
  | `list_id` | `index` | — |
  | `subscriber_list_unique` | `dropUnique` | — |

### `newsletter_campaigns` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `subject` | `string` | nullable |
  | `recipients_from` | `string` | nullable |
  | `delivery_type` | `string` | nullable |
  | `from_name` | `string` | nullable |
  | `email_template_id` | `integer` | nullable |
  | `list_id` | `integer` | nullable |
  | `sender_account_id` | `integer` | nullable |
  | `sending_limit_per_day` | `integer` | nullable |
  | `is_scheduled` | `boolean` | nullable, has-default |
  | `scheduled_at` | `dateTime` | nullable |
  | `scheduled_timezone` | `string` | nullable |
  | `is_done` | `boolean` | nullable, has-default |
  | `status` | `string` | nullable |
  | `status_log` | `text` | nullable |
  | `email_content_type` | `string` | nullable |
  | `email_content_html` | `longText` | nullable |
  | `email_attached_files` | `text` | nullable |
  | `enable_email_attachments` | `boolean` | nullable, has-default |
  | `delay_between_sending_emails` | `integer` | nullable |
  | `jobs_batch_id` | `string` | nullable |
  | `jobs_progress` | `integer` | nullable |
  | `total_jobs` | `integer` | nullable |
  | `completed_jobs` | `integer` | nullable |
  | `timestamps` | `timestamps` | — |
  | `campaign_type` | `string` | has-default |
  | `trigger_event` | `string` | nullable |
  | `delay_minutes` | `integer` | nullable, has-default |
  | `trigger_conditions` | `json` | nullable |
  | `is_active` | `boolean` | has-default |
  | `(unnamed)` | `dropColumn` | — |
  | `content` | `text` | nullable |
  | `sent_at` | `timestamp` | nullable |
  | `(unnamed)` | `dropColumn` | — |

### `newsletter_campaigns_send_log` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `campaign_id` | `integer` | nullable |
  | `subscriber_id` | `integer` | nullable |
  | `is_sent` | `boolean` | nullable, has-default |
  | `timestamps` | `timestamps` | — |
  | `campaign_id` | `index` | — |
  | `subscriber_id` | `index` | — |

### `newsletter_campaigns_pixel` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `campaign_id` | `integer` | nullable |
  | `email` | `string` | nullable |
  | `ip` | `string` | nullable |
  | `user_agent` | `string` | nullable |
  | `timestamps` | `timestamps` | — |
  | `campaign_id` | `index` | — |
  | `email` | `index` | — |

### `newsletter_campaigns_clicked_link` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `campaign_id` | `integer` | nullable |
  | `email` | `string` | nullable |
  | `ip` | `string` | nullable |
  | `user_agent` | `string` | nullable |
  | `link` | `text` | nullable |
  | `timestamps` | `timestamps` | — |

### `newsletter_automation_queue` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `campaign_id` | `foreignId` | foreign-key |
  | `subscriber_id` | `foreignId` | nullable, foreign-key |
  | `email` | `string` | — |
  | `trigger_event` | `string` | — |
  | `event_data` | `json` | nullable |
  | `scheduled_at` | `timestamp` | — |
  | `sent_at` | `timestamp` | nullable |
  | `status` | `string` | has-default |
  | `error_message` | `text` | nullable |
  | `timestamps` | `timestamps` | — |
  | `email` | `index` | — |

### `workflows` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | — |
  | `description` | `text` | nullable |
  | `trigger_type` | `string` | — |
  | `trigger_event` | `string` | nullable |
  | `trigger_conditions` | `json` | nullable |
  | `is_active` | `boolean` | has-default |
  | `last_triggered_at` | `timestamp` | nullable |
  | `execution_count` | `integer` | has-default |
  | `success_count` | `integer` | has-default |
  | `failure_count` | `integer` | has-default |
  | `timestamps` | `timestamps` | — |
  | `trigger_type` | `index` | — |
  | `trigger_event` | `index` | — |
  | `is_active` | `index` | — |

### `workflow_nodes` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `workflow_id` | `foreignId` | foreign-key |
  | `node_id` | `string` | unique |
  | `node_type` | `string` | — |
  | `node_key` | `string` | — |
  | `name` | `string` | — |
  | `description` | `text` | nullable |
  | `config` | `json` | — |
  | `position_x` | `integer` | has-default |
  | `position_y` | `integer` | has-default |
  | `connections` | `json` | nullable |
  | `sort_order` | `integer` | has-default |
  | `timestamps` | `timestamps` | — |
  | `node_id` | `index` | — |

### `workflow_executions` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `workflow_id` | `foreignId` | foreign-key |
  | `execution_key` | `string` | unique |
  | `status` | `string` | — |
  | `trigger_source` | `string` | — |
  | `trigger_data` | `json` | — |
  | `started_at` | `timestamp` | nullable |
  | `completed_at` | `timestamp` | nullable |
  | `current_step` | `integer` | has-default |
  | `total_steps` | `integer` | has-default |
  | `execution_log` | `json` | nullable |
  | `error_message` | `text` | nullable |
  | `user_id` | `unsignedInteger` | nullable |
  | `user_id` | `foreign` | — |
  | `timestamps` | `timestamps` | — |
  | `execution_key` | `index` | — |
  | `status` | `index` | — |
  | `started_at` | `index` | — |

### `workflow_execution_steps` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `execution_id` | `foreignId` | foreign-key |
  | `node_id` | `foreignId` | foreign-key |
  | `status` | `string` | — |
  | `step_number` | `integer` | — |
  | `input_data` | `json` | nullable |
  | `output_data` | `json` | nullable |
  | `started_at` | `timestamp` | nullable |
  | `completed_at` | `timestamp` | nullable |
  | `error_message` | `text` | nullable |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Newsletter\Models\NewsletterAutomationQueue`

Source: `Models/NewsletterAutomationQueue.php`. 

**Casts:**

  - `event_data` → `array`
  - `scheduled_at` → `datetime`
  - `sent_at` → `datetime`

### `Modules\Newsletter\Models\NewsletterCampaign`

Source: `Models/NewsletterCampaign.php`. 

### `Modules\Newsletter\Models\NewsletterCampaignClickedLink`

Source: `Models/NewsletterCampaignClickedLink.php`. 

### `Modules\Newsletter\Models\NewsletterCampaignPixel`

Source: `Models/NewsletterCampaignPixel.php`. 

### `Modules\Newsletter\Models\NewsletterCampaignsSendLog`

Source: `Models/NewsletterCampaignsSendLog.php`. 

**Fillable:** `campaign_id`, `subscriber_id`, `is_sent`

### `Modules\Newsletter\Models\NewsletterList`

Source: `Models/NewsletterList.php`. 

### `Modules\Newsletter\Models\NewsletterSenderAccount`

Source: `Models/NewsletterSenderAccount.php`. Table: `newsletter_sender_accounts`. 

### `Modules\Newsletter\Models\NewsletterSubscriber`

Source: `Models/NewsletterSubscriber.php`. Table: `newsletter_subscribers`. 

**Fillable:** `email`, `name`, `status`, `subscribed_at`, `unsubscribed_at`, `is_subscribed`

### `Modules\Newsletter\Models\NewsletterSubscriberList`

Source: `Models/NewsletterSubscriberList.php`. Table: `newsletter_subscribers_lists`. 

**Fillable:** `subscriber_id`, `list_id`

### `Modules\Newsletter\Models\NewsletterTemplate`

Source: `Models/NewsletterTemplate.php`. Table: `newsletter_templates`. 

**Fillable:** `title`, `text`, `json`

### `Modules\Newsletter\Models\Workflow`

Source: `Models/Workflow.php`. 

### `Modules\Newsletter\Models\WorkflowExecution`

Source: `Models/WorkflowExecution.php`. 

### `Modules\Newsletter\Models\WorkflowExecutionStep`

Source: `Models/WorkflowExecutionStep.php`. 

### `Modules\Newsletter\Models\WorkflowNode`

Source: `Models/WorkflowNode.php`. 

## API endpoints

### `routes/admin.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/export/subscribers` | `NewsletterSubscriberExportController::export` |
  | `GET` | `/export/lists` | `NewsletterListExportController::export` |
  | `GET` | `/export/campaigns` | `NewsletterCampaignExportController::export` |

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `NewsletterApiController::index` |
  | `POST` | `/` | `NewsletterApiController::store` |
  | `POST` | `/unsubscribe` | `NewsletterApiController::unsubscribe` |
  | `GET` | `/{id}` | `NewsletterApiController::show` |
  | `PUT` | `/{id}` | `NewsletterApiController::update` |
  | `PATCH` | `/{id}` | `NewsletterApiController::update` |
  | `DELETE` | `/{id}` | `NewsletterApiController::destroy` |

## Controllers

### `Modules\Newsletter\Http\Controllers\Admin\AdminController`

Source: `Http/Controllers/Admin/AdminController.php`.

  - `index(Request $request)`
  - `subscribers(Request $request)`
  - `campaigns(Request $request)`
  - `lists(Request $request)`
  - `senderAccounts(Request $request)`
  - `templates(Request $request)`
  - `templatesEdit(Request $request, $templateId)`
  - `settings(Request $request)`

### `Modules\Newsletter\Http\Controllers\Admin\NewsletterCampaignExportController`

Source: `Http/Controllers/Admin/NewsletterCampaignExportController.php`.

  - `export(Request $request): StreamedResponse`

### `Modules\Newsletter\Http\Controllers\Admin\NewsletterListExportController`

Source: `Http/Controllers/Admin/NewsletterListExportController.php`.

  - `export(Request $request): StreamedResponse`

### `Modules\Newsletter\Http\Controllers\Admin\NewsletterSenderAccountController`

Source: `Http/Controllers/Admin/NewsletterSenderAccountController.php`.

  - `save(Request $request)`

### `Modules\Newsletter\Http\Controllers\Admin\NewsletterSubscriberExportController`

Source: `Http/Controllers/Admin/NewsletterSubscriberExportController.php`.

  - `export(Request $request)`

### `Modules\Newsletter\Http\Controllers\Admin\NewsletterUploadSubscribersListController`

Source: `Http/Controllers/Admin/NewsletterUploadSubscribersListController.php`.

  - `getUploadPath()`

### `Modules\Newsletter\Http\Controllers\Api\NewsletterApiController`

Source: `Http/Controllers/Api/NewsletterApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`
  - `unsubscribe(Request $request): JsonResponse`

## Service classes

### `Modules\Newsletter\Services\AbandonedCartService`

Source: `Services/AbandonedCartService.php`.

  - `findAbandonedCarts(?int $delayMinutes = null): array`
  - `processAbandonedCarts(?int $delayMinutes = null): array`
  - `markCartAsRecovered(string $sessionId): void`
  - `setAbandonedDelay(int $minutes): self`
  - `getStatistics(): array`

### `Modules\Newsletter\Services\CampaignAutomationService`

Source: `Services/CampaignAutomationService.php`.

  - `trigger(string $event, array $data): array`
  - `cancelPendingEmails(string $email, ?string $event = null, ?int $campaignId = null): int`
  - `triggerAbandonedCart(string $email, Cart $cart, array $additionalData = []): array`
  - `triggerOrderPlaced(Order $order): array`
  - `triggerOrderPaid(Order $order): array`

### `Modules\Newsletter\Services\ImportSubscribersFileReader`

Source: `Services/ImportSubscribersFileReader.php`.

  - `getImportTempPath()`
  - `readContentFromFile(string $filename, $fileType = false)`

### `Modules\Newsletter\Services\WorkflowEngine`

Source: `Services/WorkflowEngine.php`.

  - `start(Workflow $workflow, array $triggerData, string $source = WorkflowExecution::SOURCE_EVENT): WorkflowExecution`
  - `execute(WorkflowExecution $execution): void`
  - `triggerByEvent(string $event, array $data): array`
  - `getStatistics(int $workflowId): array`

## Events

  - `Modules\Newsletter\Listeners\NewsletterAutomationSubscriber`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Newsletter\Filament\Admin\Pages\Campaigns` | — | — |
  | `Modules\Newsletter\Filament\Admin\Pages\CreateCampaign` | — | — |
  | `Modules\Newsletter\Filament\Admin\Pages\EditCampaign` | — | — |
  | `Modules\Newsletter\Filament\Admin\Pages\Homepage` | Email Marketing | — |
  | `Modules\Newsletter\Filament\Admin\Pages\Lists` | — | — |
  | `Modules\Newsletter\Filament\Admin\Pages\ProcessCampaign` | — | — |
  | `Modules\Newsletter\Filament\Admin\Pages\SenderAccounts` | — | — |
  | `Modules\Newsletter\Filament\Admin\Pages\Subscribers` | — | — |
  | `Modules\Newsletter\Filament\Admin\Pages\TemplateEditor` | — | — |
  | `Modules\Newsletter\Filament\Admin\Pages\Templates` | — | — |
  | `Modules\Newsletter\Filament\Admin\Resources\CampaignResource` | Campaigns | Campaigns |
  | `Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\CreateCampaign` | — | — |
  | `Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\EditCampaign` | — | — |
  | `Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\ManageCampaigns` | — | — |
  | `Modules\Newsletter\Filament\Admin\Resources\ListResource` | Campaigns | — |
  | `Modules\Newsletter\Filament\Admin\Resources\ListResource\Pages\ManageLists` | — | — |
  | `Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource` | Settings | — |
  | `Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts` | — | — |
  | `Modules\Newsletter\Filament\Admin\Resources\SubscribersResource` | Subscribers | — |
  | `Modules\Newsletter\Filament\Admin\Resources\SubscribersResource\Pages\ManageSubscribers` | — | — |
  | `Modules\Newsletter\Filament\Admin\Resources\TemplatesResource` | Templates | — |
  | `Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages\ManageTemplates` | — | — |
  | `Modules\Newsletter\Filament\Admin\Resources\WorkflowResource` | Campaigns | Automation Workflows |
  | `Modules\Newsletter\Filament\Admin\Resources\WorkflowResource\Pages\CreateWorkflow` | — | — |
  | `Modules\Newsletter\Filament\Admin\Resources\WorkflowResource\Pages\EditWorkflow` | — | — |
  | `Modules\Newsletter\Filament\Admin\Resources\WorkflowResource\Pages\ManageWorkflows` | — | — |
  | `Modules\Newsletter\Filament\Components\SelectTemplate` | — | — |
  | `Modules\Newsletter\Filament\Exports\NewsletterCampaignExporter` | — | — |
  | `Modules\Newsletter\Filament\Exports\NewsletterListExporter` | — | — |
  | `Modules\Newsletter\Filament\Exports\NewsletterSubscriberExporter` | — | — |
  | `Modules\Newsletter\Filament\Imports\NewsletterSubscriberImporter` | — | — |
  | `Modules\Newsletter\Filament\NewsletterModuleSettings` | — | — |
  | `Modules\Newsletter\Filament\Widgets\CampaignsChart` | — | — |
  | `Modules\Newsletter\Filament\Widgets\MailsOverviewWidget` | — | — |
  | `Modules\Newsletter\Filament\Widgets\RecentCampaignsWidget` | — | — |
  | `Modules\Newsletter\Filament\Widgets\StatsOverviewWidget` | — | — |
  | `Modules\Newsletter\Filament\Widgets\SubscribersChart` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Newsletter/Tests`

### `Tests/Filament/NewsletterBreadcrumbsTest.php`

  - `create_campaign_page_shows_campaigns_breadcrumb`

### `Tests/Filament/NewsletterResourceTest.php`

  - `it_subscribers_resource_class_exists`
  - `it_sender_accounts_resource_class_exists`
  - `it_campaign_resource_has_model`

### `Tests/Unit/EmailProvidersTest.php`

  - `mailgun_provider_sets_config_and_does_not_send_mailable`
  - `mandrill_provider_sets_config_and_does_not_send_mailable`
  - `default_provider_send_throws_exception`

### `Tests/Unit/Filament/CampaignResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_create_page_validates_required_fields`
  - `it_table_has_required_columns`

### `Tests/Unit/Filament/ListResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_index_page_supports_search`
  - `it_create_action_saves_new_record`
  - `it_table_has_required_columns`
  - `it_bulk_delete_removes_records`

### `Tests/Unit/Filament/SenderAccountsResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_create_action_renders_form`
  - `it_can_create_php_mail_account`

### `Tests/Unit/Filament/SubscribersResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_index_page_supports_search`
  - `it_can_subscribe_to_lists`
  - `it_import_action_exists`

### `Tests/Unit/Filament/TemplatesResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_index_page_supports_search`
  - `it_can_sort_by_title`
  - `it_bulk_delete_removes_records`

### `Tests/Unit/NewsletterCampaignTest.php`

  - `it_can_mark_as_finished`

### `Tests/Unit/Workflow/WorkflowExecutionTest.php`

  - `test_can_create_execution`
  - `test_can_mark_execution_as_started`
  - `test_can_mark_execution_as_completed`
  - `test_can_mark_execution_as_failed`
  - `test_can_mark_execution_as_cancelled`
  - `test_can_update_execution_progress`
  - `test_can_check_execution_status`
  - `test_can_get_execution_duration`
  - `test_get_statistics_returns_correct_data`

### `Tests/Unit/Workflow/WorkflowModelTest.php`

  - `test_can_create_workflow`
  - `test_can_create_trigger_node`
  - `test_can_evaluate_trigger_conditions`
  - `test_can_evaluate_multiple_conditions`
  - `test_get_trigger_types_returns_array`
  - `test_get_trigger_events_returns_array`
  - `test_can_increment_execution_stats`
  - `test_can_get_active_workflows_by_trigger`

## Service providers

  - `Modules\Newsletter\Providers\NewsletterFilamentAdminPanelProvider`
  - `Modules\Newsletter\Providers\NewsletterServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
