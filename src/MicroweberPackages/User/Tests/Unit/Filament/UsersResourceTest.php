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
        // UsersResource hides Faker/demo users (@example.com/.org/.net) from the
        // table via modifyQueryUsing, so the factory's default @example.com
        // emails would be filtered out. Create users with a non-example domain
        // and count against the SAME filter the resource applies, then clean up
        // (no RefreshDatabase in this suite) so the rows don't accumulate.
        $visibleQuery = fn () => User::where('email', 'NOT LIKE', '%@example.com')
            ->where('email', 'NOT LIKE', '%@example.org')
            ->where('email', 'NOT LIKE', '%@example.net');

        $existingCount = $visibleQuery()->count();

        $ids = [];
        for ($i = 0; $i < 3; $i++) {
            $ids[] = User::factory()->create([
                'email' => 'pmtest-' . uniqid() . '@usersresource-test.dev',
            ])->id;
        }

        try {
            Livewire::test(ListUsers::class)
                ->loadTable()
                ->assertCountTableRecords($existingCount + 3);
        } finally {
            User::whereIn('id', $ids)->forceDelete();
        }
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
        // UsersResource shows human-readable columns (email/username/name/…),
        // not the raw 'id' — so don't assert an 'id' column that the resource
        // deliberately omits.
        Livewire::test(ListUsers::class)
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
