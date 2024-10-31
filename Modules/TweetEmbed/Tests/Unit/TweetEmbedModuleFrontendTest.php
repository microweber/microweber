<?php

namespace Modules\TweetEmbed\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\TweetEmbed\Microweber\TweetEmbedModule;
use Tests\TestCase;

class TweetEmbedModuleFrontendTest extends TestCase
{

    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-tweet-embed-id' . uniqid(),
            'twitter_url' => 'https://twitter.com/user/status/1234567890',
        ];
        $moduleId = $params['id'];
        $moduleType = 'tweet_embed';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        // Save options to the database
        foreach ($params as $key => $value) {
            ModuleOption::create([
                'option_group' => $params['id'],
                'module' => $moduleType,
                'option_key' => $key,
                'option_value' => $value,
            ]);
        }
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $tweetEmbedModule = new TweetEmbedModule($params);
        $viewData = $tweetEmbedModule->getViewData();

        $viewOutput = $tweetEmbedModule->render();

        $this->assertTrue(View::exists('modules.tweet_embed::templates.default'));
        $this->assertStringContainsString('1234567890', $viewOutput);
        $this->assertStringContainsString('tweet-embeded', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
