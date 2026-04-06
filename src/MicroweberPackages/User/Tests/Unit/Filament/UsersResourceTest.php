<?php

namespace MicroweberPackages\User\Tests\Unit\Filament;

use Livewire\Livewire;
use MicroweberPackages\User\Filament\Resources\UsersResource;
use MicroweberPackages\User\Filament\Resources\UsersResource\Pages\CreateUsers;
use MicroweberPackages\User\Filament\Resources\UsersResource\Pages\EditUsers;
use MicroweberPackages\User\Filament\Resources\UsersResource\Pages\ListUsers;
use MicroweberPackages\User\Models\User;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UsersResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
        // Clean up hardcoded test users that may persist between test runs
        User::where('email', 'searchable@example.com')->orWhere('username', 'searchableuser')->delete();
        User::where('email', 'search@example.com')->orWhere('username', 'searchuser')->delete();
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
        $existingCount = User::count();
        $users = User::factory()->count(3)->create();

        Livewire::test(ListUsers::class)
            ->loadTable()
            ->assertCountTableRecords($existingCount + 3);
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
            'username' => 'searchableuser',
            'email' => 'searchable@example.com',
        ]);

        Livewire::test(ListUsers::class)
            ->searchTable('searchableuser')
            ->assertSuccessful()
            ->assertSee('searchableuser');
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
            ->callTableBulkAction('delete', $users);

        foreach ($users as $user) {
            $this->assertDatabaseMissing('users', [
                'id' => $user->id,
            ]);
        }
    }

    #[Test]
    public function it_create_page_loads_without_errors(): void
    {
        Livewire::test(CreateUsers::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_edit_page_loads_without_errors(): void
    {
        $user = User::factory()->create();

        Livewire::test(EditUsers::class, [
            'record' => $user->getRouteKey(),
        ])
            ->assertSuccessful();
    }

    #[Test]
    public function it_admin_pages_return_no_500(): void
    {
        $response = $this->get('/admin/users');
        $this->assertNotEquals(500, $response->status());

        $response = $this->get('/admin/users/create');
        $this->assertNotEquals(500, $response->status());

        $user = User::factory()->create();
        $response = $this->get("/admin/users/{$user->id}/edit");
        $this->assertNotEquals(500, $response->status());
    }
}
