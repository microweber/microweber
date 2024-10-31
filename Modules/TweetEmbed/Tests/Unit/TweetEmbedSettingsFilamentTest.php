<?php

namespace Modules\TweetEmbed\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\TweetEmbed\Filament\TweetEmbedModuleSettings;
use Tests\TestCase;

class TweetEmbedSettingsFilamentTest extends TestCase
{

    public function testTweetEmbedSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'tweet_embed';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        Livewire::test(TweetEmbedModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.twitter_url');

        $data = [
            'options.twitter_url' => 'https://twitter.com/user/status/1234567890',
        ];

        Livewire::test(TweetEmbedModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet([
                'options.twitter_url' => 'https://twitter.com/user/status/1234567890',
            ])
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'twitter_url', 'option_value' => 'https://twitter.com/user/status/1234567890']);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
