<?php

namespace Modules\Newsletter\Tests\Filament;

use Livewire\Livewire;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource;
use Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource;
use Modules\Newsletter\Filament\Admin\Resources\WorkflowResource;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class NewsletterResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_campaign_resource_class_exists(): void
    {
        $this->assertTrue(class_exists(CampaignResource::class));
    }

    #[Test]
    public function it_subscribers_resource_class_exists(): void
    {
        $this->assertTrue(class_exists(SubscribersResource::class));
    }

    #[Test]
    public function it_templates_resource_class_exists(): void
    {
        $this->assertTrue(class_exists(TemplatesResource::class));
    }

    #[Test]
    public function it_sender_accounts_resource_class_exists(): void
    {
        $this->assertTrue(class_exists(SenderAccountsResource::class));
    }

    #[Test]
    public function it_workflow_resource_class_exists(): void
    {
        $this->assertTrue(class_exists(WorkflowResource::class));
    }

    #[Test]
    public function it_campaign_resource_has_model(): void
    {
        $this->assertNotNull(CampaignResource::getModel());
    }

    #[Test]
    public function it_subscribers_resource_has_model(): void
    {
        $this->assertNotNull(SubscribersResource::getModel());
    }
}
