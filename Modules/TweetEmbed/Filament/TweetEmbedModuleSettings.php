<?php

namespace Modules\TweetEmbed\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class TweetEmbedModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'tweet_embed';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('options.twitter_url')
                    ->label('Tweet Post URL')
                    ->placeholder('Enter the URL of the tweet')
                    ->live(),
            ]);
    }
}
