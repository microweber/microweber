<?php

namespace Modules\Ai\Tests\Filament;

use Livewire\Livewire;
use Modules\Ai\Filament\Resources\AgentChatResource;
use Modules\Ai\Filament\Resources\AgentChatResource\Pages\ListAgentChats;
use Modules\Ai\Filament\Resources\McpClientResource;
use Modules\Ai\Filament\Resources\McpClientResource\Pages\ListMcpClients;
use Modules\Ai\Filament\Pages\AiSettingsPage;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class AiResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_can_render_agent_chats_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListAgentChats::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_mcp_clients_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListMcpClients::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_ai_settings_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AiSettingsPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_agent_chat_resource_has_model(): void
    {
        $this->assertNotNull(AgentChatResource::getModel());
    }

    #[Test]
    public function it_mcp_client_resource_has_model(): void
    {
        $this->assertNotNull(McpClientResource::getModel());
    }
}
