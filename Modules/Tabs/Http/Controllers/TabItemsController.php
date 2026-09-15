<?php

namespace Modules\Tabs\Http\Controllers;

use MicroweberPackages\LiveEdit\Http\Controllers\ModuleItemsController;
use Modules\Tabs\Models\Tab;

// task-2026-09-15-qskit — Tabs inline item-list CRUD (see base controller).
class TabItemsController extends ModuleItemsController
{
    protected function modelClass(): string
    {
        return Tab::class;
    }

    protected function fields(): array
    {
        return ['title', 'content'];
    }
}
