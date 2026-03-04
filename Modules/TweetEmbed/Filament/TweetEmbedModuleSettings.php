<?php

namespace Modules\TweetEmbed\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class TweetEmbedModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'tweet_embed';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('options.twitter_url')
                    ->label('Tweet Post URL')
                    ->placeholder('Enter the URL of the tweet')
                    ->live(),
            ]);
    }
}
