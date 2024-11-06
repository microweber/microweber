<?php

namespace Modules\TweetEmbed\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\TweetEmbed\Filament\TweetEmbedModuleSettings;

class TweetEmbedModule extends BaseModule
{
    public static string $name = 'TweetEmbed';
    public static string $module = 'tweet_embed';
    public static string $icon = 'modules.tweet_embed-icon';
    public static string $categories = 'social';
    public static int $position = 200;
    public static string $settingsComponent = TweetEmbedModuleSettings::class;
    public static string $templatesNamespace = 'modules.tweet_embed::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        $twitterUrl = get_option('twitter_url', $this->params['id']);
        if (!$twitterUrl) {
            return lnotif('Tweet Embed');
        }

        $statusID = explode('status/', $twitterUrl);
        if (!isset($statusID[1])) {
            return lnotif('Tweet Embed - Invalid URL');
        }

        $viewData['status_id'] = $statusID[1];

        return view(static::$templatesNamespace . '.default', $viewData);
    }
}
