<?php

namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Newsletter\Models\NewsletterTemplate;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsletterTemplateTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_newsletter_template()
    {
        $data = [
            'title' => 'Test Template',
            'text' => 'This is a test template.',
            'json' => json_encode(['key' => 'value']),
        ];

        $template = NewsletterTemplate::create($data);

        $this->assertDatabaseHas('newsletter_templates', [
            'id' => $template->id,
            'title' => 'Test Template',
            'text' => 'This is a test template.',
        ]);
    }

    #[Test]
    public function it_has_factory()
    {
        $template = NewsletterTemplate::factory()->create();
        $this->assertInstanceOf(NewsletterTemplate::class, $template);
        $this->assertDatabaseHas('newsletter_templates', [
            'id' => $template->id,
        ]);
    }
}