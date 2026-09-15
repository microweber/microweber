<?php

namespace Modules\Teamcard\Http\Controllers;

use MicroweberPackages\LiveEdit\Http\Controllers\ModuleItemsController;
use Modules\Teamcard\Models\Teamcard;

// task-2026-09-15-qskit — Teamcard inline item-list CRUD (see base controller).
// The photo (file) stays in the full settings editor.
class TeamcardItemsController extends ModuleItemsController
{
    protected function modelClass(): string
    {
        return Teamcard::class;
    }

    protected function fields(): array
    {
        return ['name', 'role', 'bio'];
    }
}
