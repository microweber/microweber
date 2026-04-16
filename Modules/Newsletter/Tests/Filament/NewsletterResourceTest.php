<?php

namespace Modules\Newsletter\Tests\Filament;

use Livewire\Livewire;
use Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use Modules\Newsletter\Filament\Admin\Resources\SubscribersResource\Pages\ManageSubscribers;
use Modules\Newsletter\Filament\Admin\Resources\ListResource;
use Modules\Newsletter\Filament\Admin\Resources\ListResource\Pages\ManageLists;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class NewsletterResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return SubscribersResource::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('admin-newsletter');
    }

    #[Test]
    public function it_can_render_subscribers_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ManageSubscribers::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_lists_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ManageLists::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_sender_accounts_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ManageSenderAccounts::class)
            ->assertSuccessful();
    }
}
