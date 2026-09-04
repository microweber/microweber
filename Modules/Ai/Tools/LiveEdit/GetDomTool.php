<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit tool: read the current canvas DOM (the live page HTML).
 *
 * The frontend sends the canvas HTML with every turn; the controller binds it to
 * the request-scoped container key 'mw.ai.liveedit.context'. This tool returns
 * that DOM so the model can inspect the real, current page markup on demand
 * (existing tags/ids/classes) instead of guessing — e.g. before targeting an
 * element with set_text/apply_css. Read-only.
 */
class GetDomTool extends BaseTool
{
    protected string $domain = 'liveedit';

    /** Hard cap so a huge page can't blow the context window. */
    private const MAX = 24000;

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_dom',
            'Get the current page canvas DOM (the live HTML). Use it to read the real '
            . 'markup — existing tags, ids and classes — before targeting elements '
            . 'with set_text, set_image or apply_css. Optionally pass a CSS selector '
            . 'to return only the matching part of the DOM.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'selector',
                type: PropertyType::STRING,
                description: 'Optional CSS selector — return only the outerHTML of the first match. '
                    . 'Omit to return the whole page DOM (truncated if very large).',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $selector = trim((string) ($args['selector'] ?? ''));
        $ctx = app()->bound('mw.ai.liveedit.context') ? (array) app('mw.ai.liveedit.context') : [];
        $dom = (string) ($ctx['dom'] ?? '');

        if ($dom === '') {
            return $this->handleError(
                'No canvas DOM is available for this turn. The current page markup is also '
                . 'provided in the message under "[Current page canvas markup]".'
            );
        }

        // Optional selector narrowing (best-effort, DOMDocument).
        if ($selector !== '') {
            $part = $this->extractSelector($dom, $selector);
            if ($part !== null) {
                return json_encode([
                    'selector' => $selector,
                    'html' => mb_substr($part, 0, self::MAX),
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            return $this->handleError("No element matched the selector \"{$selector}\".");
        }

        $truncated = mb_strlen($dom) > self::MAX;
        return json_encode([
            'length' => mb_strlen($dom),
            'truncated' => $truncated,
            'dom' => mb_substr($dom, 0, self::MAX),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /** Best-effort outerHTML extraction for a simple #id or .class selector. */
    private function extractSelector(string $dom, string $selector): ?string
    {
        if (!preg_match('/^[#.][\w-]+$/', $selector)) {
            return null; // only simple id/class selectors supported
        }
        $isId = $selector[0] === '#';
        $needle = substr($selector, 1);

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?>' . $dom);
        libxml_clear_errors();
        $xpath = new \DOMXPath($doc);
        $query = $isId
            ? "//*[@id='" . $needle . "']"
            : "//*[contains(concat(' ', normalize-space(@class), ' '), ' " . $needle . " ')]";
        $nodes = $xpath->query($query);
        if ($nodes && $nodes->length > 0) {
            return $doc->saveHTML($nodes->item(0));
        }
        return null;
    }
}
