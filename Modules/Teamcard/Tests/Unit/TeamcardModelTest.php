<?php

namespace Modules\Teamcard\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Modules\Teamcard\Models\Teamcard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamcardModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'name',
            'file',
            'bio',
            'role',
            'website',
            'position',
            'rel_type',
            'rel_id',
            'settings'
        ];
        
        $this->assertEquals($fillable, (new Teamcard())->getFillable());
    }

    #[Test]
    public function it_casts_settings_to_array()
    {
        $teamcard = Teamcard::create([
            'name' => 'Test Member',
            'settings' => ['key' => 'value']
        ]);
        
        $this->assertIsArray($teamcard->settings);
        $this->assertEquals('value', $teamcard->settings['key']);
    }

    #[Test]
    public function it_casts_file_urls()
    {
        $teamcard = Teamcard::create([
            'name' => 'Test Member',
            'file' => 'http://lorempixel.com/400/200/'
        ]);
        
        // Currently the cast doesn't transform the URL
        $this->assertEquals('http://lorempixel.com/400/200/', $teamcard->file);
    }

    #[Test]
    public function it_can_create_team_member()
    {
        $member = Teamcard::create([
            'name' => 'John Doe',
            'role' => 'Developer',
            'bio' => 'Test bio',
            'position' => 1
        ]);
        
        $this->assertDatabaseHas('teamcards', [
            'name' => 'John Doe',
            'role' => 'Developer'
        ]);
    }

    #[Test]
    public function it_can_update_team_member()
    {
        $member = Teamcard::create([
            'name' => 'John Doe',
            'role' => 'Developer'
        ]);
        
        $member->update(['role' => 'Senior Developer']);
        
        $this->assertDatabaseHas('teamcards', [
            'name' => 'John Doe',
            'role' => 'Senior Developer'
        ]);
    }

    #[Test]
    public function it_can_delete_team_member()
    {
        $member = Teamcard::create([
            'name' => 'John Doe',
            'role' => 'Developer'
        ]);
        
        $member->delete();
        
        $this->assertSoftDeleted($member);
    }
}