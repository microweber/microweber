<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: offer the user a small set of choices as clickable
 * pills in the chat, instead of listing options as plain text.
 *
 * Like every Live-Edit tool the real work is in the browser (mw-ai.js
 * frontendTools.offer_choices is a no-op; the conversation panel renders the
 * choices as pills). When the user clicks a pill, their choice is sent back as
 * their next chat message, so the agent can then act on it. This backend class
 * is only the declaration the model needs to be able to call the tool.
 */
class OfferChoicesTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'offer_choices',
            'Offer the user a few options as clickable pills instead of listing '
            . 'them as plain text. Use this WHENEVER you have a small set of '
            . 'concrete options for the user to pick from — alternative titles, '
            . 'colours, button styles, layouts, tones of voice, wording, etc. '
            . 'The user clicks one pill and their choice comes back to you as '
            . 'their next message, which you then apply. Prefer 2–5 short options. '
            . 'Do NOT also repeat the options as a bullet list in your reply.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'prompt',
                type: PropertyType::STRING,
                description: 'A short question or intro shown above the pills, e.g. '
                    . '"Which title do you prefer?" or "Pick a button colour:".',
                required: true,
            ),
            new ToolProperty(
                name: 'choices',
                type: PropertyType::STRING,
                description: 'The options to show as pills, ONE PER LINE (separate '
                    . 'each option with a newline). Give 2–5 short options, e.g.:'
                    . "\nExplore the Prehistoric World\nJourney Back in Time\n"
                    . 'Meet the Giants. Keep each option under ~8 words.',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $raw = (string) ($args['choices'] ?? '');
        $count = count(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|\|/', $raw) ?: [])));
        if ($count === 0) {
            return $this->handleError('No choices were provided.');
        }

        return "OK — offered {$count} choice(s) to the user as clickable pills. "
            . "STOP and wait for the user to click one; their choice will arrive "
            . "as their next message, and only then should you apply it.";
    }
}
