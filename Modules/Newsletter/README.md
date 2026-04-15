# Newsletter

Full-featured email newsletter system. Build subscriber lists, design email templates, compose campaigns, and automate sending with workflow support and multiple email provider backends.

## Key Features

- Subscriber management with list segmentation
- Campaign creation with template-based design
- Send logging with open/click tracking (pixel + link tracking)
- Multiple email provider backends (SMTP, Mailgun, Mailchimp, Amazon SES, Mandrill, SparkPost)
- Automation queue for scheduled/triggered sends
- Workflow engine with nodes, executions, and step tracking
- Abandoned cart email automation
- Subscriber import via Livewire component
- Unsubscribe page

## Email Providers

| Provider | Class |
|---|---|
| SMTP | `SMTPProvider` |
| PHP Mail | `PHPMailProvider` |
| Amazon SES | `AmazonSesProvider` |
| Mailgun | `MailgunProvider` |
| Mailchimp | `MailchimpProvider` |
| Mandrill | `MandrillProvider` |
| SparkPost | `SparkpostProvider` |
| Default (Laravel) | `DefaultProvider` |

## Key Classes

| Class | Purpose |
|---|---|
| `Models\NewsletterCampaign` | Campaign definition |
| `Models\NewsletterSubscriber` | Subscriber records |
| `Models\NewsletterList` | Subscriber list/segment |
| `Models\NewsletterTemplate` | Email template |
| `Models\NewsletterSenderAccount` | Sender identity |
| `Models\Workflow` / `WorkflowNode` | Automation workflow engine |
| `Models\NewsletterCampaignPixel` | Open tracking |
| `Models\NewsletterCampaignClickedLink` | Click tracking |

## Database Tables

- `newsletter_subscribers` / `newsletter_lists` / `newsletter_subscribers_lists` -- subscribers
- `newsletter_templates` -- email templates
- `newsletter_sender_accounts` -- sender identities
- `newsletter_campaigns` / `newsletter_campaigns_send_log` -- campaigns and send log
- `newsletter_campaigns_pixel` / `newsletter_campaigns_clicked_link` -- tracking
- `newsletter_automation_queue` -- automation queue
- `workflows` / `workflow_nodes` / `workflow_executions` / `workflow_execution_steps` -- workflow engine

## Artisan Commands

- `newsletter:process-campaigns` -- process pending campaign sends
- `newsletter:process-abandoned-carts` -- send abandoned cart emails
- `newsletter:process-automation-queue` -- process automation queue
- `newsletter:seed-demo-data` -- seed sample newsletter data

## Admin Panel (Filament)

Dedicated Filament admin panel (`admin-newsletter`) with campaign, subscriber, template, and automation management.

## Usage

```html
<module type="newsletter" />
```
