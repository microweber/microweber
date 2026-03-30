<?php

namespace Modules\Newsletter\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SenderAccountsResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('admin-newsletter');
        DB::table('newsletter_sender_accounts')->delete();
    }

    protected function getResourceClass(): string
    {
        return SenderAccountsResource::class;
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ManageSenderAccounts::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $accounts = NewsletterSenderAccount::factory()->count(3)->create();

        $test = Livewire::test(ManageSenderAccounts::class);
        $test->assertSuccessful();

        foreach ($accounts as $account) {
            $this->assertDatabaseHas('newsletter_sender_accounts', [
                'id' => $account->id,
            ]);
        }
    }

    #[Test]
    public function it_index_page_supports_pagination(): void
    {
        NewsletterSenderAccount::factory()->count(15)->create();

        Livewire::test(ManageSenderAccounts::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_create_action_renders_form(): void
    {
        Livewire::test(ManageSenderAccounts::class)
            ->assertSuccessful()
            ->assertFormExists();
    }

    #[Test]
    public function it_create_action_saves_smtp_account(): void
    {
        Livewire::test(ManageSenderAccounts::class)
            ->callAction('create', data: [
                'account_type' => 'smtp',
                'smtp_username' => 'test@example.com',
                'smtp_password' => 'password123',
                'smtp_host' => 'smtp.example.com',
                'smtp_port' => '587',
                'from_name' => 'Test Sender',
                'from_email' => 'from@example.com',
                'reply_email' => 'reply@example.com',
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('newsletter_sender_accounts', [
            'account_type' => 'smtp',
            'smtp_username' => 'test@example.com',
            'from_email' => 'from@example.com',
        ]);
    }

    #[Test]
    public function it_edit_action_updates_record(): void
    {
        $account = NewsletterSenderAccount::factory()->create([
            'account_type' => 'php_mail',
            'from_name' => 'Original Name',
        ]);

        Livewire::test(ManageSenderAccounts::class)
            ->callTableAction('edit', $account, data: [
                'from_name' => 'Updated Name',
            ])
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('newsletter_sender_accounts', [
            'id' => $account->id,
            'from_name' => 'Updated Name',
        ]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $account = NewsletterSenderAccount::factory()->create();

        Livewire::test(ManageSenderAccounts::class)
            ->callTableAction('delete', $account);

        $this->assertDatabaseMissing('newsletter_sender_accounts', [
            'id' => $account->id,
        ]);
    }

    #[Test]
    public function it_table_shows_account_type_icon(): void
    {
        NewsletterSenderAccount::factory()->create([
            'account_type' => 'smtp',
        ]);

        Livewire::test(ManageSenderAccounts::class)
            ->assertSuccessful()
            ->assertTableColumnExists('account_type');
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ManageSenderAccounts::class)
            ->assertTableColumnExists('account_type')
            ->assertTableColumnExists('from_name')
            ->assertTableColumnExists('from_email')
            ->assertTableColumnExists('reply_email');
    }

    #[Test]
    public function it_can_create_php_mail_account(): void
    {
        Livewire::test(ManageSenderAccounts::class)
            ->callAction('create', data: [
                'account_type' => 'php_mail',
                'from_name' => 'PHP Mail Sender',
                'from_email' => 'php@example.com',
                'reply_email' => 'reply@example.com',
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('newsletter_sender_accounts', [
            'account_type' => 'php_mail',
            'from_email' => 'php@example.com',
        ]);
    }

    #[Test]
    public function it_bulk_delete_removes_records(): void
    {
        $accounts = NewsletterSenderAccount::factory()->count(3)->create();

        Livewire::test(ManageSenderAccounts::class)
            ->callTableBulkAction('delete', $accounts);

        foreach ($accounts as $account) {
            $this->assertDatabaseMissing('newsletter_sender_accounts', [
                'id' => $account->id,
            ]);
        }
    }
}
