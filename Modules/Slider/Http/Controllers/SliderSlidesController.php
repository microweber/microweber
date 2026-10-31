<?php

namespace Modules\Slider\Http\Controllers;

use MicroweberPackages\LiveEdit\Http\Controllers\ModuleItemsController;
use Modules\Slider\Models\Slider;

// task-2026-09-15-qskit — Slider slides inline item-list CRUD (see base
// controller). The slide image (media) stays in the full settings editor.
class SliderSlidesController extends ModuleItemsController
{
    protected function modelClass(): string
    {
        return Slider::class;
    }

    protected function fields(): array
    {
        return ['name', 'description', 'button_text', 'link'];
    }

    protected function defaultContentKey(): ?string
    {
        return 'slides';
    }

    protected function defaultContentFile(): ?string
    {
        return base_path('Modules/Slider/resources/default-content/default_content.json');
    }

    protected function defaultSeedGuardKey(): ?string
    {
        return 'getSlidesCreatedDefault';
    }
}
