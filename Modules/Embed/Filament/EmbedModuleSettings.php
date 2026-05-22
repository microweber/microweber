<?php

namespace Modules\Embed\Filament;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
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
                // task-2026-05-22-slice2-ai872 / AI-872 Slice 2 — Embed: code type + resizable textarea
                Select::make('options.code_type')
                    ->label('Code type')
                    ->live()
                    ->options([
                        'html'       => 'HTML',
                        'css'        => 'CSS',
                        'javascript' => 'JavaScript',
                    ])
                    ->default('html')
                    ->helperText('Select the type of code you are embedding.'),

                // AI-1023 / task-2026-05-22 — reactive badge shows active language mode.
                // AI-1021 / task-2026-05-22 — client-side syntax validation (moved to JS
                //   CodeMirror blur handler for AI-970 compatibility).
                // AI-970 / task-2026-05-22-dc3963 — CodeMirror syntax highlighting.
                //   data-mw-codemirror signals admin-filament.js to wrap this textarea.
                //   data-mw-code-type is updated reactively by Livewire when code_type changes,
                //   allowing the JS to update the CodeMirror mode without a full reinitialisation.
                Textarea::make('options.source_code')
                    ->label('Embed Code')
                    ->rows(10)
                    ->placeholder('Insert your embed code here')
                    ->hint(fn (Get $get): string => match ($get('options.code_type') ?? 'html') {
                        'css' => 'CSS',
                        'javascript' => 'JavaScript',
                        default => 'HTML',
                    })
                    ->hintColor('primary')
                    ->helperText('Paste HTML, CSS, or JavaScript embed code. The code is validated on blur.')
                    ->extraInputAttributes(fn (Get $get) => [
                        'style' => 'resize: vertical; min-height: 120px; font-family: monospace; font-size: 13px;',
                        'data-mw-codemirror' => 'true',
                        'data-mw-code-type'  => $get('options.code_type') ?? 'html',
                    ]),

                Toggle::make('options.hide_in_live_edit')
                    ->label('Hide in Live Edit')
                    ->live(),
            ]);
    }
}
