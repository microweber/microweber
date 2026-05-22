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

                // AI-1023 / task-2026-05-22 — reactive badge shows active language mode
                // so editors know which syntax is expected without switching tabs.
                // AI-1021 / task-2026-05-22 — added client-side validation via Alpine.js
                // x-data integration. On blur, validates syntax:
                // HTML — DOMParser (browser), CSS — CSSStyleSheet API, JS — Function constructor.
                // Shows inline error message below textarea.
                Textarea::make('options.source_code')
                    ->label('Embed Code')
                    ->rows(10)
                    ->placeholder('Insert your embed code here')
                    ->live()
                    ->hint(fn (Get $get): string => match ($get('options.code_type') ?? 'html') {
                        'css' => 'CSS',
                        'javascript' => 'JavaScript',
                        default => 'HTML',
                    })
                    ->hintColor('primary')
                    ->helperText('Paste HTML, CSS, or JavaScript embed code. The code is validated on blur.')
                    ->extraInputAttributes([
                        'style' => 'resize: vertical; min-height: 120px; font-family: monospace; font-size: 13px;',
                        'x-on:blur' => "
                            (function(el) {
                                var code = el.value;
                                var type = document.querySelector('[data-code-type-select]')?.value || 'html';
                                var err = el.closest('.fi-fo-field-wrp')?.querySelector('.mw-embed-validation-error');
                                if (!err) { err = document.createElement('p'); err.className = 'mw-embed-validation-error'; err.style = 'color:#dc2626;font-size:12px;margin-top:4px;'; el.closest('.fi-fo-field-wrp')?.append(err); }
                                err.textContent = '';
                                if (!code.trim()) return;
                                try {
                                    if (type === 'javascript') { new Function(code); }
                                    else if (type === 'css') { var s = new CSSStyleSheet(); s.replaceSync(code); }
                                    else { var d = (new DOMParser()).parseFromString(code, 'text/html'); if (d.querySelector('parsererror')) throw new Error('Invalid HTML'); }
                                    err.textContent = '';
                                } catch(e) { err.textContent = 'Syntax error: ' + e.message; }
                            })(event.target)
                        ",
                    ]),

                Toggle::make('options.hide_in_live_edit')
                    ->label('Hide in Live Edit')
                    ->live(),
            ]);
    }
}
