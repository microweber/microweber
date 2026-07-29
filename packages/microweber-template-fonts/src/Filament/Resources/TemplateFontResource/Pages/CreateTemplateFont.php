<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Filament\Resources\TemplateFontResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MicroweberPackages\TemplateFonts\Filament\Resources\TemplateFontResource;
use MicroweberPackages\TemplateFonts\Models\TemplateFont;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;

class CreateTemplateFont extends CreateRecord
{
    protected static string $resource = TemplateFontResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['is_enabled'] = (bool) ($data['is_enabled'] ?? true);
        $provider = $data['provider'] ?? TemplateFont::PROVIDER_GOOGLE;
        $data['provider'] = is_string($provider) ? $provider : TemplateFont::PROVIDER_GOOGLE;

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var TemplateFont $record */
        $record = $this->record;
        $manager = app(TemplateFontsManager::class);

        // Handle custom file upload from Filament FileUpload (stored path relative to disk)
        $uploadPath = $this->data['upload'] ?? null;
        if ($record->provider === TemplateFont::PROVIDER_CUSTOM && is_string($uploadPath) && $uploadPath !== '') {
            $absolute = Storage::disk('local')->path($uploadPath);
            if (is_file($absolute)) {
                $uploaded = new UploadedFile(
                    $absolute,
                    basename($absolute),
                    mime_content_type($absolute) ?: null,
                    null,
                    true
                );
                $result = $manager->uploadCustomFont($uploaded, $record->family);
                if ($result['success'] === true && isset($result['font'])) {
                    // Prefer the upload-created row; remove the empty create row if different
                    if ($result['font']->id !== $record->id) {
                        $record->delete();
                        $this->record = $result['font'];
                    }
                }
            }
        } elseif ($record->provider === TemplateFont::PROVIDER_GOOGLE) {
            $manager->enableFont($record->family, TemplateFont::PROVIDER_GOOGLE, $record->category);
            $record->refresh();
        }

        $manager->clearCssCache();
    }
}
