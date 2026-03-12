<?php

namespace MicroweberPackages\User\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\User\Filament\Resources\UsersResource;
use MicroweberPackages\User\Filament\Resources\UsersResource\Pages\ListUsers;
use MicroweberPackages\User\Models\User;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UsersResourceTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    protected function getResourceClass(): string
    {
        return UsersResource::class;
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListUsers::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $users = User::factory()->count(3)->create();

        Livewire::test(ListUsers::class)
            ->assertCanSeeTableRecords($users);
    }

    #[Test]
    public function it_index_page_supports_pagination(): void
    {
        User::factory()->count(15)->create();

        Livewire::test(ListUsers::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_supports_search(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        Livewire::test(ListUsers::class)
            ->searchTable('testuser')
            ->assertCanSeeTableRecords([$user]);
    }

    #[Test]
    public function it_create_page_renders_form(): void
    {
        Livewire::test(ListUsers::class)
            ->assertSuccessful()
            ->assertFormExists();
    }

    #[Test]
    public function it_create_page_validates_required_fields(): void
    {
        Livewire::test(ListUsers::class)
            ->fillForm([
                'email' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'email',
            ]);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        Livewire::test(ListUsers::class)
            ->fillForm([
                'username' => 'newuser',
                'email' => 'newuser@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'is_admin' => '0',
                'is_active' => '1',
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
        ]);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Original',
            'is_admin' => 0,
        ]);

        Livewire::test(ListUsers::class)
            ->fillForm([
                'first_name' => 'Updated',
                'is_admin' => '1',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Updated',
        ]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $user = User::factory()->create();

        Livewire::test(ListUsers::class)
            ->callTableAction('delete', $user);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListUsers::class)
            ->assertTableColumnExists('id')
            ->assertTableColumnExists('username')
            ->assertTableColumnExists('email')
            ->assertTableColumnExists('first_name')
            ->assertTableColumnExists('last_name');
    }

    #[Test]
    public function it_email_must_be_unique(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        Livewire::test(ListUsers::class)
            ->fillForm([
                'username' => 'newuser',
                'email' => 'existing@example.com',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'email',
            ]);
    }

    #[Test]
    public function it_password_is_hashed_on_save(): void
    {
        Livewire::test(ListUsers::class)
            ->fillForm([
                'username' => 'passwordtest',
                'email' => 'password@example.com',
                'password' => 'secret123',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $user = User::where('username', 'passwordtest')->first();
        $this->assertNotEquals('secret123', $user->password);
    }

    #[Test]
    public function it_global_search_returns_results(): void
    {
        $user = User::factory()->create([
            'username' => 'searchuser',
            'email' => 'search@example.com',
        ]);

        $results = UsersResource::getGlobalSearchResults('searchuser');
        $this->assertNotEmpty($results);
    }

    #[Test]
    public function it_bulk_delete_removes_records(): void
    {
        $users = User::factory()->count(3)->create();

        Livewire::test(ListUsers::class)
            ->selectTableRecords($users)
            ->callTableBulkAction('delete');

        foreach ($users as $user) {
            $this->assertDatabaseMissing('users', [
                'id' => $user->id,
            ]);
        }
    }
}
