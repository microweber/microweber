<?php

namespace Modules\Embed\Filament;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class EmbedModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'embed';

    /**
     * Restrict the Embed module settings to admins only by default.
     *
     * Embed renders user-supplied raw HTML / iframe / script. If a
     * non-admin editor role can author Embed content, that authoring
     * surface becomes a stored-XSS vector against every visitor of
     * the page (OWASP A03, OOYES_AUDITS/01_SECURITY_AUDITOR.md A03 BLOCKER).
     */
    public static function canAccess(): bool
    {
        return is_admin();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Textarea::make('options.source_code')
                    ->label('Embed Code')
                    ->rows(10)
                    ->placeholder('Insert your embed code here')
                    ->live(),

                Toggle::make('options.hide_in_live_edit')
                    ->label('Hide in Live Edit')
                    ->live(),
            ]);
    }
}
