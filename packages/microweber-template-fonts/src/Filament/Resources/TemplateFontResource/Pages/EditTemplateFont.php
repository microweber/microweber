<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Filament\Resources\TemplateFontResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use MicroweberPackages\TemplateFonts\Filament\Resources\TemplateFontResource;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;

class EditTemplateFont extends EditRecord
{
    protected static string $resource = TemplateFontResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(function () {
                    app(TemplateFontsManager::class)->clearCssCache();
                }),
        ];
    }

    protected function afterSave(): void
    {
        app(TemplateFontsManager::class)->clearCssCache();
    }
}
