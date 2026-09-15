<?php

namespace Modules\Faq\Http\Controllers;

use MicroweberPackages\LiveEdit\Http\Controllers\ModuleItemsController;
use Modules\Faq\Models\Faq;

// task-2026-09-15-qskit — FAQ inline item-list CRUD (see base controller).
class FaqItemsController extends ModuleItemsController
{
    protected function modelClass(): string
    {
        return Faq::class;
    }

    protected function fields(): array
    {
        return ['question', 'answer'];
    }
}
