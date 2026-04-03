<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Actions\Action;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopAutoRespondEmailPage extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-paper-airplane';

    protected string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Auto Respond Email';

    protected static string $description = 'Configure your shop auto respond email settings';

    protected static string | \UnitEnum | null $navigationGroup = 'Email Settings';

    public array $optionGroups = [
        'orders',
        'shop'
    ];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('test_email')
                ->label('Send Test Email')
                ->icon('heroicon-o-envelope')
                ->form([
                    TextInput::make('test_email_to')
                        ->label('Send test email to')
                        ->email()
                        ->required()
                        ->placeholder('test@example.com')
                        ->helperText('Enter the email address where you want to send the test email'),
                ])
                ->action(function (array $data) {
                    try {
                        // Call the test email API
                        $response = checkout_confirm_email_test([
                            'to' => $data['test_email_to']
                        ]);

                        Notification::make()
                            ->title('Test email sent successfully')
                            ->success()
                            ->body('Test email has been sent to ' . $data['test_email_to'])
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Failed to send test email')
                            ->danger()
                            ->body('Error: ' . $e->getMessage())
                            ->send();
                    }
                })
                ->color('primary'), Action::make('create_order_template')
                ->label('Create Order Template')
                ->icon('heroicon-o-plus')
                ->form(function () {
                    try {
                        if (class_exists('\Modules\MailTemplate\Services\MailTemplateService')) {
                            $service = app(\Modules\MailTemplate\Services\MailTemplateService::class);
                            return $service->getTemplateFormSchema();
                        }
                    } catch (\Exception $e) {
                        // Fallback to basic form if service is not available
                    }

                    // Fallback basic form
                    return [
                        TextInput::make('name')
                            ->label('Template Name')
                            ->required()
                            ->placeholder('e.g., New Order Confirmation')
                            ->helperText('Enter a name for the new order email template'),

                        Select::make('type')
                            ->label('Template Type')
                            ->options([
                                'new_order' => 'New Order Confirmation',
                                'order_paid' => 'Order Payment Confirmation',
                                'order_shipped' => 'Order Shipped',
                                'order_delivered' => 'Order Delivered',
                            ])
                            ->default('new_order')
                            ->required()
                            ->helperText('Select the type of order email template'),

                        TextInput::make('subject')
                            ->label('Email Subject')
                            ->required()
                            ->placeholder('Order #{order_id} - Confirmation')
                            ->helperText('Email subject line (you can use variables)'),

                        \Filament\Forms\Components\RichEditor::make('message')
                            ->label('Email Content')
                            ->required()
                            ->placeholder('Enter the email content...')
                            ->helperText('Email body content (you can use variables like {order_id}, {first_name}, etc.)'),
                    ];
                })->action(function (array $data) {
                    try {
                        // Create new mail template with full form data
                        $templateData = [
                            'name' => $data['name'],
                            'type' => $data['type'] ?? 'new_order',
                            'from_name' => $data['from_name'] ?? get_option('email_from_name', 'email') ?? 'Your Store',
                            'from_email' => $data['from_email'] ?? get_option('email_from', 'email') ?? 'noreply@yourstore.com',
                            'copy_to' => $data['copy_to'] ?? null,
                            'subject' => $data['subject'] ?? 'Order #{order_id} - ' . $data['name'],
                            'message' => $data['message'] ?? $this->getDefaultTemplateContent($data['type'] ?? 'new_order'),
                            'is_active' => $data['is_active'] ?? true,
                        ];
                        if (class_exists('\Modules\MailTemplate\Models\MailTemplate')) {
                            $template = \Modules\MailTemplate\Models\MailTemplate::create($templateData);
                            Notification::make()
                                ->title('Email template created successfully')
                                ->success()
                                ->body('Template "' . $data['name'] . '" has been created and is ready to use')
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('edit_template')
                                        ->label('Edit Template')
                                        ->url(admin_url('mail-templates/' . $template->id . '/edit'))
                                        ->openUrlInNewTab(),
                                    \Filament\Notifications\Actions\Action::make('view_all')
                                        ->label('View All Templates')
                                        ->url(admin_url('mail-templates'))
                                        ->openUrlInNewTab(),
                                ])
                                ->send();
                        } else {
                            throw new \Exception('MailTemplate module is not available');
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Failed to create email template')
                            ->danger()
                            ->body('Error: ' . $e->getMessage())
                            ->send();
                    }
                })
                ->color('success'),

            Action::make('mail_templates')
                ->label('Manage Templates')
                ->icon('heroicon-o-document-text')
                ->url(admin_url('mail-templates'))
                ->openUrlInNewTab()
                ->color('info'),

            Action::make('email_settings')
                ->label('Email Settings')
                ->icon('heroicon-o-cog-6-tooth')
                ->url(admin_url('admin-email-page'))
                ->openUrlInNewTab()
                ->color('gray'),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('New Order Notifications')
                    ->icon('heroicon-m-bell-alert')
                    ->description('Configure email notifications for new orders')
                    ->schema([
                        Toggle::make('options.orders.order_email_enabled')
                            ->label('Send email to the customer when new order is received')
                            ->helperText(new HtmlString('Enable or disable email notifications for new orders<br><small class="text-muted">You must have a working email setup in order to send emails</small>'))
                            ->live(),

                        Grid::make(1)
                            ->schema([
                                Select::make('options.orders.send_email_on_new_order')
                                    ->label('Send email to')
                                    ->helperText('Choose who should receive the autorespond emails')
                                    ->options([
                                        '' => 'Default (Admins & Client)',
                                        'admins' => 'Only Admins',
                                        'client' => 'Only Client',
                                    ])
                                    ->default('')
                                    ->live(),

                                Radio::make('options.orders.order_email_send_when')
                                    ->label('Send email when')
                                    ->options([
                                        'order_received' => 'Order is received',
                                        'order_paid' => 'Order is paid',
                                        '' => 'Disable',
                                    ])
                                    ->default('order_received')
                                    ->inline()
                                    ->live(),
                            ])
                            ->visible(fn(Get $get) => $get('options.orders.order_email_enabled')),
                    ]),


                Section::make('Email Templates')
                    ->icon('heroicon-m-document-text')
                    ->description('Choose the email templates to send for different events')
                    ->schema([
                        Select::make('options.orders.new_order_mail_template')
                            ->label('New Order Email Template')
                            ->helperText('Select the email template to use for new order notifications')
                            ->options(function () {
                                $templates = [];
                                try {
                                    // Get mail templates for new_order type
                                    if (function_exists('get_mail_template_by_type')) {
                                        $allTemplates = \Modules\MailTemplate\Models\MailTemplate::where('type', 'new_order')
                                            ->where('is_active', true)
                                            ->get();

                                        foreach ($allTemplates as $template) {
                                            $templates[$template->id] = $template->name;
                                        }
                                    }
                                } catch (\Exception $e) {
                                    // Fallback if MailTemplate module is not available
                                }

                                if (empty($templates)) {
                                    $templates[''] = 'Default Template';
                                }

                                return $templates;
                            })
                            ->searchable()
                            ->live(),


                    ]),

                Section::make('Advanced Settings')
                    ->icon('heroicon-m-cog-6-tooth')
                    ->description('Additional configuration options')
                    ->collapsed()
                    ->schema([

                        \Filament\Forms\Components\Placeholder::make('variables_info')
                            ->label('Available Email Variables')
                            ->content(new HtmlString('
                                <div class="rounded-lg border  p-4">
                                    <h4 class="font-medium mb-2">Available Variables for Order Emails</h4>
                                    <div class="text-sm  space-y-1">
                                        <p><code class=" px-1 rounded">{order_id}</code> - Order ID</p>
                                        <p><code class=" px-1 rounded">{first_name}</code> - Customer first name</p>
                                        <p><code class=" px-1 rounded">{last_name}</code> - Customer last name</p>
                                        <p><code class=" px-1 rounded">{email}</code> - Customer email</p>
                                        <p><code class=" px-1 rounded">{phone}</code> - Customer phone</p>
                                        <p><code class=" px-1 rounded">{address}</code> - Customer address</p>
                                        <p><code class=" px-1 rounded">{city}</code> - Customer city</p>
                                        <p><code class=" px-1 rounded">{state}</code> - Customer state</p>
                                        <p><code class=" px-1 rounded">{country}</code> - Customer country</p>
                                        <p><code class=" px-1 rounded">{zip}</code> - Customer zip code</p>
                                        <p><code class=" px-1 rounded">{order_amount}</code> - Total order amount</p>
                                        <p><code class=" px-1 rounded">{cart_items}</code> - Order items table</p>
                                    </div>
                                </div>
                                ')),
                    ]),
            ]);
    }

    /**
     * Get default template content based on template type
     */
    private function getDefaultTemplateContent(string $type): string
    {
        $templates = [
            'new_order' => '
                <h1>Thank you for your order!</h1>
                <p>Hello {first_name} {last_name},</p>
                <p>We have received your order <strong>#{order_id}</strong> and are processing it now.</p>

                <h2>Order Details</h2>
                <p><strong>Order ID:</strong> {order_id}</p>
                <p><strong>Order Amount:</strong> {order_amount}</p>
                <p><strong>Customer Email:</strong> {email}</p>
                <p><strong>Phone:</strong> {phone}</p>

                <h3>Shipping Address</h3>
                <p>{address}<br>
                {city}, {state} {zip}<br>
                {country}</p>

                <h3>Order Items</h3>
                {cart_items}

                <p>We will send you another email when your order ships.</p>
                <p>Thank you for your business!</p>
            ',
            'order_paid' => '
                <h1>Payment Confirmed!</h1>
                <p>Hello {first_name} {last_name},</p>
                <p>We have received your payment for order <strong>#{order_id}</strong>.</p>

                <p><strong>Order Amount:</strong> {order_amount}</p>
                <p><strong>Payment Status:</strong> Confirmed</p>

                <p>Your order is now being processed and will be shipped soon.</p>
                <p>Thank you for your payment!</p>
            ',
            'order_shipped' => '
                <h1>Your order has been shipped!</h1>
                <p>Hello {first_name} {last_name},</p>
                <p>Great news! Your order <strong>#{order_id}</strong> has been shipped.</p>

                <p>You should receive your order within the next few business days.</p>
                <p>Thank you for your order!</p>
            ',
            'order_delivered' => '
                <h1>Order Delivered!</h1>
                <p>Hello {first_name} {last_name},</p>
                <p>Your order <strong>#{order_id}</strong> has been delivered.</p>

                <p>We hope you enjoy your purchase! If you have any questions or concerns, please don\'t hesitate to contact us.</p>
                <p>Thank you for choosing us!</p>
            ',
        ];

        return $templates[$type] ?? $templates['new_order'];
    }
}
