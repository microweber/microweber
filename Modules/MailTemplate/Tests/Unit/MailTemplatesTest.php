<?php

namespace Modules\MailTemplate\Tests\Unit;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use MicroweberPackages\Core\tests\TestCase;
use Modules\MailTemplate\Models\MailTemplate;
use Modules\MailTemplate\Services\MailTemplateService;

class MailTemplatesTest extends TestCase
{

    private MailTemplateService $service;

    public function setUp(): void
    {
        parent::setUp();
        MailTemplate::truncate();
        Config::set('mail.driver', 'array');
        Config::set('queue.driver', 'sync');
        Config::set('mail.transport', 'array');
        $this->service = app(MailTemplateService::class);
    }

    public function testMailTemplateTypes()
    {
        // Test getting template types from config
        $types = $this->service->getTemplateTypes();
        $this->assertIsArray($types);
        $this->assertNotEmpty($types);
        $typesKeys = array_keys($types);
        // Verify all configured template types are available
        $configuredTypes = array_keys(config('modules.mail_template.template_types'));
        foreach ($configuredTypes as $type) {
            $this->assertContains($type, $typesKeys);
        }
    }

    public function testDefaultEmailSettings()
    {
        // Test default from name and email
        $this->assertEquals(
            config('modules.mail_template.defaults.from_name'),
            $this->service->getDefaultFromName()
        );

        $this->assertEquals(
            config('modules.mail_template.defaults.from_email'),
            $this->service->getDefaultFromEmail()
        );
    }

    public function testTemplateVariables()
    {
        // Test variables for each template type
        $configuredTypes = array_keys(config('modules.mail_template.template_types'));

        foreach ($configuredTypes as $type) {
            $variables = $this->service->getAvailableVariables($type);
            $this->assertIsArray($variables);

            // Verify variables match configuration
            $configuredVariables = config("modules.mail_template.variables.{$type}", []);
            $this->assertEquals(
                array_keys($configuredVariables),
                array_keys($variables)
            );
        }
    }

    public function testTemplateCreationWithDefaults()
    {
        // Test creating template with default values
        $template = MailTemplate::create([
            'name' => 'Default Test Template',
            'type' => 'new_order',
            'from_name' => $this->service->getDefaultFromName(),
            'from_email' => $this->service->getDefaultFromEmail(),
            'subject' => 'Test Subject',
            'message' => 'Test Message',
            'is_active' => true
        ]);

        $this->assertEquals($this->service->getDefaultFromName(), $template->from_name);
        $this->assertEquals($this->service->getDefaultFromEmail(), $template->from_email);
    }

    public function testTemplateCreationAndRetrieval()
    {
        // Create a test template
        $template = MailTemplate::create([
            'name' => 'Test Order Template',
            'type' => 'new_order',
            'from_name' => 'Test Store',
            'from_email' => 'store@example.com',
            'subject' => 'New Order #{order_id}',
            'message' => 'Hello {customer_name}, your order #{order_id} has been received.',
            'is_active' => true
        ]);

        // Test retrieving template by type
        $retrieved = $this->service->getTemplateByType('new_order');
        $this->assertNotNull($retrieved);
        $this->assertEquals($template->id, $retrieved->id);
        $this->assertEquals('new_order', $retrieved->type);
    }

    public function testTemplateVariableParsing()
    {
        // Create a template with variables
        $template = MailTemplate::create([
            'name' => 'Variable Test',
            'type' => 'new_order',
            'from_name' => 'Test Store',
            'from_email' => 'store@example.com',
            'subject' => 'Order #{order_id}',
            'message' => 'Hello {customer_name}, your order #{order_id} total is ${order_amount}.',
            'is_active' => true
        ]);

        // Test parsing variables
        $parsed = $this->service->parseTemplate($template, [
            'customer_name' => 'John Doe',
            'order_id' => '12345',
            'order_amount' => '99.99'
        ]);

        $this->assertEquals(
            'Hello John Doe, your order #12345 total is $99.99.',
            $parsed
        );
    }

    public function testEmailSendingWithCc()
    {
        Mail::fake();

        $template = MailTemplate::create([
            'name' => 'Email Test',
            'type' => 'new_order',
            'from_name' => 'Test Store',
            'from_email' => 'store@example.com',
            'subject' => 'Test Email',
            'message' => 'Hello {name}',
            'copy_to' => 'cc@example.com',
            'is_active' => true
        ]);

        $this->service->send($template, 'customer@example.com', [
            'name' => 'John'
        ]);

        Mail::assertSent(function (\Illuminate\Mail\Mailable $mail) {


            return $mail->hasTo('customer@example.com') &&
                $mail->hasCc('cc@example.com');
        });
    }

    public function testFileTemplateManagement()
    {
        // Test getting template files
        $files = $this->service->getMailTemplateFiles();
        $this->assertIsArray($files);

        // If we have template files, test their structure
        if (!empty($files)) {
            $template = $files[0];
            $this->assertArrayHasKey('type', $template);
            $this->assertArrayHasKey('name', $template);
            $this->assertArrayHasKey('file', $template);
            $this->assertArrayHasKey('path', $template);
            $this->assertArrayHasKey('subject', $template);
        }
    }

    public function testHelperFunctions()
    {
        // Test mail_template_service helper
        $service = mail_template_service();
        $this->assertInstanceOf(MailTemplateService::class, $service);

        // Create a test template
        $template = MailTemplate::create([
            'name' => 'Helper Test',
            'type' => 'contact_form',
            'from_name' => 'Test Store',
            'from_email' => 'store@example.com',
            'subject' => 'Contact Form',
            'message' => 'New contact from {name}',
            'is_active' => true
        ]);

        // Test get_mail_template_by_type helper
        $retrieved = get_mail_template_by_type('contact_form');
        $this->assertNotNull($retrieved);
        $this->assertEquals($template->id, $retrieved->id);

        // Test get_mail_template_types helper
        $types = get_mail_template_types();
        $this->assertIsArray($types);
        $this->assertNotEmpty($types);

        // Test get_mail_template_variables helper
        $variables = get_mail_template_variables('contact_form');
        $this->assertIsArray($variables);
    }

}
