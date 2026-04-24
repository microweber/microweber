<?php

namespace Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource;

/**
 * "Create" lands on the existing stateful import page so the
 * probe → summary → start flow stays on one screen. We do not
 * build a standalone create form — the probe step is not a plain
 * Eloquent insert, and duplicating it here would split the
 * validation and HTTP-probe logic across two places.
 */
class CreateWordPressMigration extends CreateRecord
{
    protected static string $resource = WordPressMigrationResource::class;

    public function mount(): void
    {
        $this->redirect('/admin/word-press-migration-import-page');
    }
}
