<?php

namespace Modules\Post\Filament;


use Modules\Content\Filament\ContentModuleSettings;
use Modules\Post\Models\Post;

class PostModuleSettings extends ContentModuleSettings
{
    public string $module = 'post';

    public string $contentModelClass = Post::class;

}
