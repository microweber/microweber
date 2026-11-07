<?php

declare(strict_types=1);

namespace Modules\Ai\Agents\Output;

use NeuronAI\StructuredOutput\SchemaProperty;

/**
 * The LOCKED global design system for a swarm build.
 *
 * The coordinator commits this ONCE, before any page lane runs: it maps the
 * tokens through set_css_var and applies one global apply_css. Every page lane
 * then inherits it, so the whole site reads as one piece.
 */
class SwarmDesignOutput
{
    #[SchemaProperty(
        description: 'The global design tokens as a flat map of CSS custom-property name to value, e.g. {"--mw-primary-color":"#1b7f3b","--mw-heading-color":"#182433","--mw-body-color":"#333333","--mw-background-color":"#ffffff","--mw-link-color":"#1b7f3b","--mw-btn-background-color":"#1b7f3b","--mw-btn-text-color":"#ffffff","--mw-heading-font-family":"Playfair Display","--mw-body-font-family":"Inter"}. Use real Microweber theme token names so the values flow through set_css_var and style every page at once.',
        required: true
    )]
    public array $tokens;

    #[SchemaProperty(
        description: 'A one-paragraph summary of the locked visual direction: the mood/era/style, the palette intent, the type pairing, the spacing/radius rhythm and the one class-name prefix all pages reuse. Describes what was decided ONCE and held constant across every page.',
        required: true
    )]
    public string $summary;

    #[SchemaProperty(
        description: 'The brand / site name shown in the GLOBAL site-header logo, e.g. "SOFT CLUB". Extract it from the brief (the name the site is for). The coordinator sets this as the site title BEFORE any lane runs, so the shared template header stops showing a previous build\'s brand. Leave empty only if the brief truly names no brand.',
        required: false
    )]
    public ?string $brand = null;
}
