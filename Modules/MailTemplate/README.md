# Mail Template Module

A Laravel module for managing email templates with Filament v3 admin interface.

## Features

- Complete mail template management system
- Modern admin interface using Filament v3
- Configurable template types and variables
- Easy integration with other modules
- Rich text editor for template content
- Variable substitution support
- Attachment support
- CC email functionality

## Installation

1. Run module migrations:
```bash
php artisan module:migrate MailTemplate
```

2. Publish module assets:
```bash
php artisan module:publish MailTemplate
```

## Admin Interface

Access the mail templates management at:
- **Templates List**: Admin > Settings > Mail Templates
- **Module Settings**: Admin > Settings > Mail Templates Settings

## Usage

### Helper Functions

```php
// Get mail template service
$service = mail_template_service();

// Get template by type
$template = get_mail_template_by_type('new_order');

// Send email using template
send_mail_template('new_order', 'customer@example.com', [
    'order_id' => $order->id,
    'customer_name' => $order->customer_name
]);

// Get available variables for template type
$variables = get_mail_template_variables('new_order');

// Get all template types
$types = get_mail_template_types();
```

### Service Methods

```php
use Modules\MailTemplate\Services\MailTemplateService;

public function example(MailTemplateService $service)
{
    // Get template
    $template = $service->getTemplateByType('new_order');
    
    // Parse template with variables
    $message = $service->parseTemplate($template, [
        'order_id' => '123',
        'customer_name' => 'John Doe'
    ]);
    
    // Send email with attachments
    $service->send($template, 'customer@example.com', [
        'order_id' => '123',
        'customer_name' => 'John Doe'
    ], [
        ['path' => 'path/to/file.pdf']
    ]);
}
```

## Available Template Types

- `new_order` - New Order Notification
- `new_comment` - New Comment Notification
- `forgot_password` - Password Reset
- `new_registration` - New User Registration
- `contact_form` - Contact Form Submission
- `newsletter_subscription` - Newsletter Subscription

## Template Variables

Each template type supports specific variables:

### New Order
- `{order_id}` - Order ID
- `{order_amount}` - Order Amount
- `{customer_name}` - Customer Name
- `{order_status}` - Order Status
- `{order_items}` - Order Items

### New Comment
- `{comment_author}` - Comment Author
- `{comment_content}` - Comment Content
- `{post_title}` - Post Title

### Forgot Password
- `{reset_link}` - Password Reset Link
- `{user_name}` - User Name

### New Registration
- `{user_name}` - User Name
- `{user_email}` - User Email
- `{verification_link}` - Email Verification Link

### Contact Form
- `{name}` - Sender Name
- `{email}` - Sender Email
- `{subject}` - Message Subject
- `{message}` - Message Content

### Newsletter Subscription
- `{subscriber_email}` - Subscriber Email
- `{confirmation_link}` - Confirmation Link

## Configuration

The module configuration can be published and modified:

```bash
php artisan vendor:publish --tag=config --provider="Modules\MailTemplate\Providers\MailTemplateServiceProvider"
```

Key configuration options:
- Default email settings
- Template types
- Available variables
- Module settings

## Views

Module views can be published and customized:

```bash
php artisan vendor:publish --tag=views --provider="Modules\MailTemplate\Providers\MailTemplateServiceProvider"
```

## License

This module is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
