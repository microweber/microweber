<?php

namespace Modules\Page\Filament;

use Modules\Content\Filament\ContentModuleSettings;
use Modules\Page\Models\Page;

class PageModuleSettings extends ContentModuleSettings
{
    public string $module = 'page';

    public string $contentModelClass = Page::class;

}
