<?php

namespace Modules\Testimonials\Http\Controllers;

use MicroweberPackages\LiveEdit\Http\Controllers\ModuleItemsController;
use Modules\Testimonials\Models\Testimonial;

// task-2026-09-15-qskit — Testimonials inline item-list CRUD (see base
// controller). Client image stays in the full settings editor.
class TestimonialItemsController extends ModuleItemsController
{
    protected function modelClass(): string
    {
        return Testimonial::class;
    }

    protected function fields(): array
    {
        return ['name', 'content', 'client_role', 'client_company'];
    }
}
