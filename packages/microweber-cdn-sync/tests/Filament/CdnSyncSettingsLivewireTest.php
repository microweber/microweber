<?php

namespace MicroweberPackages\CdnSync\Tests\Filament;

use Livewire\Livewire;
use MicroweberPackages\CdnSync\Filament\Pages\CdnSyncSettings;
use MicroweberPackages\CdnSync\Tests\TestCase;

class CdnSyncSettingsLivewireTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Log in as admin user for Filament tests
        if (class_exists(\App\Models\User::class)) {
            $user = \App\Models\User::where('is_admin', 1)->first();
            if (!$user) {
                $user = \App\Models\User::create([
                    'username' => 'cdn_test_admin',
                    'email' => 'cdntest@test.com',
                    'password' => bcrypt('password'),
                    'is_admin' => 1,
                ]);
            }
            $this->actingAs($user);
        }
    }

    public function test_settings_page_renders_via_livewire(): void
    {
        $component = Livewire::test(CdnSyncSettings::class);
        $component->assertStatus(200);
    }

    public function test_settings_page_has_form_data(): void
    {
        // Clear any previously saved options
        if (function_exists('save_option')) {
            foreach (['enabled', 'key', 'secret', 'region', 'bucket', 'endpoint', 'url', 'use_path_style_endpoint', 'cdn_url', 'path_prefix'] as $k) {
                save_option('cdn_sync_' . $k, '', 'cdn_sync');
            }
        }

        $component = Livewire::test(CdnSyncSettings::class);
        // Form fills with defaults via mount()
        $component->assertSet('data.cdn_sync_region', 'us-east-1');
        $component->assertSet('data.cdn_sync_path_prefix', 'cdn-sync');
        $component->assertSet('data.cdn_sync_enabled', false);
    }

    public function test_save_action_works(): void
    {
        $component = Livewire::test(CdnSyncSettings::class);
        $component->set('data.cdn_sync_enabled', true);
        $component->set('data.cdn_sync_key', 'test-key-123');
        $component->set('data.cdn_sync_secret', 'test-secret-456');
        $component->set('data.cdn_sync_bucket', 'test-bucket');
        $component->set('data.cdn_sync_region', 'eu-west-1');
        $component->call('save');
        $component->assertHasNoErrors();
    }

    public function test_test_connection_when_not_configured(): void
    {
        $component = Livewire::test(CdnSyncSettings::class);
        $component->set('data.cdn_sync_key', '');
        $component->set('data.cdn_sync_secret', '');
        $component->set('data.cdn_sync_bucket', '');
        $component->call('testConnection');
        $component->assertHasNoErrors();
    }

    public function test_settings_page_renders_form_fields(): void
    {
        $component = Livewire::test(CdnSyncSettings::class);
        // The page should render without errors — the form contains our fields
        $component->assertStatus(200);
        $component->assertSee('CDN Sync Configuration');
        $component->assertSee('Access Key');
        $component->assertSee('Secret Key');
        $component->assertSee('Bucket');
        $component->assertSee('Region');
    }

    public function test_real_key_integration_if_set(): void
    {
        $key = env('CDN_SYNC_KEY');
        $secret = env('CDN_SYNC_SECRET');
        $bucket = env('CDN_SYNC_BUCKET');

        if (empty($key) || empty($secret) || empty($bucket)) {
            $this->markTestSkipped('CDN_SYNC_KEY, CDN_SYNC_SECRET, CDN_SYNC_BUCKET env vars required.');
        }

        $component = Livewire::test(CdnSyncSettings::class);
        $component->set('data.cdn_sync_enabled', true);
        $component->set('data.cdn_sync_key', $key);
        $component->set('data.cdn_sync_secret', $secret);
        $component->set('data.cdn_sync_bucket', $bucket);
        $component->set('data.cdn_sync_region', env('CDN_SYNC_REGION', 'us-east-1'));
        $component->set('data.cdn_sync_endpoint', env('CDN_SYNC_ENDPOINT', ''));
        $component->set('data.cdn_sync_use_path_style', (bool) env('CDN_SYNC_USE_PATH_STYLE', false));
        $component->call('save');
        $component->assertHasNoErrors();

        $component->call('testConnection');
        $component->assertHasNoErrors();
    }
}